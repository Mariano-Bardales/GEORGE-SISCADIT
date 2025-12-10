<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Elimina campos de edad redundantes que se calculan dinámicamente
     * usando fecha_nacimiento y fecha del control mediante EdadService.
     */
    public function up(): void
    {
        // Eliminar edad_meses y edad_dias de ninos
        // Se calculan dinámicamente con EdadService::calcularEdadEnDias() y calcularEdadEnMeses()
        if (Schema::hasTable('ninos')) {
            Schema::table('ninos', function (Blueprint $table) {
                if (Schema::hasColumn('ninos', 'edad_meses')) {
                    $table->dropColumn('edad_meses');
                }
                if (Schema::hasColumn('ninos', 'edad_dias')) {
                    $table->dropColumn('edad_dias');
                }
            });
        }

        // Eliminar edad de control_rns
        // Se calcula desde fecha_nacimiento del niño y fecha del control
        if (Schema::hasTable('control_rns')) {
            Schema::table('control_rns', function (Blueprint $table) {
                if (Schema::hasColumn('control_rns', 'edad')) {
                    $table->dropColumn('edad');
                }
            });
        }

        // Eliminar edad de control_menor1s
        // Se calcula desde fecha_nacimiento del niño y fecha del control
        if (Schema::hasTable('control_menor1s')) {
            Schema::table('control_menor1s', function (Blueprint $table) {
                if (Schema::hasColumn('control_menor1s', 'edad')) {
                    $table->dropColumn('edad');
                }
            });
        }

        // Eliminar edad_bcg y edad_hvb de vacuna_rns
        // Se calculan desde fecha_nacimiento y fecha_bcg/fecha_hvb
        if (Schema::hasTable('vacuna_rns')) {
            Schema::table('vacuna_rns', function (Blueprint $table) {
                if (Schema::hasColumn('vacuna_rns', 'edad_bcg')) {
                    $table->dropColumn('edad_bcg');
                }
                if (Schema::hasColumn('vacuna_rns', 'edad_hvb')) {
                    $table->dropColumn('edad_hvb');
                }
            });
        }

        // Eliminar edad_tam_neo y galen_dias_tam_feo de tamizaje_neonatals
        // Se calculan desde fecha_nacimiento y fecha_tam_neo/galen_fecha_tam_feo
        if (Schema::hasTable('tamizaje_neonatals')) {
            Schema::table('tamizaje_neonatals', function (Blueprint $table) {
                if (Schema::hasColumn('tamizaje_neonatals', 'edad_tam_neo')) {
                    $table->dropColumn('edad_tam_neo');
                }
                if (Schema::hasColumn('tamizaje_neonatals', 'galen_dias_tam_feo')) {
                    $table->dropColumn('galen_dias_tam_feo');
                }
            });
        }

        // NOTA: edad_gestacional en recien_nacidos NO se elimina porque
        // es la edad gestacional al nacer (dato médico), no la edad del niño
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar campos eliminados (por si se necesita rollback)
        
        if (Schema::hasTable('ninos')) {
            Schema::table('ninos', function (Blueprint $table) {
                if (!Schema::hasColumn('ninos', 'edad_meses')) {
                    $table->integer('edad_meses')->nullable()->after('genero');
                }
                if (!Schema::hasColumn('ninos', 'edad_dias')) {
                    $table->integer('edad_dias')->nullable()->after('edad_meses');
                }
            });
        }

        if (Schema::hasTable('control_rns')) {
            Schema::table('control_rns', function (Blueprint $table) {
                if (!Schema::hasColumn('control_rns', 'edad')) {
                    $table->integer('edad')->nullable()->after('fecha');
                }
            });
        }

        if (Schema::hasTable('control_menor1s')) {
            Schema::table('control_menor1s', function (Blueprint $table) {
                if (!Schema::hasColumn('control_menor1s', 'edad')) {
                    $table->integer('edad')->nullable()->after('fecha');
                }
            });
        }

        if (Schema::hasTable('vacuna_rns')) {
            Schema::table('vacuna_rns', function (Blueprint $table) {
                if (!Schema::hasColumn('vacuna_rns', 'edad_bcg')) {
                    $table->integer('edad_bcg')->nullable()->after('fecha_bcg');
                }
                if (!Schema::hasColumn('vacuna_rns', 'edad_hvb')) {
                    $table->integer('edad_hvb')->nullable()->after('fecha_hvb');
                }
            });
        }

        if (Schema::hasTable('tamizaje_neonatals')) {
            Schema::table('tamizaje_neonatals', function (Blueprint $table) {
                if (!Schema::hasColumn('tamizaje_neonatals', 'edad_tam_neo')) {
                    $table->integer('edad_tam_neo')->nullable()->after('fecha_tam_neo');
                }
                if (!Schema::hasColumn('tamizaje_neonatals', 'galen_dias_tam_feo')) {
                    $table->integer('galen_dias_tam_feo')->nullable()->after('galen_fecha_tam_feo');
                }
            });
        }
    }
};


