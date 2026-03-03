<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Actividad;
use App\Models\ActividadSubcategoria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ActividadController extends Controller
{
    public function index(Request $request)
    {
        $tz = 'America/Mexico_City';

        $fecha = (string) $request->query('fecha', now($tz)->toDateString());
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            $fecha = now($tz)->toDateString();
        }

        $inicioDia = Carbon::parse($fecha, $tz)->startOfDay();
        $finDia = Carbon::parse($fecha, $tz)->endOfDay();

        $q = trim((string) $request->query('q', ''));
        $categoriaId = $request->query('actividad_categoria_id');
        $subcategoriaId = $request->query('actividad_subcategoria_id');

        $query = Actividad::query()
            ->with(['categoria:id,nombre,slug', 'subcategoria:id,nombre,slug'])
            ->whereBetween('created_at', [$inicioDia, $finDia])
            ->orderByDesc('created_at');

        if (!empty($categoriaId)) {
            $query->where('actividad_categoria_id', (int) $categoriaId);
        }

        if (!empty($subcategoriaId)) {
            $query->where('actividad_subcategoria_id', (int) $subcategoriaId);
        }

        if ($q !== '') {
            $query->where('nombre', 'like', '%' . $q . '%');
        }

        $perPage = (int) $request->query('per_page', 20);
        if ($perPage < 5) $perPage = 5;
        if ($perPage > 100) $perPage = 100;

        $items = $query->paginate($perPage);

        $items->getCollection()->transform(function ($a) {
            $a->foto_url = $a->foto_path ? asset('storage/' . ltrim($a->foto_path, '/')) : null;
            return $a;
        });

        return response()->json([
            'ok' => true,
            'data' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'actividad_categoria_id' => 'required|exists:actividad_categorias,id',
            'actividad_subcategoria_id' => 'nullable|exists:actividad_subcategorias,id',
            'foto' => 'required|file|mimes:jpg,jpeg,png,webp|max:4096',
            'cantidad' => 'nullable|integer|min:1|max:9999',
            'nombre' => 'nullable|string|max:255',
        ]);

        if (!empty($validated['actividad_subcategoria_id'])) {
            $ok = ActividadSubcategoria::query()
                ->where('id', (int) $validated['actividad_subcategoria_id'])
                ->where('actividad_categoria_id', (int) $validated['actividad_categoria_id'])
                ->exists();

            if (!$ok) {
                return response()->json([
                    'ok' => false,
                    'message' => 'La subcategoría no pertenece a la categoría seleccionada.',
                    'errors' => ['actividad_subcategoria_id' => ['La subcategoría no pertenece a la categoría seleccionada.']]
                ], 422);
            }
        }

        $userName = (string) (auth()->user()->name ?? '');
        $nombre = $validated['nombre'] ?? $userName;
        $nombre = mb_strtoupper(trim((string) $nombre), 'UTF-8');
        if ($nombre === '') $nombre = $userName;

        $cantidad = (int) ($validated['cantidad'] ?? 1);
        if ($cantidad < 1) $cantidad = 1;

        try {
            return DB::transaction(function () use ($request, $validated, $nombre, $cantidad) {

                $file = $request->file('foto');
                $fotoHash = hash_file('sha256', $file->getRealPath());

                $yaExiste = Actividad::query()->where('foto_hash', $fotoHash)->exists();
                if ($yaExiste) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'Esta foto ya fue subida anteriormente (mismo contenido).',
                        'errors' => ['foto' => ['Esta foto ya fue subida anteriormente (mismo contenido).']]
                    ], 422);
                }

                $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
                $filename = now('America/Mexico_City')->format('Ymd_His') . '_' . Str::random(10) . '.' . $ext;

                $fotoPath = $file->storeAs('actividades', $filename, 'public');

                $actividad = Actividad::create([
                    'actividad_categoria_id' => (int) $validated['actividad_categoria_id'],
                    'actividad_subcategoria_id' => !empty($validated['actividad_subcategoria_id']) ? (int) $validated['actividad_subcategoria_id'] : null,
                    'nombre' => $nombre,
                    'cantidad' => $cantidad,
                    'foto_path' => $fotoPath,
                    'foto_nombre_original' => $file->getClientOriginalName(),
                    'foto_hash' => $fotoHash,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                $actividad->load(['categoria:id,nombre,slug', 'subcategoria:id,nombre,slug']);
                $actividad->foto_url = $actividad->foto_path ? asset('storage/' . ltrim($actividad->foto_path, '/')) : null;

                return response()->json([
                    'ok' => true,
                    'message' => 'Actividad creada correctamente.',
                    'data' => $actividad,
                ], 201);
            });
        } catch (\Exception $e) {
            Log::error("API Error al crear actividad: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al crear la actividad.'
            ], 500);
        }
    }

    public function show(Actividad $actividad)
    {
        $actividad->load(['categoria:id,nombre,slug', 'subcategoria:id,nombre,slug']);
        $actividad->foto_url = $actividad->foto_path ? asset('storage/' . ltrim($actividad->foto_path, '/')) : null;

        return response()->json([
            'ok' => true,
            'data' => $actividad,
        ]);
    }

    public function destroy(Actividad $actividad)
    {
        try {
            if (!empty($actividad->foto_path) && Storage::disk('public')->exists($actividad->foto_path)) {
                Storage::disk('public')->delete($actividad->foto_path);
            }

            $actividad->delete();

            return response()->json([
                'ok' => true,
                'message' => 'Actividad eliminada correctamente.',
            ]);
        } catch (\Exception $e) {
            Log::error("API Error al eliminar actividad: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar la actividad.'
            ], 500);
        }
    }
}
