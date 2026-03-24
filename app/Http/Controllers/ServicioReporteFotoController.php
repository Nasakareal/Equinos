<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\ServicioReporte;
use App\Models\ServicioReporteFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ServicioReporteFotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:subir fotos reportes de servicios')->only(['store']);
        $this->middleware('can:eliminar fotos reportes de servicios')->only(['destroy']);
    }

    public function store(Request $request, Servicio $servicio, ServicioReporte $reporte)
    {
        abort_if($reporte->servicio_id !== $servicio->id, 404);

        $validatedData = $request->validate([
            'fotos' => ['required', 'array', 'min:1'],
            'fotos.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'descripcion' => ['nullable', 'array'],
            'descripcion.*' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($request->file('fotos', []) as $index => $archivo) {
            $nombre = Str::uuid()->toString() . '.' . $archivo->getClientOriginalExtension();
            $ruta = $archivo->storeAs('servicios/reportes', $nombre, 'public');

            $reporte->fotos()->create([
                'ruta' => $ruta,
                'nombre_original' => $archivo->getClientOriginalName(),
                'mime' => $archivo->getClientMimeType(),
                'size' => $archivo->getSize(),
                'descripcion' => $validatedData['descripcion'][$index] ?? null,
            ]);
        }

        Log::info('Fotos agregadas a reporte de servicio', [
            'servicio_id' => $servicio->id,
            'reporte_id' => $reporte->id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('mis_servicios.reportes.show', [$servicio, $reporte])
            ->with('success', 'Fotos subidas correctamente.');
    }

    public function destroy(Servicio $servicio, ServicioReporte $reporte, ServicioReporteFoto $foto)
    {
        abort_if($reporte->servicio_id !== $servicio->id, 404);
        abort_if($foto->servicio_reporte_id !== $reporte->id, 404);

        if ($foto->ruta && file_exists(storage_path('app/public/' . $foto->ruta))) {
            @unlink(storage_path('app/public/' . $foto->ruta));
        }

        $fotoId = $foto->id;
        $foto->delete();

        Log::info('Foto eliminada de reporte de servicio', [
            'servicio_id' => $servicio->id,
            'reporte_id' => $reporte->id,
            'foto_id' => $fotoId,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('mis_servicios.reportes.show', [$servicio, $reporte])
            ->with('success', 'Foto eliminada correctamente.');
    }
}
