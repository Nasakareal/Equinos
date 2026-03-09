<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\PersonalDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PersonalDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('can:ver personal')->only(['index', 'show', 'download']);
        $this->middleware('can:editar personal')->only(['create', 'store', 'edit', 'update', 'destroy']);
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
            ->paginate(15)
            ->appends(['buscar' => $buscar]);

        return view('personal.documentos.index', compact('personal', 'documentos', 'buscar'));
    }

    public function create(Personal $personal)
    {
        return view('personal.documentos.create', compact('personal'));
    }

    public function store(Request $request, Personal $personal)
    {
        $validatedData = $request->validate([
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

            PersonalDocument::create([
                'personal_id' => $personal->id,
                'tipo_documento' => $validatedData['tipo_documento'] ?? null,
                'titulo' => $validatedData['titulo'],
                'descripcion' => $validatedData['descripcion'] ?? null,
                'archivo' => $rutaArchivo,
                'nombre_original' => $archivo->getClientOriginalName(),
                'mime_type' => $archivo->getClientMimeType(),
                'extension' => $archivo->getClientOriginalExtension(),
                'tamano' => $archivo->getSize(),
                'fecha_documento' => $validatedData['fecha_documento'] ?? null,
                'observaciones' => $validatedData['observaciones'] ?? null,
                'activo' => 1,
            ]);

            Log::info("Documento creado para personal {$personal->id} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()
                ->route('personal.documentos.index', $personal->id)
                ->with('success', 'Documento subido correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear documento de personal {$personal->id}: " . $e->getMessage());

            return redirect()
                ->back()
                ->withErrors('Hubo un error al subir el documento.')
                ->withInput();
        }
    }

    public function show(Personal $personal, PersonalDocument $documento)
    {
        if ((int) $documento->personal_id !== (int) $personal->id) {
            abort(404);
        }

        return view('personal.documentos.show', compact('personal', 'documento'));
    }

    public function edit(Personal $personal, PersonalDocument $documento)
    {
        if ((int) $documento->personal_id !== (int) $personal->id) {
            abort(404);
        }

        return view('personal.documentos.edit', compact('personal', 'documento'));
    }

    public function update(Request $request, Personal $personal, PersonalDocument $documento)
    {
        if ((int) $documento->personal_id !== (int) $personal->id) {
            abort(404);
        }

        $validatedData = $request->validate([
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
                'tipo_documento' => $validatedData['tipo_documento'] ?? null,
                'titulo' => $validatedData['titulo'],
                'descripcion' => $validatedData['descripcion'] ?? null,
                'fecha_documento' => $validatedData['fecha_documento'] ?? null,
                'observaciones' => $validatedData['observaciones'] ?? null,
                'activo' => (bool) ($validatedData['activo'] ?? true),
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

            Log::info("Documento {$documento->id} actualizado para personal {$personal->id} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()
                ->route('personal.documentos.index', $personal->id)
                ->with('success', 'Documento actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar documento {$documento->id}: " . $e->getMessage());

            return redirect()
                ->back()
                ->withErrors('Hubo un error al actualizar el documento.')
                ->withInput();
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

            $documentoId = $documento->id;

            $documento->delete();

            Log::info("Documento {$documentoId} eliminado del personal {$personal->id} por usuario " . (Auth::id() ?? 'N/A'));

            return redirect()
                ->route('personal.documentos.index', $personal->id)
                ->with('success', 'Documento eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar documento {$documento->id}: " . $e->getMessage());

            return redirect()
                ->back()
                ->withErrors('Hubo un error al eliminar el documento.');
        }
    }

    public function download(Personal $personal, PersonalDocument $documento)
    {
        if ((int) $documento->personal_id !== (int) $personal->id) {
            abort(404);
        }

        if (empty($documento->archivo) || !Storage::disk('public')->exists($documento->archivo)) {
            return redirect()
                ->route('personal.documentos.index', $personal->id)
                ->withErrors('El archivo no existe o ya fue eliminado.');
        }

        return Storage::disk('public')->download(
            $documento->archivo,
            $documento->nombre_original ?: basename($documento->archivo)
        );
    }
}
