<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Renombrar id_nino a id_niño usando DB raw para manejar el carácter especial
        if (Schema::hasColumn('madres', 'id_nino') && !Schema::hasColumn('madres', 'id_niño')) {
            DB::statement('ALTER TABLE madres CHANGE COLUMN id_nino id_niño bigint(20) unsigned NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('madres', 'id_niño')) {
            DB::statement('ALTER TABLE madres CHANGE COLUMN id_niño id_nino bigint(20) unsigned NULL');
        }
    }
};




