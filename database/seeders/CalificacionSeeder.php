<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CalificacionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('calificacions')->insert([
            [
                'reserva_id'   => 1,
                'usuario_id'   => 13,
                'calificacion' => 5,
                'comentario'   => '¡Excelente conductor, muy puntual y amable!',
                'tipo'         => 'pasajero_a_conductor',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'reserva_id'   => 2,
                'usuario_id'   => 11,
                'calificacion' => 4,
                'comentario'   => 'Buen pasajero, pero llegó un poco tarde.',
                'tipo'         => 'conductor_a_pasajero',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
        ]);
    }
}
