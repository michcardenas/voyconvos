<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        // RolesAndAdminSeeder::class,
        SobreNosotrosSeeder::class,
        ContactoContenidoSeeder::class,
        PreguntasFrecuentesSeeder::class,
        TerminosCondicionesSeeder::class,
        PoliticaPrivacidadSeeder::class, 
       // CalificacionSeeder::class,       
        ComoFuncionaSeeder::class,      
        PaginaInicioSeeder::class,       
    ]);
}

}
