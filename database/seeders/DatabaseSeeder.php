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
            RolesAndAdminSeeder::class,
        ]);
        $this->call(SobreNosotrosSeeder::class);
        $this->call(ContactoContenidoSeeder::class);
        $this->call(PreguntasFrecuentesSeeder::class);
        $this->call(TerminosCondicionesSeeder::class);
        $this->call(PoliticasDePrivacidadSeeder::class);

    }
}
