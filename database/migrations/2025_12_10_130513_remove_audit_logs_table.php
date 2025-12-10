<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Elimina la tabla audit_logs ya que no se está usando.
     * No hay interfaz para ver los logs ni se consultan en ningún lugar.
     */
    public function up(): void
    {
        if (Schema::hasTable('audit_logs')) {
            Schema::dropIfExists('audit_logs');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->string('action', 50);
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
                
                $table->index(['model_type', 'model_id'], 'audit_logs_model_index');
                $table->index('user_id', 'audit_logs_user_id_index');
                $table->index('action', 'audit_logs_action_index');
                $table->index('created_at', 'audit_logs_created_at_index');
            });
        }
    }
};
