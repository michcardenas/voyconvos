<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pagina;
use App\Models\Seccion;
use App\Models\Contenido;

class PaginaInicioSeeder extends Seeder
{
    public function run(): void
    {
        // Crear la página "Inicio"
        $pagina = Pagina::firstOrCreate(['nombre' => 'Inicio']);

        // Secciones de la página de inicio
        $secciones = [
            'hero' => [
                ['clave' => 'h1', 'valor' => 'Comparte tu viaje en auto'],
                ['clave' => 'h2', 'valor' => 'Ahorra dinero y conecta con otras personas'],
                ['clave' => 'btn_buscar', 'valor' => 'Buscar viaje'],
                ['clave' => 'btn_publicar', 'valor' => 'Publicar viaje'],
                ['clave' => 'background', 'valor' => asset('img/fondo.jpg')],
                ['clave' => 'ahorro_texto', 'valor' => 'Ahorra hasta'],
                ['clave' => 'ahorro_valor', 'valor' => '$ 100'],
                ['clave' => 'ahorro_sufijo', 'valor' => 'en cada viaje'],
                ['clave' => 'btn_publicar_main', 'valor' => 'Publica un viaje'],
                ['clave' => 'como_funciona', 'valor' => '¿Cómo funciona?'],
            ],
            'features' => [
                ['clave' => 'titulo', 'valor' => '¿Por qué elegir VoyConVos?'],

                ['clave' => 'feature_1_icon', 'valor' => 'fa-coins'],
                ['clave' => 'feature_1_titulo', 'valor' => 'Ahorra en cada viaje'],
                ['clave' => 'feature_1_texto', 'valor' => 'Comparte los gastos de gasolina y peajes con otros viajeros.'],

                ['clave' => 'feature_2_icon', 'valor' => 'fa-users'],
                ['clave' => 'feature_2_titulo', 'valor' => 'Conoce nuevas personas'],
                ['clave' => 'feature_2_texto', 'valor' => 'Conecta con gente que comparte tu ruta e intereses.'],

                ['clave' => 'feature_3_icon', 'valor' => 'fa-leaf'],
                ['clave' => 'feature_3_titulo', 'valor' => 'Cuida el medio ambiente'],
                ['clave' => 'feature_3_texto', 'valor' => 'Reduce la contaminación compartiendo vehículo.'],
            ],
            'slogan' => [
                ['clave' => 'titulo', 'valor' => 'Conduce. Comparte. Ahorra.'],
                ['clave' => 'boton', 'valor' => 'Publica un viaje'],
            ]
        ];

        // Crear secciones y contenidos
        foreach ($secciones as $slug => $contenidos) {
            $seccion = Seccion::firstOrCreate([
                'pagina_id' => $pagina->id,
                'slug' => $slug
            ], [
                'titulo' => ucfirst($slug),
            ]);

            foreach ($contenidos as $contenido) {
                Contenido::updateOrCreate(
                    ['seccion_id' => $seccion->id, 'clave' => $contenido['clave']],
                    ['valor' => $contenido['valor']]
                );
            }
        }
    }
}
