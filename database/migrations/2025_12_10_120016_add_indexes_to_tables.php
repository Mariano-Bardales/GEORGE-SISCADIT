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
        // Índices para tabla ninos
        if (Schema::hasTable('ninos')) {
            Schema::table('ninos', function (Blueprint $table) {
                if (!$this->hasIndex('ninos', 'ninos_id_madre_index')) {
                    $table->index('id_madre', 'ninos_id_madre_index');
                }
                if (!$this->hasIndex('ninos', 'ninos_fecha_nacimiento_index')) {
                    $table->index('fecha_nacimiento', 'ninos_fecha_nacimiento_index');
                }
                if (!$this->hasIndex('ninos', 'ninos_numero_doc_index')) {
                    $table->index('numero_doc', 'ninos_numero_doc_index');
                }
                if (!$this->hasIndex('ninos', 'ninos_apellidos_nombres_index')) {
                    $table->index('apellidos_nombres', 'ninos_apellidos_nombres_index');
                }
            });
        }

        // Índices para tabla datos_extras
        if (Schema::hasTable('datos_extras')) {
            Schema::table('datos_extras', function (Blueprint $table) {
                if (!$this->hasIndex('datos_extras', 'datos_extras_id_niño_index')) {
                    $table->index('id_niño', 'datos_extras_id_niño_index');
                }
                if (!$this->hasIndex('datos_extras', 'datos_extras_red_index')) {
                    $table->index('red', 'datos_extras_red_index');
                }
                if (!$this->hasIndex('datos_extras', 'datos_extras_microred_index')) {
                    $table->index('microred', 'datos_extras_microred_index');
                }
                if (!$this->hasIndex('datos_extras', 'datos_extras_distrito_index')) {
                    $table->index('distrito', 'datos_extras_distrito_index');
                }
            });
        }

        // Índices para tabla control_rns
        if (Schema::hasTable('control_rns')) {
            Schema::table('control_rns', function (Blueprint $table) {
                if (!$this->hasIndex('control_rns', 'control_rns_id_niño_index')) {
                    $table->index('id_niño', 'control_rns_id_niño_index');
                }
                if (!$this->hasIndex('control_rns', 'control_rns_fecha_index')) {
                    $table->index('fecha', 'control_rns_fecha_index');
                }
                if (!$this->hasIndex('control_rns', 'control_rns_id_niño_numero_control_index')) {
                    $table->index(['id_niño', 'numero_control'], 'control_rns_id_niño_numero_control_index');
                }
            });
        }

        // Índices para tabla control_menor1s
        if (Schema::hasTable('control_menor1s')) {
            Schema::table('control_menor1s', function (Blueprint $table) {
                if (!$this->hasIndex('control_menor1s', 'control_menor1s_id_niño_index')) {
                    $table->index('id_niño', 'control_menor1s_id_niño_index');
                }
                if (!$this->hasIndex('control_menor1s', 'control_menor1s_fecha_index')) {
                    $table->index('fecha', 'control_menor1s_fecha_index');
                }
                if (!$this->hasIndex('control_menor1s', 'control_menor1s_id_niño_numero_control_index')) {
                    $table->index(['id_niño', 'numero_control'], 'control_menor1s_id_niño_numero_control_index');
                }
            });
        }

        // Índices para tabla madres
        if (Schema::hasTable('madres')) {
            Schema::table('madres', function (Blueprint $table) {
                if (!$this->hasIndex('madres', 'madres_id_niño_index')) {
                    $table->index('id_niño', 'madres_id_niño_index');
                }
                if (!$this->hasIndex('madres', 'madres_dni_index')) {
                    $table->index('dni', 'madres_dni_index');
                }
            });
        }

        // Índices para tabla visitas_domiciliarias
        if (Schema::hasTable('visita_domiciliarias')) {
            Schema::table('visita_domiciliarias', function (Blueprint $table) {
                if (!$this->hasIndex('visita_domiciliarias', 'visita_domiciliarias_id_niño_index')) {
                    $table->index('id_niño', 'visita_domiciliarias_id_niño_index');
                }
                if (!$this->hasIndex('visita_domiciliarias', 'visita_domiciliarias_fecha_visita_index')) {
                    $table->index('fecha_visita', 'visita_domiciliarias_fecha_visita_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar índices de ninos
        if (Schema::hasTable('ninos')) {
            Schema::table('ninos', function (Blueprint $table) {
                $table->dropIndex('ninos_id_madre_index');
                $table->dropIndex('ninos_fecha_nacimiento_index');
                $table->dropIndex('ninos_numero_doc_index');
                $table->dropIndex('ninos_apellidos_nombres_index');
            });
        }

        // Eliminar índices de datos_extras
        if (Schema::hasTable('datos_extras')) {
            Schema::table('datos_extras', function (Blueprint $table) {
                $table->dropIndex('datos_extras_id_niño_index');
                $table->dropIndex('datos_extras_red_index');
                $table->dropIndex('datos_extras_microred_index');
                $table->dropIndex('datos_extras_distrito_index');
            });
        }

        // Eliminar índices de control_rns
        if (Schema::hasTable('control_rns')) {
            Schema::table('control_rns', function (Blueprint $table) {
                $table->dropIndex('control_rns_id_niño_index');
                $table->dropIndex('control_rns_fecha_index');
                $table->dropIndex('control_rns_id_niño_numero_control_index');
            });
        }

        // Eliminar índices de control_menor1s
        if (Schema::hasTable('control_menor1s')) {
            Schema::table('control_menor1s', function (Blueprint $table) {
                $table->dropIndex('control_menor1s_id_niño_index');
                $table->dropIndex('control_menor1s_fecha_index');
                $table->dropIndex('control_menor1s_id_niño_numero_control_index');
            });
        }

        // Eliminar índices de madres
        if (Schema::hasTable('madres')) {
            Schema::table('madres', function (Blueprint $table) {
                $table->dropIndex('madres_id_niño_index');
                $table->dropIndex('madres_dni_index');
            });
        }

        // Eliminar índices de visita_domiciliarias
        if (Schema::hasTable('visita_domiciliarias')) {
            Schema::table('visita_domiciliarias', function (Blueprint $table) {
                $table->dropIndex('visita_domiciliarias_id_niño_index');
                $table->dropIndex('visita_domiciliarias_fecha_visita_index');
            });
        }
    }

    /**
     * Verificar si un índice existe en una tabla
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();
        
        $result = $connection->select(
            "SELECT COUNT(*) as count 
             FROM information_schema.statistics 
             WHERE table_schema = ? 
             AND table_name = ? 
             AND index_name = ?",
            [$database, $table, $indexName]
        );
        
        return $result[0]->count > 0;
    }
};
