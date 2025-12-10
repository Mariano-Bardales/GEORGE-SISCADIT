<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar soft deletes a ninos
        if (Schema::hasTable('ninos') && !Schema::hasColumn('ninos', 'deleted_at')) {
            Schema::table('ninos', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Agregar soft deletes a madres
        if (Schema::hasTable('madres') && !Schema::hasColumn('madres', 'deleted_at')) {
            Schema::table('madres', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Agregar soft deletes a control_rns
        if (Schema::hasTable('control_rns') && !Schema::hasColumn('control_rns', 'deleted_at')) {
            Schema::table('control_rns', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Agregar soft deletes a control_menor1s
        if (Schema::hasTable('control_menor1s') && !Schema::hasColumn('control_menor1s', 'deleted_at')) {
            Schema::table('control_menor1s', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Agregar soft deletes a solicitudes
        if (Schema::hasTable('solicitudes') && !Schema::hasColumn('solicitudes', 'deleted_at')) {
            Schema::table('solicitudes', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Agregar soft deletes a users (excepto admin principal)
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Agregar soft deletes a datos_extras
        if (Schema::hasTable('datos_extras') && !Schema::hasColumn('datos_extras', 'deleted_at')) {
            Schema::table('datos_extras', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Agregar soft deletes a visita_domiciliarias
        if (Schema::hasTable('visita_domiciliarias') && !Schema::hasColumn('visita_domiciliarias', 'deleted_at')) {
            Schema::table('visita_domiciliarias', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar soft deletes de ninos
        if (Schema::hasTable('ninos') && Schema::hasColumn('ninos', 'deleted_at')) {
            Schema::table('ninos', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        // Eliminar soft deletes de madres
        if (Schema::hasTable('madres') && Schema::hasColumn('madres', 'deleted_at')) {
            Schema::table('madres', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        // Eliminar soft deletes de control_rns
        if (Schema::hasTable('control_rns') && Schema::hasColumn('control_rns', 'deleted_at')) {
            Schema::table('control_rns', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        // Eliminar soft deletes de control_menor1s
        if (Schema::hasTable('control_menor1s') && Schema::hasColumn('control_menor1s', 'deleted_at')) {
            Schema::table('control_menor1s', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        // Eliminar soft deletes de solicitudes
        if (Schema::hasTable('solicitudes') && Schema::hasColumn('solicitudes', 'deleted_at')) {
            Schema::table('solicitudes', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        // Eliminar soft deletes de users
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        // Eliminar soft deletes de datos_extras
        if (Schema::hasTable('datos_extras') && Schema::hasColumn('datos_extras', 'deleted_at')) {
            Schema::table('datos_extras', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        // Eliminar soft deletes de visita_domiciliarias
        if (Schema::hasTable('visita_domiciliarias') && Schema::hasColumn('visita_domiciliarias', 'deleted_at')) {
            Schema::table('visita_domiciliarias', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
