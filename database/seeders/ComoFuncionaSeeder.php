<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComoFuncionaSeeder extends Seeder
{
    public function run(): void
    {
        // Paso 1: Página (solo usa 'nombre')
        DB::table('paginas')->updateOrInsert(
            ['nombre' => '¿Cómo funciona?'],
            ['updated_at' => now(), 'created_at' => now()]
        );

        $paginaId = DB::table('paginas')->where('nombre', '¿Cómo funciona?')->value('id');

        // Paso 2: Secciones
        DB::table('secciones')->updateOrInsert(
            ['pagina_id' => $paginaId, 'slug' => 'pasos'],
            ['titulo' => 'Cómo usar la plataforma', 'updated_at' => now(), 'created_at' => now()]
        );

        $seccionId = DB::table('secciones')->where('pagina_id', $paginaId)->where('slug', 'pasos')->value('id');

        // Paso 3: Contenidos
        $contenidos = [
            'titulo' => '¿Cómo funciona VoyConVos?',
            'texto' => 'Te explicamos en cuatro pasos simples cómo usar la plataforma.',
            'paso_1_titulo' => 'Regístrate gratis',
            'paso_1_texto' => 'Completa tu perfil en pocos pasos.',
            'paso_2_titulo' => 'Explora viajes',
            'paso_2_texto' => 'Busca rutas disponibles según tu ubicación.',
            'paso_3_titulo' => 'Reserva con confianza',
            'paso_3_texto' => 'Confirma tu asiento y comunícate con el conductor.',
        ];

        foreach ($contenidos as $clave => $valor) {
            DB::table('contenidos')->updateOrInsert(
                ['seccion_id' => $seccionId, 'clave' => $clave],
                ['valor' => $valor, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        $this->command->info('Seeder ComoFunciona ejecutado correctamente 🚀');
    }
}
