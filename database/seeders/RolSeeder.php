<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'ADMIN',
                'descripcion' => 'Administrador del sistema con acceso completo',
            ],
            [
                'nombre' => 'JefeDeRed',
                'descripcion' => 'Jefe de Red con acceso a datos de su red',
            ],
            [
                'nombre' => 'CoordinadorDeMicroRed',
                'descripcion' => 'Coordinador de Microred con acceso a datos de su microred',
            ],
        ];

        foreach ($roles as $rol) {
            Rol::create($rol);
        }
    }
}
