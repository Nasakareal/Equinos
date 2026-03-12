<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use App\Models\PersonalHorario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersonalHorarioController extends Controller
{
    public function show(Personal $personal)
    {
        $horario = PersonalHorario::query()
            ->where('personal_id', $personal->id)
            ->where('activo', 1)
            ->with(['detalles' => function ($q) {
                $q->orderBy('dia_semana')->orderBy('hora_entrada');
            }])
            ->first();

        if (!$horario) {
            $horario = PersonalHorario::create([
                'personal_id' => $personal->id,
                'activo' => 1,
                'nombre' => 'HORARIO MIXTO',
                'fecha_inicio' => now()->toDateString(),
                'fecha_fin' => null,
            ]);

            $horario->load(['detalles' => function ($q) {
                $q->orderBy('dia_semana')->orderBy('hora_entrada');
            }]);
        }

        return response()->json([
            'ok' => true,
            'data' => $horario,
        ]);
    }

    public function store(Request $request, Personal $personal)
    {
        $data = $request->validate([
            'nombre' => 'nullable|string|max:120',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'activo' => 'nullable|boolean',
        ]);

        $data['activo'] = (bool) ($data['activo'] ?? true);

        try {
            DB::beginTransaction();

            if ($data['activo']) {
                PersonalHorario::query()
                    ->where('personal_id', $personal->id)
                    ->where('activo', 1)
                    ->update(['activo' => 0]);
            }

            $horario = PersonalHorario::query()
                ->where('personal_id', $personal->id)
                ->orderByDesc('id')
                ->first();

            if ($horario && (int) $horario->activo === 1) {
                $horario->update([
                    'nombre' => $data['nombre'] ?? $horario->nombre,
                    'fecha_inicio' => $data['fecha_inicio'],
                    'fecha_fin' => $data['fecha_fin'] ?? null,
                    'activo' => $data['activo'] ? 1 : 0,
                ]);
            } else {
                $horario = PersonalHorario::create([
                    'personal_id' => $personal->id,
                    'nombre' => $data['nombre'] ?? 'HORARIO MIXTO',
                    'fecha_inicio' => $data['fecha_inicio'],
                    'fecha_fin' => $data['fecha_fin'] ?? null,
                    'activo' => $data['activo'] ? 1 : 0,
                ]);
            }

            DB::commit();

            return response()->json([
                'ok' => true,
                'message' => 'Horario guardado correctamente.',
                'data' => $horario->load(['detalles' => function ($q) {
                    $q->orderBy('dia_semana')->orderBy('hora_entrada');
                }]),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('API Error guardando PersonalHorario: ' . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al guardar el horario.',
            ], 500);
        }
    }
}
