<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Elimina los campos created_at y updated_at de todas las tablas.
     */
    public function up(): void
    {
        // Eliminar timestamps de ninos
        if (Schema::hasTable('ninos')) {
            Schema::table('ninos', function (Blueprint $table) {
                if (Schema::hasColumn('ninos', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('ninos', 'updated_at')) {
                    $table->dropColumn('updated_at');
                }
            });
        }

        // Eliminar timestamps de madres
        if (Schema::hasTable('madres')) {
            Schema::table('madres', function (Blueprint $table) {
                if (Schema::hasColumn('madres', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('madres', 'updated_at')) {
                    $table->dropColumn('updated_at');
                }
            });
        }

        // Eliminar timestamps de users
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('users', 'updated_at')) {
                    $table->dropColumn('updated_at');
                }
            });
        }

        // Eliminar timestamps de solicitudes
        if (Schema::hasTable('solicitudes')) {
            Schema::table('solicitudes', function (Blueprint $table) {
                if (Schema::hasColumn('solicitudes', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('solicitudes', 'updated_at')) {
                    $table->dropColumn('updated_at');
                }
            });
        }

        // Eliminar timestamps de datos_extras
        if (Schema::hasTable('datos_extras')) {
            Schema::table('datos_extras', function (Blueprint $table) {
                if (Schema::hasColumn('datos_extras', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('datos_extras', 'updated_at')) {
                    $table->dropColumn('updated_at');
                }
            });
        }

        // Eliminar timestamps de recien_nacidos
        if (Schema::hasTable('recien_nacidos')) {
            Schema::table('recien_nacidos', function (Blueprint $table) {
                if (Schema::hasColumn('recien_nacidos', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('recien_nacidos', 'updated_at')) {
                    $table->dropColumn('updated_at');
                }
            });
        }

        // Eliminar timestamps de tamizaje_neonatals
        if (Schema::hasTable('tamizaje_neonatals')) {
            Schema::table('tamizaje_neonatals', function (Blueprint $table) {
                if (Schema::hasColumn('tamizaje_neonatals', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('tamizaje_neonatals', 'updated_at')) {
                    $table->dropColumn('updated_at');
                }
            });
        }

        // Eliminar timestamps de vacuna_rns
        if (Schema::hasTable('vacuna_rns')) {
            Schema::table('vacuna_rns', function (Blueprint $table) {
                if (Schema::hasColumn('vacuna_rns', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('vacuna_rns', 'updated_at')) {
                    $table->dropColumn('updated_at');
                }
            });
        }

        // Eliminar timestamps de visita_domiciliarias
        if (Schema::hasTable('visita_domiciliarias')) {
            Schema::table('visita_domiciliarias', function (Blueprint $table) {
                if (Schema::hasColumn('visita_domiciliarias', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('visita_domiciliarias', 'updated_at')) {
                    $table->dropColumn('updated_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar timestamps en ninos
        if (Schema::hasTable('ninos')) {
            Schema::table('ninos', function (Blueprint $table) {
                if (!Schema::hasColumn('ninos', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('ninos', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }

        // Restaurar timestamps en madres
        if (Schema::hasTable('madres')) {
            Schema::table('madres', function (Blueprint $table) {
                if (!Schema::hasColumn('madres', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('madres', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }

        // Restaurar timestamps en users
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('users', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }

        // Restaurar timestamps en solicitudes
        if (Schema::hasTable('solicitudes')) {
            Schema::table('solicitudes', function (Blueprint $table) {
                if (!Schema::hasColumn('solicitudes', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('solicitudes', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }

        // Restaurar timestamps en datos_extras
        if (Schema::hasTable('datos_extras')) {
            Schema::table('datos_extras', function (Blueprint $table) {
                if (!Schema::hasColumn('datos_extras', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('datos_extras', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }

        // Restaurar timestamps en recien_nacidos
        if (Schema::hasTable('recien_nacidos')) {
            Schema::table('recien_nacidos', function (Blueprint $table) {
                if (!Schema::hasColumn('recien_nacidos', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('recien_nacidos', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }

        // Restaurar timestamps en tamizaje_neonatals
        if (Schema::hasTable('tamizaje_neonatals')) {
            Schema::table('tamizaje_neonatals', function (Blueprint $table) {
                if (!Schema::hasColumn('tamizaje_neonatals', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('tamizaje_neonatals', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }

        // Restaurar timestamps en vacuna_rns
        if (Schema::hasTable('vacuna_rns')) {
            Schema::table('vacuna_rns', function (Blueprint $table) {
                if (!Schema::hasColumn('vacuna_rns', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('vacuna_rns', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }

        // Restaurar timestamps en visita_domiciliarias
        if (Schema::hasTable('visita_domiciliarias')) {
            Schema::table('visita_domiciliarias', function (Blueprint $table) {
                if (!Schema::hasColumn('visita_domiciliarias', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('visita_domiciliarias', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }
    }
};
