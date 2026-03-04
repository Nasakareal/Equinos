<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    // GET /api/feed?limit=20&cursor=...
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 20);
        if ($limit < 1) $limit = 1;
        if ($limit > 50) $limit = 50;

        $cursor = (string) $request->query('cursor', '');
        $cursorData = $this->decodeCursor($cursor);

        $me = $request->user();
        $myArea = trim((string) ($me->area ?? ''));

        // Seguridad: si el usuario no tiene area, NO regresamos todo
        if ($myArea === '') {
            return response()->json([
                'limit' => $limit,
                'count' => 0,
                'has_more' => false,
                'next_cursor' => null,
                'data' => [],
            ]);
        }

        $q = DB::table('actividades as a')
            ->join('users as u', 'u.id', '=', 'a.created_by')
            ->leftJoin('actividad_categorias as ac', 'ac.id', '=', 'a.actividad_categoria_id')
            ->leftJoin('actividad_subcategorias as asc', 'asc.id', '=', 'a.actividad_subcategoria_id')
            ->where('u.area', '=', $myArea)
            ->select([
                DB::raw("'ACTIVIDAD' as type"),
                DB::raw('a.id as item_id'),
                DB::raw('a.created_by as user_id'),
                DB::raw('u.name as user_name'),
                DB::raw('a.nombre as resumen'),
                DB::raw('ac.id as categoria_id'),
                DB::raw('ac.nombre as categoria_nombre'),
                DB::raw('asc.id as subcategoria_id'),
                DB::raw('asc.nombre as subcategoria_nombre'),
                DB::raw('a.cantidad as cantidad'),
                DB::raw('a.foto_path as foto_path'),
                DB::raw('a.created_at as created_at'),
            ]);

        // Cursor: (created_at desc, id desc)
        if ($cursorData) {
            $created_at = $cursorData['created_at'] ?? null;
            $item_id    = isset($cursorData['item_id']) ? (int) $cursorData['item_id'] : null;

            if ($created_at && $item_id) {
                $q->where(function ($w) use ($created_at, $item_id) {
                    $w->where('a.created_at', '<', $created_at)
                      ->orWhere(function ($w2) use ($created_at, $item_id) {
                          $w2->where('a.created_at', '=', $created_at)
                             ->where('a.id', '<', $item_id);
                      });
                });
            }
        }

        $rows = $q->orderByDesc('a.created_at')
            ->orderByDesc('a.id')
            ->limit($limit + 1)
            ->get();

        $has_more = $rows->count() > $limit;
        if ($has_more) {
            $rows = $rows->take($limit)->values();
        }

        $mapped = $rows->map(function ($row) {
            $foto_url = null;

            if (!empty($row->foto_path)) {
                $path = ltrim((string) $row->foto_path, '/');

                if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                    $foto_url = $path;
                } else {
                    $foto_url = asset('storage/' . $path);
                }
            }

            $resumen = $this->limpiaResumen($row->resumen);

            return [
                'type' => 'ACTIVIDAD',
                'id' => (int) $row->item_id,

                'user' => [
                    'id' => (int) $row->user_id,
                    'name' => $row->user_name,
                ],

                'resumen' => $resumen,
                'cantidad' => (int) ($row->cantidad ?? 1),

                'categoria' => $row->categoria_id ? [
                    'id' => (int) $row->categoria_id,
                    'nombre' => $row->categoria_nombre,
                ] : null,

                'subcategoria' => $row->subcategoria_id ? [
                    'id' => (int) $row->subcategoria_id,
                    'nombre' => $row->subcategoria_nombre,
                ] : null,

                'foto_url' => $foto_url,
                'created_at' => $row->created_at,
                'show_url' => route('actividades.show', $row->item_id),
            ];
        });

        $next_cursor = null;
        if ($mapped->count() > 0) {
            $last = $mapped->last();
            $next_cursor = $this->encodeCursor([
                'created_at' => $last['created_at'],
                'item_id' => $last['id'],
            ]);
        }

        return response()->json([
            'limit' => $limit,
            'count' => $mapped->count(),
            'has_more' => $has_more,
            'next_cursor' => $next_cursor,
            'data' => $mapped,
        ]);
    }

    private function limpiaResumen($resumen): string
    {
        $txt = trim((string) $resumen);
        $txt = preg_replace('/\s+/', ' ', $txt);
        return $txt !== '' ? $txt : 'Actividad registrada';
    }

    private function encodeCursor(array $data): string
    {
        return rtrim(strtr(base64_encode(json_encode($data, JSON_UNESCAPED_UNICODE)), '+/', '-_'), '=');
    }

    private function decodeCursor(string $cursor): ?array
    {
        $cursor = trim($cursor);
        if ($cursor === '') return null;

        $b64 = strtr($cursor, '-_', '+/');
        $pad = strlen($b64) % 4;
        if ($pad) $b64 .= str_repeat('=', 4 - $pad);

        $json = base64_decode($b64, true);
        if ($json === false) return null;

        $data = json_decode($json, true);
        return is_array($data) ? $data : null;
    }
}
