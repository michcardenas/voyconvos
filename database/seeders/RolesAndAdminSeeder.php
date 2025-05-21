<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles si no existen
        $roles = ['admin', 'conductor', 'pasajero', 'soporte'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Crear usuario para cada rol
        $usuarios = [
            [
                'name' => 'Administrador',
                'email' => 'admin@voyconvos.com',
                'password' => 'admin123',
                'role' => 'admin',
            ],
            [
                'name' => 'Conductor Prueba',
                'email' => 'conductor@voyconvos.com',
                'password' => 'conductor123',
                'role' => 'conductor',
            ],
            [
                'name' => 'Pasajero Prueba',
                'email' => 'pasajero@voyconvos.com',
                'password' => 'pasajero123',
                'role' => 'pasajero',
            ],
            [
                'name' => 'Soporte Técnico',
                'email' => 'soporte@voyconvos.com',
                'password' => 'soporte123',
                'role' => 'soporte',
            ],
        ];

        foreach ($usuarios as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                ]
            );

            $user->assignRole($data['role']);
        }
    }
}
