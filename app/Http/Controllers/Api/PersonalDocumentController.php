<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use App\Models\PersonalDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PersonalDocumentController extends Controller
{
    private function transformDocument(PersonalDocument $documento): array
    {
        return array_merge($documento->toArray(), [
            'archivo_url' => $documento->archivo ? Storage::disk('public')->url($documento->archivo) : null,
        ]);
    }

    public function index(Request $request, Personal $personal)
    {
        $buscar = trim((string) $request->input('buscar'));

        $documentos = PersonalDocument::query()
            ->where('personal_id', $personal->id)
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('titulo', 'like', "%{$buscar}%")
                        ->orWhere('tipo_documento', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%")
                        ->orWhere('nombre_original', 'like', "%{$buscar}%")
                        ->orWhere('observaciones', 'like', "%{$buscar}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(15);

        $documentos->setCollection($documentos->getCollection()->map(function ($documento) {
            return $this->transformDocument($documento);
        }));

        return response()->json(['ok' => true, 'data' => $documentos]);
    }

    public function store(Request $request, Personal $personal)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo_documento' => 'nullable|string|max:150',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_documento' => 'nullable|date',
            'observaciones' => 'nullable|string|max:1000',
            'archivo' => 'required|file|max:20480',
        ]);

        try {
            $archivo = $request->file('archivo');
            $rutaArchivo = $archivo->store('personal_documentos', 'public');

            $documento = PersonalDocument::create([
                'personal_id' => $personal->id,
                'tipo_documento' => $validated['tipo_documento'] ?? null,
                'titulo' => $validated['titulo'],
                'descripcion' => $validated['descripcion'] ?? null,
                'archivo' => $rutaArchivo,
                'nombre_original' => $archivo->getClientOriginalName(),
                'mime_type' => $archivo->getClientMimeType(),
                'extension' => $archivo->getClientOriginalExtension(),
                'tamano' => $archivo->getSize(),
                'fecha_documento' => $validated['fecha_documento'] ?? null,
                'observaciones' => $validated['observaciones'] ?? null,
                'activo' => 1,
            ]);

            Log::info("API Documento creado para personal {$personal->id} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Documento subido correctamente.',
                'data' => $this->transformDocument($documento),
            ], 201);
        } catch (\Throwable $e) {
            Log::error("API Error al crear documento de personal {$personal->id}: " . $e->getMessage());
            return response()->json(['ok' => false, 'message' => 'Hubo un error al subir el documento.'], 500);
        }
    }

    public function show(Personal $personal, PersonalDocument $documento)
    {
        if ((int) $documento->personal_id !== (int) $personal->id) {
            abort(404);
        }

        return response()->json(['ok' => true, 'data' => $this->transformDocument($documento)]);
    }

    public function update(Request $request, Personal $personal, PersonalDocument $documento)
    {
        if ((int) $documento->personal_id !== (int) $personal->id) {
            abort(404);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo_documento' => 'nullable|string|max:150',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_documento' => 'nullable|date',
            'observaciones' => 'nullable|string|max:1000',
            'archivo' => 'nullable|file|max:20480',
            'activo' => 'nullable|boolean',
        ]);

        try {
            $dataToUpdate = [
                'tipo_documento' => $validated['tipo_documento'] ?? null,
                'titulo' => $validated['titulo'],
                'descripcion' => $validated['descripcion'] ?? null,
                'fecha_documento' => $validated['fecha_documento'] ?? null,
                'observaciones' => $validated['observaciones'] ?? null,
                'activo' => (bool) ($validated['activo'] ?? true),
            ];

            if ($request->hasFile('archivo')) {
                if (!empty($documento->archivo) && Storage::disk('public')->exists($documento->archivo)) {
                    Storage::disk('public')->delete($documento->archivo);
                }

                $archivo = $request->file('archivo');
                $rutaArchivo = $archivo->store('personal_documentos', 'public');

                $dataToUpdate['archivo'] = $rutaArchivo;
                $dataToUpdate['nombre_original'] = $archivo->getClientOriginalName();
                $dataToUpdate['mime_type'] = $archivo->getClientMimeType();
                $dataToUpdate['extension'] = $archivo->getClientOriginalExtension();
                $dataToUpdate['tamano'] = $archivo->getSize();
            }

            $documento->update($dataToUpdate);

            Log::info("API Documento {$documento->id} actualizado para personal {$personal->id} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json([
                'ok' => true,
                'message' => 'Documento actualizado correctamente.',
                'data' => $this->transformDocument($documento->fresh()),
            ]);
        } catch (\Throwable $e) {
            Log::error("API Error al actualizar documento {$documento->id}: " . $e->getMessage());
            return response()->json(['ok' => false, 'message' => 'Hubo un error al actualizar el documento.'], 500);
        }
    }

    public function destroy(Personal $personal, PersonalDocument $documento)
    {
        if ((int) $documento->personal_id !== (int) $personal->id) {
            abort(404);
        }

        try {
            if (!empty($documento->archivo) && Storage::disk('public')->exists($documento->archivo)) {
                Storage::disk('public')->delete($documento->archivo);
            }

            $documento->delete();

            Log::info("API Documento {$documento->id} eliminado del personal {$personal->id} por usuario " . (Auth::id() ?? 'N/A'));

            return response()->json(['ok' => true, 'message' => 'Documento eliminado correctamente.']);
        } catch (\Throwable $e) {
            Log::error("API Error al eliminar documento {$documento->id}: " . $e->getMessage());
            return response()->json(['ok' => false, 'message' => 'Hubo un error al eliminar el documento.'], 500);
        }
    }

    public function download(Personal $personal, PersonalDocument $documento)
    {
        if ((int) $documento->personal_id !== (int) $personal->id) {
            abort(404);
        }

        if (empty($documento->archivo) || !Storage::disk('public')->exists($documento->archivo)) {
            return response()->json(['ok' => false, 'message' => 'El archivo no existe o ya fue eliminado.'], 404);
        }

        return Storage::disk('public')->download($documento->archivo, $documento->nombre_original ?: basename($documento->archivo));
    }
}
