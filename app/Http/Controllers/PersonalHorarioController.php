<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\PersonalHorario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersonalHorarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:editar personal')->only(['edit', 'store']);
    }

    public function edit(Personal $personal)
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

        return view('personal.horario', compact('personal', 'horario'));
    }

    public function store(Request $request, Personal $personal)
    {
        $data = $request->validate([
            'nombre' => 'nullable|string|max:120',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'activo' => 'nullable|boolean',
        ]);

        $data['activo'] = (bool)($data['activo'] ?? true);

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

            if ($horario && (int)$horario->activo === 1) {
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

            return redirect()
                ->route('personal.horario.edit', $personal->id)
                ->with('success', 'Horario guardado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error guardando PersonalHorario: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withErrors('Hubo un error al guardar el horario.')
                ->withInput();
        }
    }
}
