<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario DIRESA (Administrador)
        $adminExists = User::where('email', 'diresa@siscadit.com')->exists();
        if (!$adminExists) {
            User::create([
                'name' => 'Administrador DIRESA',
                'email' => 'diresa@siscadit.com',
                'password' => Hash::make('diresa123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            $this->command->info('✓ Usuario DIRESA creado exitosamente!');
            $this->command->info('  Email: diresa@siscadit.com');
            $this->command->info('  Password: diresa123');
        } else {
            $this->command->warn('El usuario DIRESA ya existe.');
        }

        // Usuario Jefe de Red
        $jefeRedExists = User::where('email', 'jefedered@siscadit.com')->exists();
        if (!$jefeRedExists) {
            User::create([
                'name' => 'Jefe de Red',
                'email' => 'jefedered@siscadit.com',
                'password' => Hash::make('jefedered123'),
                'role' => 'jefe_red',
                'email_verified_at' => now(),
            ]);
            $this->command->info('✓ Usuario Jefe de Red creado exitosamente!');
            $this->command->info('  Email: jefedered@siscadit.com');
            $this->command->info('  Password: jefedered123');
        } else {
            $this->command->warn('El usuario Jefe de Red ya existe.');
        }

        // Usuario Coordinador de Microred
        $coordinadorExists = User::where('email', 'coordinador@siscadit.com')->exists();
        if (!$coordinadorExists) {
            User::create([
                'name' => 'Coordinador de Microred',
                'email' => 'coordinador@siscadit.com',
                'password' => Hash::make('coordinador123'),
                'role' => 'coordinador_microred',
                'email_verified_at' => now(),
            ]);
            $this->command->info('✓ Usuario Coordinador de Microred creado exitosamente!');
            $this->command->info('  Email: coordinador@siscadit.com');
            $this->command->info('  Password: coordinador123');
        } else {
            $this->command->warn('El usuario Coordinador de Microred ya existe.');
        }

        // Mantener el usuario admin original si no existe el de DIRESA
        $adminOriginalExists = User::where('email', 'admin@siscadit.com')->exists();
        if (!$adminOriginalExists && !$adminExists) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@siscadit.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            $this->command->info('✓ Usuario administrador (admin) creado exitosamente!');
            $this->command->info('  Email: admin@siscadit.com');
            $this->command->info('  Password: admin123');
        }
    }
}
