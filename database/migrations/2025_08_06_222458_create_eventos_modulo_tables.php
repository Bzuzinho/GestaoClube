<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // As tabelas eventos_tipos e eventos já existem, não recriar

        if (!Schema::hasTable('eventos_users')) {
            Schema::create('eventos_users', function (Blueprint $table) {
                $table->id();
                $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->boolean('convocado')->default(false);
                $table->boolean('presenca_confirmada')->nullable();
                $table->text('justificacao')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_users');
        // Não eliminar eventos ou eventos_tipos porque já existem noutras migrations
    }
};

