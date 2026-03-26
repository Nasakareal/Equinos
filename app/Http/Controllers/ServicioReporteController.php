<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\ServicioReporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ServicioReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('can:ver reportes de servicios')->only([
            'misServicios',
            'panelServicio',
            'index',
            'create',
            'store',
            'show',
            'edit',
            'update',
            'whatsapp',
            'compartirNativo',
        ]);

        $this->middleware('can:crear reportes de servicios')->only([
            'create',
            'store',
        ]);

        $this->middleware('can:editar reportes de servicios')->only([
            'edit',
            'update',
        ]);

        $this->middleware('can:eliminar reportes de servicios')->only([
            'destroy',
        ]);

        $this->middleware('can:compartir whatsapp reportes de servicios')->only([
            'whatsapp',
            'compartirNativo',
        ]);
    }

    public function misServicios()
    {
        $servicios = Servicio::query()
            ->with([
                'patrulla',
                'canino',
                'equino',
                'reportes',
            ])
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->get();

        return view('mis_servicios.index', compact('servicios'));
    }

    public function panelServicio(Servicio $servicio)
    {
        $servicio->load([
            'creador',
            'personal',
            'canino',
            'equino',
            'patrulla',
            'estadoFuerza',
            'participantes',
            'coordenadas',
            'recursos',
            'reportes.creador',
            'reportes.fotos',
        ]);

        return view('mis_servicios.show', compact('servicio'));
    }

    public function index(Servicio $servicio)
    {
        $reportes = $servicio->reportes()
            ->with([
                'creador',
                'fotos',
            ])
            ->get();

        return view('mis_servicios.reportes.index', compact('servicio', 'reportes'));
    }

    private function normalizeText(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        $value = preg_replace('/\s+/u', ' ', $value);

        return mb_strtoupper($value, 'UTF-8');
    }

    private function validationRules(): array
    {
        return [
            'tipo_reporte' => ['required', 'string', Rule::in([
                'INICIO',
                'CONTINUIDAD',
                'FINALIZACION',
                'INCIDENTE',
                'RESULTADO',
                'PUESTA_DISPOSICION',
                'APOYO_BUSQUEDA',
                'EVENTO',
                'OTRO',
            ])],
            'fecha' => ['required', 'date'],
            'hora' => ['nullable', 'date_format:H:i'],
            'municipio' => ['nullable', 'string', 'max:255'],
            'lugar' => ['nullable', 'string', 'max:255'],
            'asunto' => ['nullable', 'string', 'max:255'],
            'narrativa' => ['nullable', 'string'],
            'estado_fuerza_texto' => ['nullable', 'string'],
            'acciones_a_realizar' => ['nullable', 'string'],
            'acciones_realizadas' => ['nullable', 'string'],
            'resultados' => ['nullable', 'string'],
            'datos_persona_asegurada' => ['nullable', 'string'],
            'conclusion' => ['nullable', 'string'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],

            'fotos' => ['nullable', 'array'],
            'fotos.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'descripcion' => ['nullable', 'array'],
            'descripcion.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function buildWhatsappText(Servicio $servicio, ServicioReporte $reporte): string
    {
        $lineas = [];

        $lineas[] = 'GUARDIA CIVIL MICHOACÁN';
        $lineas[] = '';
        $lineas[] = 'COORDINACIÓN DE AGRUPAMIENTOS';
        $lineas[] = '';

        if ($servicio->unidad_clave) {
            $lineas[] = $servicio->unidad_clave;
            $lineas[] = '';
        }

        if ($reporte->municipio) {
            $lineas[] = 'MUNICIPIO: ' . $reporte->municipio;
            $lineas[] = '';
        }

        if ($reporte->asunto) {
            $lineas[] = 'ASUNTO: ' . $reporte->asunto;
            $lineas[] = '';
        }

        $fecha = optional($reporte->fecha)->format('d/m/Y');
        $hora = $reporte->hora ? substr($reporte->hora, 0, 5) . ' HRS.' : null;
        $encabezado = trim($fecha . ' ' . ($hora ?? ''));

        if ($encabezado !== '') {
            $lineas[] = $encabezado;
            $lineas[] = '';
        }

        if ($reporte->narrativa) {
            $lineas[] = trim($reporte->narrativa);
            $lineas[] = '';
        }

        if ($reporte->estado_fuerza_texto) {
            $lineas[] = 'ESTADO DE FUERZA';
            $lineas[] = '';
            $lineas[] = trim($reporte->estado_fuerza_texto);
            $lineas[] = '';
        }

        if ($reporte->acciones_a_realizar) {
            $lineas[] = 'ACCIONES A REALIZAR';
            $lineas[] = '';
            $lineas[] = trim($reporte->acciones_a_realizar);
            $lineas[] = '';
        }

        if ($reporte->acciones_realizadas) {
            $lineas[] = 'ACCIONES REALIZADAS';
            $lineas[] = '';
            $lineas[] = trim($reporte->acciones_realizadas);
            $lineas[] = '';
        }

        if ($reporte->resultados) {
            $lineas[] = 'RESULTADOS';
            $lineas[] = '';
            $lineas[] = trim($reporte->resultados);
            $lineas[] = '';
        }

        if ($reporte->datos_persona_asegurada) {
            $lineas[] = 'DATOS DE LA PERSONA ASEGURADA';
            $lineas[] = '';
            $lineas[] = trim($reporte->datos_persona_asegurada);
            $lineas[] = '';
        }

        if ($reporte->conclusion) {
            $lineas[] = trim($reporte->conclusion);
            $lineas[] = '';
        }

        $lineas[] = 'QUEDANDO PENDIENTES A ÓRDENES DEL MANDO';

        return trim(implode("\n", $lineas));
    }

    private function guardarFotos(Request $request, ServicioReporte $reporte): void
    {
        if (!$request->hasFile('fotos')) {
            return;
        }

        foreach ($request->file('fotos') as $index => $archivo) {
            if (!$archivo) {
                continue;
            }

            $ruta = $archivo->store('servicios/reportes', 'public');

            $reporte->fotos()->create([
                'ruta' => $ruta,
                'nombre_original' => $archivo->getClientOriginalName(),
                'mime' => $archivo->getClientMimeType(),
                'size' => $archivo->getSize(),
                'descripcion' => $request->input("descripcion.$index"),
            ]);
        }
    }

    public function create(Servicio $servicio)
    {
        return view('mis_servicios.reportes.create', compact('servicio'));
    }

    public function store(Request $request, Servicio $servicio)
    {
        $validatedData = $request->validate($this->validationRules());

        $validatedData['tipo_reporte'] = $this->normalizeText($validatedData['tipo_reporte'] ?? null);
        $validatedData['municipio'] = $this->normalizeText($validatedData['municipio'] ?? $servicio->municipio);
        $validatedData['lugar'] = $this->normalizeText($validatedData['lugar'] ?? $servicio->lugar);
        $validatedData['asunto'] = $this->normalizeText($validatedData['asunto'] ?? $servicio->asunto);
        $validatedData['created_by'] = Auth::id();

        unset($validatedData['fotos'], $validatedData['descripcion']);

        $reporte = $servicio->reportes()->create($validatedData);

        $this->guardarFotos($request, $reporte);

        $reporte->whatsapp_texto = $this->buildWhatsappText($servicio, $reporte);
        $reporte->save();

        Log::info('Reporte de servicio creado', [
            'servicio_id' => $servicio->id,
            'reporte_id' => $reporte->id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('mis_servicios.reportes.show', [$servicio, $reporte])
            ->with('success', 'Reporte creado correctamente.');
    }

    public function show(Servicio $servicio, ServicioReporte $reporte)
    {
        abort_if($reporte->servicio_id !== $servicio->id, 404);

        $reporte->load([
            'creador',
            'fotos',
        ]);

        return view('mis_servicios.reportes.show', compact('servicio', 'reporte'));
    }

    public function edit(Servicio $servicio, ServicioReporte $reporte)
    {
        abort_if($reporte->servicio_id !== $servicio->id, 404);

        $reporte->load('fotos');

        return view('mis_servicios.reportes.edit', compact('servicio', 'reporte'));
    }

    public function update(Request $request, Servicio $servicio, ServicioReporte $reporte)
    {
        abort_if($reporte->servicio_id !== $servicio->id, 404);

        $validatedData = $request->validate($this->validationRules());

        $validatedData['tipo_reporte'] = $this->normalizeText($validatedData['tipo_reporte'] ?? null);
        $validatedData['municipio'] = $this->normalizeText($validatedData['municipio'] ?? null);
        $validatedData['lugar'] = $this->normalizeText($validatedData['lugar'] ?? null);
        $validatedData['asunto'] = $this->normalizeText($validatedData['asunto'] ?? null);

        unset($validatedData['fotos'], $validatedData['descripcion']);

        $reporte->update($validatedData);

        $this->guardarFotos($request, $reporte);

        $reporte->whatsapp_texto = $this->buildWhatsappText($servicio, $reporte);
        $reporte->save();

        Log::info('Reporte de servicio actualizado', [
            'servicio_id' => $servicio->id,
            'reporte_id' => $reporte->id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('mis_servicios.reportes.show', [$servicio, $reporte])
            ->with('success', 'Reporte actualizado correctamente.');
    }

    public function destroy(Servicio $servicio, ServicioReporte $reporte)
    {
        abort_if($reporte->servicio_id !== $servicio->id, 404);

        $reporte->load('fotos');

        foreach ($reporte->fotos as $foto) {
            if ($foto->ruta && Storage::disk('public')->exists($foto->ruta)) {
                Storage::disk('public')->delete($foto->ruta);
            }
        }

        $reporte->delete();

        Log::info('Reporte de servicio eliminado', [
            'servicio_id' => $servicio->id,
            'reporte_id' => $reporte->id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('mis_servicios.reportes.index', $servicio)
            ->with('success', 'Reporte eliminado correctamente.');
    }

    public function whatsapp(Servicio $servicio, ServicioReporte $reporte)
    {
        abort_if($reporte->servicio_id !== $servicio->id, 404);

        if (!$reporte->whatsapp_texto) {
            $reporte->whatsapp_texto = $this->buildWhatsappText($servicio, $reporte);
            $reporte->save();
        }

        return redirect()->away('https://wa.me/?text=' . urlencode($reporte->whatsapp_texto));
    }

    public function compartirNativo(Servicio $servicio, ServicioReporte $reporte)
    {
        abort_if($reporte->servicio_id !== $servicio->id, 404);

        $reporte->load('fotos');

        if (!$reporte->whatsapp_texto) {
            $reporte->whatsapp_texto = $this->buildWhatsappText($servicio, $reporte);
            $reporte->save();
        }

        $imagenes = [];

        foreach ($reporte->fotos as $foto) {
            if (!empty($foto->ruta) && Storage::disk('public')->exists($foto->ruta)) {
                $imagenes[] = asset('storage/' . $foto->ruta);
            }
        }

        return view('mis_servicios.reportes.compartir_nativo', [
            'servicio' => $servicio,
            'reporte' => $reporte,
            'texto' => $reporte->whatsapp_texto,
            'imagenes' => $imagenes,
        ]);
    }
}
