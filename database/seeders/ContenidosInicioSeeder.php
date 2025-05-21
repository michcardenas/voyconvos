<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pagina;
use App\Models\Contenido;

class ContenidosInicioSeeder extends Seeder
{
    public function run(): void
    {
        // Crear o buscar la página "Inicio"
        $pagina = Pagina::firstOrCreate(['nombre' => 'Inicio']);

        // Lista de contenidos de la página de inicio
        $contenidos = [
            ['clave' => 'hero_h1', 'valor' => 'Comparte tu viaje en auto'],
            ['clave' => 'hero_h2', 'valor' => 'Ahorra dinero y conecta con otras personas'],
            ['clave' => 'hero_btn_buscar', 'valor' => 'Buscar viaje'],
            ['clave' => 'hero_btn_publicar', 'valor' => 'Publicar viaje'],
            ['clave' => 'hero_background', 'valor' => asset('img/fondo.jpg')],
            ['clave' => 'hero_ahorro_texto', 'valor' => 'Ahorra hasta'],
            ['clave' => 'hero_ahorro_sufijo', 'valor' => 'en cada viaje'],
            ['clave' => 'hero_btn_publish_main', 'valor' => 'Publica un viaje'],
            ['clave' => 'hero_como_funciona', 'valor' => '¿Cómo funciona?'],

            ['clave' => 'features_titulo', 'valor' => '¿Por qué elegir VoyConVos?'],
            ['clave' => 'feature_1_titulo', 'valor' => 'Ahorra en cada viaje'],
            ['clave' => 'feature_1_texto', 'valor' => 'Comparte los gastos de gasolina y peajes con otros viajeros'],
            ['clave' => 'feature_2_titulo', 'valor' => 'Conoce nuevas personas'],
            ['clave' => 'feature_2_texto', 'valor' => 'Conecta con gente que comparte tu ruta e intereses'],
            ['clave' => 'feature_3_titulo', 'valor' => 'Cuida el medio ambiente'],
            ['clave' => 'feature_3_texto', 'valor' => 'Reduce la contaminación compartiendo vehículo'],

            ['clave' => 'slogan', 'valor' => 'Conduce. Comparte. Ahorra.'],
            ['clave' => 'slogan_boton', 'valor' => 'Publica un viaje'],
        ];

        foreach ($contenidos as $contenido) {
            Contenido::updateOrCreate(
                ['pagina_id' => $pagina->id, 'clave' => $contenido['clave']],
                ['valor' => $contenido['valor']]
            );
        }
    }
}
