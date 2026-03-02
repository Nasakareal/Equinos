<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\PersonalHorario;
use App\Models\PersonalHorarioDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersonalHorarioDetalleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:editar personal')->only(['store', 'update', 'destroy']);
    }

    public function store(Request $request, Personal $personal, PersonalHorario $personal_horario)
    {
        if ((int)$personal_horario->personal_id !== (int)$personal->id) abort(404);

        $data = $request->validate([
            'dia_semana' => 'required|integer|min:0|max:6',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'min_tolerancia' => 'nullable|integer|min:0|max:240',
            'cruza_dia' => 'nullable|boolean',
            'bloque' => 'nullable|string|max:50',
            'notas' => 'nullable|string|max:500',
        ]);

        $cruza = (bool)($data['cruza_dia'] ?? false);

        if (!$cruza && $data['hora_fin'] <= $data['hora_inicio']) {
            return back()->withErrors('La hora fin debe ser mayor a la hora inicio.')->withInput();
        }

        try {
            DB::beginTransaction();

            $overlap = $this->hayTraslape(
                (int)$personal_horario->id,
                (int)$data['dia_semana'],
                $data['hora_inicio'],
                $data['hora_fin'],
                $cruza,
                null
            );

            if ($overlap) {
                DB::rollBack();
                return back()->withErrors('Ese tramo se traslapa con otro tramo de ese mismo día.')->withInput();
            }

            $payload = [
                'personal_horario_id' => (int)$personal_horario->id,
                'dia_semana' => (int)$data['dia_semana'],
                'hora_entrada' => $data['hora_inicio'],
                'hora_salida' => $data['hora_fin'],
                'min_tolerancia' => (int)($data['min_tolerancia'] ?? 0),
                'cruza_dia' => $cruza ? 1 : 0,
                'notas' => $data['notas'] ?? null,
            ];

            if (isset($data['bloque']) && trim((string)$data['bloque']) !== '') {
                $payload['bloque'] = trim((string)$data['bloque']);
            }

            PersonalHorarioDetalle::create($payload);

            DB::commit();

            return redirect()
                ->route('personal.horario.edit', $personal->id)
                ->with('success', 'Tramo agregado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creando PersonalHorarioDetalle: ' . $e->getMessage());
            return back()->withErrors('Hubo un error al agregar el tramo.')->withInput();
        }
    }

    public function update(Request $request, Personal $personal, PersonalHorario $personal_horario, PersonalHorarioDetalle $detalle)
    {
        if ((int)$personal_horario->personal_id !== (int)$personal->id) abort(404);
        if ((int)$detalle->personal_horario_id !== (int)$personal_horario->id) abort(404);

        $data = $request->validate([
            'dia_semana' => 'required|integer|min:0|max:6',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'min_tolerancia' => 'nullable|integer|min:0|max:240',
            'cruza_dia' => 'nullable|boolean',
            'bloque' => 'nullable|string|max:50',
            'notas' => 'nullable|string|max:500',
        ]);

        $cruza = (bool)($data['cruza_dia'] ?? false);

        if (!$cruza && $data['hora_fin'] <= $data['hora_inicio']) {
            return back()->withErrors('La hora fin debe ser mayor a la hora inicio.')->withInput();
        }

        try {
            DB::beginTransaction();

            $overlap = $this->hayTraslape(
                (int)$personal_horario->id,
                (int)$data['dia_semana'],
                $data['hora_inicio'],
                $data['hora_fin'],
                $cruza,
                (int)$detalle->id
            );

            if ($overlap) {
                DB::rollBack();
                return back()->withErrors('Ese tramo se traslapa con otro tramo de ese mismo día.')->withInput();
            }

            $payload = [
                'dia_semana' => (int)$data['dia_semana'],
                'hora_entrada' => $data['hora_inicio'],
                'hora_salida' => $data['hora_fin'],
                'min_tolerancia' => (int)($data['min_tolerancia'] ?? 0),
                'cruza_dia' => $cruza ? 1 : 0,
                'notas' => $data['notas'] ?? null,
            ];

            if (isset($data['bloque']) && trim((string)$data['bloque']) !== '') {
                $payload['bloque'] = trim((string)$data['bloque']);
            }

            $detalle->update($payload);

            DB::commit();

            return redirect()
                ->route('personal.horario.edit', $personal->id)
                ->with('success', 'Tramo actualizado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error actualizando PersonalHorarioDetalle: ' . $e->getMessage());
            return back()->withErrors('Hubo un error al actualizar el tramo.')->withInput();
        }
    }

    public function destroy(Personal $personal, PersonalHorario $personal_horario, PersonalHorarioDetalle $detalle)
    {
        if ((int)$personal_horario->personal_id !== (int)$personal->id) abort(404);
        if ((int)$detalle->personal_horario_id !== (int)$personal_horario->id) abort(404);

        try {
            $detalle->delete();

            return redirect()
                ->route('personal.horario.edit', $personal->id)
                ->with('success', 'Tramo eliminado correctamente.');
        } catch (\Throwable $e) {
            Log::error('Error eliminando PersonalHorarioDetalle: ' . $e->getMessage());
            return back()->withErrors('Hubo un error al eliminar el tramo.');
        }
    }

    private function hayTraslape(int $personalHorarioId, int $diaSemana, string $inicio, string $fin, bool $cruzaDia, ?int $ignoreId): bool
    {
        $q = PersonalHorarioDetalle::query()
            ->where('personal_horario_id', $personalHorarioId)
            ->where('dia_semana', $diaSemana);

        if ($ignoreId) $q->where('id', '!=', $ignoreId);

        $detalles = $q->get();

        $nuevo = $this->normalizaRango($inicio, $fin, $cruzaDia);

        foreach ($detalles as $d) {
            $existente = $this->normalizaRango($d->hora_entrada, $d->hora_salida, (bool)$d->cruza_dia);
            if ($this->rangosSeTraslapan($nuevo, $existente)) return true;
        }

        return false;
    }

    private function normalizaRango(string $inicio, string $fin, bool $cruzaDia): array
    {
        $i = $this->toMin($inicio);
        $f = $this->toMin($fin);

        if ($cruzaDia && $f <= $i) $f += 1440;
        if (!$cruzaDia && $f <= $i) $f = $i;

        return [$i, $f];
    }

    private function rangosSeTraslapan(array $a, array $b): bool
    {
        return max($a[0], $b[0]) < min($a[1], $b[1]);
    }

    private function toMin(string $hhmm): int
    {
        [$h, $m] = array_map('intval', explode(':', $hhmm));
        return ($h * 60) + $m;
    }
}
