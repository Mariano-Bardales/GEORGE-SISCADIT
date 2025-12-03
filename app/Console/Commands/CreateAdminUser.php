<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin 
                            {--name= : Nombre del usuario}
                            {--email= : Email del usuario}
                            {--password= : Contraseña del usuario}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear un nuevo usuario administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Crear Usuario Administrador');
        $this->info('================================');

        // Obtener datos del usuario
        $name = $this->option('name') ?: $this->ask('Nombre del usuario');
        $email = $this->option('email') ?: $this->ask('Email del usuario');
        $password = $this->option('password') ?: $this->secret('Contraseña del usuario');

        // Validar datos
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            $this->error('❌ Errores de validación:');
            foreach ($validator->errors()->all() as $error) {
                $this->error("  - {$error}");
            }
            return 1;
        }

        // Verificar si el email ya existe
        if (User::where('email', $email)->exists()) {
            $this->error("❌ El usuario con email '{$email}' ya existe.");
            return 1;
        }

        // Crear el usuario
        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);

            $this->info("\n✅ Usuario administrador creado exitosamente!");
            $this->table(
                ['Campo', 'Valor'],
                [
                    ['ID', $user->id],
                    ['Nombre', $user->name],
                    ['Email', $user->email],
                    ['Rol', $user->role],
                    ['Email Verificado', $user->email_verified_at ? 'Sí' : 'No'],
                ]
            );

            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Error al crear el usuario: " . $e->getMessage());
            return 1;
        }
    }
}

