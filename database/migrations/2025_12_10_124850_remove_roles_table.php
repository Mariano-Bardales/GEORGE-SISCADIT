<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Elimina la tabla roles ya que no se está usando.
     * El sistema usa directamente el campo 'role' (string) en la tabla users.
     */
    public function up(): void
    {
        // Verificar que la tabla existe antes de eliminarla
        if (Schema::hasTable('roles')) {
            Schema::dropIfExists('roles');
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Recrea la tabla roles por si se necesita hacer rollback.
     */
    public function down(): void
    {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 50)->unique();
                $table->string('descripcion')->nullable();
                $table->timestamps();
            });

            // Recrear datos originales
            DB::table('roles')->insert([
                [
                    'nombre' => 'ADMIN',
                    'descripcion' => 'Administrador del sistema con acceso completo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nombre' => 'JefeDeRed',
                    'descripcion' => 'Jefe de Red con acceso a datos de su red',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nombre' => 'CoordinadorDeMicroRed',
                    'descripcion' => 'Coordinador de Microred con acceso a datos de su microred',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
};
