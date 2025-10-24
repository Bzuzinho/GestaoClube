<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('eventos_tipos', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('cor')->nullable();
        $table->string('icon')->nullable();
        $table->timestamps();
    });

    Schema::create('eventos', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->text('descricao')->nullable();
        $table->datetime('data_inicio');
        $table->datetime('data_fim');
        $table->string('local')->nullable();
        $table->foreignId('tipo_evento_id')->constrained('eventos_tipos');
        $table->enum('visibilidade', ['privado', 'restrito', 'publico'])->default('restrito');

        $table->boolean('transporte_disponivel')->default(false);
        $table->string('local_partida')->nullable();
        $table->time('hora_partida')->nullable();

        $table->string('convocatoria_path')->nullable();
        $table->string('regulamento_path')->nullable();

        $table->timestamps();
    });

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

    public function down(): void
    {
        Schema::dropIfExists('eventos_users');
        Schema::dropIfExists('eventos_tipos');
        Schema::dropIfExists('eventos');
    }
};

