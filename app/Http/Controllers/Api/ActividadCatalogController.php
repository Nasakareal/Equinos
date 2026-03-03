<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActividadCategoria;
use App\Models\ActividadSubcategoria;

class ActividadCatalogController extends Controller
{
    public function categorias()
    {
        $items = ActividadCategoria::query()
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'slug']);

        return response()->json([
            'ok' => true,
            'data' => $items,
        ]);
    }

    public function subcategorias($categoriaId)
    {
        $items = ActividadSubcategoria::query()
            ->where('actividad_categoria_id', (int) $categoriaId)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get(['id', 'actividad_categoria_id', 'nombre', 'slug']);

        return response()->json([
            'ok' => true,
            'data' => $items,
        ]);
    }
}
