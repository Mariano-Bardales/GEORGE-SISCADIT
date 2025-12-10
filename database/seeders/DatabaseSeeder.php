<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // RolSeeder::class, // Eliminado: La tabla roles no se usa, se usa campo 'role' directamente en users
            UserSeeder::class,
            // ControlesSeeder::class, // Descomentar para importar controles
        ]);
    }
}
