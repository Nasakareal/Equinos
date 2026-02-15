<?php

namespace App\Http\Controllers;

use App\Models\Responsable;
use App\Models\Personal;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResponsableController extends Controller
{
    public function index()
    {
        $responsables = Responsable::query()
            ->with(['personal', 'area'])
            ->orderBy('nivel')
            ->orderByDesc('activo')
            ->get();

        return view('admin.settings.responsables.index', compact('responsables'));
    }

    public function create()
    {
        $personals = Personal::query()
            ->where('activo', 1)
            ->orderBy('nombres')
            ->get();

        $areas = Area::query()
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return view('admin.settings.responsables.create', compact('personals', 'areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'personal_id' => 'required|exists:personals,id',
            'nivel'       => 'required|in:GENERAL,AREA',
            'area_id'     => 'nullable|exists:areas,id',
            'activo'      => 'nullable|boolean',
        ]);

        $activo = (bool) ($validated['activo'] ?? true);

        if ($validated['nivel'] === 'GENERAL') {
            $validated['area_id'] = null;
        }

        if ($validated['nivel'] === 'AREA' && empty($validated['area_id'])) {
            return redirect()->back()
                ->withErrors(['area_id' => 'Selecciona un área para el responsable.'])
                ->withInput();
        }

        DB::transaction(function () use ($validated, $activo) {

            if ($activo) {

                if ($validated['nivel'] === 'GENERAL') {
                    Responsable::query()
                        ->where('nivel', 'GENERAL')
                        ->where('activo', 1)
                        ->update(['activo' => 0]);
                }

                if ($validated['nivel'] === 'AREA') {
                    Responsable::query()
                        ->where('nivel', 'AREA')
                        ->where('area_id', $validated['area_id'])
                        ->where('activo', 1)
                        ->update(['activo' => 0]);
                }
            }

            Responsable::create([
                'personal_id' => $validated['personal_id'],
                'nivel'       => $validated['nivel'],
                'area_id'     => $validated['area_id'],
                'activo'      => $activo,
            ]);
        });

        return redirect()->route('responsables.index')
            ->with('success', 'Responsable asignado correctamente.');
    }

    public function edit(Responsable $responsable)
    {
        $personals = Personal::query()
            ->where('activo', 1)
            ->orderBy('nombres')
            ->get();

        $areas = Area::query()
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return view('admin.settings.responsables.edit', compact('responsable', 'personals', 'areas'));
    }

    public function update(Request $request, Responsable $responsable)
    {
        $validated = $request->validate([
            'personal_id' => 'required|exists:personals,id',
            'nivel'       => 'required|in:GENERAL,AREA',
            'area_id'     => 'nullable|exists:areas,id',
            'activo'      => 'nullable|boolean',
        ]);

        $activo = (bool) ($validated['activo'] ?? true);

        if ($validated['nivel'] === 'GENERAL') {
            $validated['area_id'] = null;
        }

        if ($validated['nivel'] === 'AREA' && empty($validated['area_id'])) {
            return redirect()->back()
                ->withErrors(['area_id' => 'Selecciona un área para el responsable.'])
                ->withInput();
        }

        DB::transaction(function () use ($validated, $activo, $responsable) {

            if ($activo) {

                if ($validated['nivel'] === 'GENERAL') {
                    Responsable::query()
                        ->where('nivel', 'GENERAL')
                        ->where('activo', 1)
                        ->where('id', '!=', $responsable->id)
                        ->update(['activo' => 0]);
                }

                if ($validated['nivel'] === 'AREA') {
                    Responsable::query()
                        ->where('nivel', 'AREA')
                        ->where('area_id', $validated['area_id'])
                        ->where('activo', 1)
                        ->where('id', '!=', $responsable->id)
                        ->update(['activo' => 0]);
                }
            }

            $responsable->update([
                'personal_id' => $validated['personal_id'],
                'nivel'       => $validated['nivel'],
                'area_id'     => $validated['area_id'],
                'activo'      => $activo,
            ]);
        });

        return redirect()->route('responsables.index')
            ->with('success', 'Responsable actualizado correctamente.');
    }

    public function destroy(Responsable $responsable)
    {
        $responsable->delete();

        return redirect()->route('responsables.index')
            ->with('success', 'Responsable eliminado correctamente.');
    }
}
