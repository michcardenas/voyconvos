<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pagina;
use App\Models\MetadatoPagina;
use Illuminate\Validation\Rule;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $paginas = Pagina::all();

        return view('admin.configuracion.botones');
    }

     public function seo()
    {
        $metadatos = MetadatoPagina::all();
        
        // Páginas disponibles
        $paginasDisponibles = [
            'inicio' => 'Página de Inicio',
            'nosotros' => 'Nosotros / Acerca de',
            'contactanos' => 'Contáctanos',
            'como_funciona' => 'Cómo Funciona'
        ];
        
        return view('admin.configuracion.seo', compact('metadatos', 'paginasDisponibles'));
    }

    /**
     * Obtener metadatos de una página específica (AJAX)
     */
    public function obtenerMetadatos(Request $request)
    {
        $request->validate([
            'pagina' => 'required|string'
        ]);

        $metadato = MetadatoPagina::where('pagina', $request->pagina)->first();

        if ($metadato) {
            return response()->json([
                'success' => true,
                'existe' => true,
                'data' => $metadato
            ]);
        }

        return response()->json([
            'success' => true,
            'existe' => false,
            'data' => [
                'pagina' => $request->pagina,
                'meta_title' => '',
                'meta_description' => '',
                'meta_keywords' => '',
                'canonical_url' => '',
                'meta_robots' => 'index, follow',
                'extra_meta' => ''
            ]
        ]);
    }

    /**
     * Guardar o actualizar metadatos
     */
    public function guardarMetadatos(Request $request)
    {
        $request->validate([
            'pagina' => [
                'required',
                'string',
                Rule::in(['inicio', 'nosotros', 'contactanos', 'como_funciona'])
            ],
            'meta_title' => 'required|string|max:60',
            'meta_description' => 'required|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:255',
            'meta_robots' => 'required|string|max:100',
            'extra_meta' => 'nullable|string'
        ], [
            'meta_title.required' => 'El título SEO es obligatorio',
            'meta_title.max' => 'El título SEO no debe exceder 60 caracteres',
            'meta_description.required' => 'La descripción SEO es obligatoria',
            'meta_description.max' => 'La descripción SEO no debe exceder 160 caracteres',
            'canonical_url.url' => 'La URL canónica debe ser una URL válida',
            'pagina.in' => 'La página seleccionada no es válida'
        ]);

        try {
            $metadato = MetadatoPagina::updateOrCreate(
                ['pagina' => $request->pagina],
                [
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'meta_keywords' => $request->meta_keywords,
                    'canonical_url' => $request->canonical_url,
                    'meta_robots' => $request->meta_robots,
                    'extra_meta' => $request->extra_meta,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Metadatos guardados exitosamente',
                'data' => $metadato
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar los metadatos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar metadatos de una página
     */
    public function eliminarMetadatos(Request $request)
    {
        $request->validate([
            'pagina' => 'required|string'
        ]);

        try {
            $metadato = MetadatoPagina::where('pagina', $request->pagina)->first();
            
            if (!$metadato) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron metadatos para esta página'
                ], 404);
            }

            $metadato->delete();

            return response()->json([
                'success' => true,
                'message' => 'Metadatos eliminados exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar los metadatos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Previsualización de metadatos
     */
    public function previsualizarMetadatos(Request $request)
    {
        $request->validate([
            'meta_title' => 'required|string',
            'meta_description' => 'required|string'
        ]);

        return response()->json([
            'success' => true,
            'preview' => [
                'title' => $request->meta_title,
                'description' => $request->meta_description,
                'title_length' => strlen($request->meta_title),
                'description_length' => strlen($request->meta_description)
            ]
        ]);
    }

}
