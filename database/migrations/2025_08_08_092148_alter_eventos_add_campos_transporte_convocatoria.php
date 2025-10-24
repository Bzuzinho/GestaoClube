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
        Schema::table('eventos', function (Blueprint $table) {
            if (!Schema::hasColumn('eventos', 'convocatoria_id')) {
                $table->unsignedBigInteger('convocatoria_id')->nullable();
            }
            if (!Schema::hasColumn('eventos', 'regulamento_id')) {
                $table->unsignedBigInteger('regulamento_id')->nullable();
            }
            if (!Schema::hasColumn('eventos', 'transporte_disponivel')) {
                $table->boolean('transporte_disponivel')->default(false);
            }
            if (!Schema::hasColumn('eventos', 'local_partida')) {
                $table->string('local_partida')->nullable();
            }
            if (!Schema::hasColumn('eventos', 'hora_partida')) {
                $table->time('hora_partida')->nullable();
            }
            if (!Schema::hasColumn('eventos', 'convocatoria_path')) {
                $table->string('convocatoria_path')->nullable();
            }
            if (!Schema::hasColumn('eventos', 'regulamento_path')) {
                $table->string('regulamento_path')->nullable();
            }
        });

        // Se adicionares FKs aqui, garante que as colunas existem antes,
        // e envolve em try/catch OU verifica previamente se já existem índices.
        // Exemplo (só se necessário):
        // Schema::table('eventos', function (Blueprint $table) {
        //     if (Schema::hasColumn('eventos','convocatoria_id')) {
        //         // $table->foreign('convocatoria_id')->references('id')->on('convocatorias')->nullOnDelete();
        //     }
        // });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            if (Schema::hasColumn('eventos', 'convocatoria_id')) $table->dropColumn('convocatoria_id');
            if (Schema::hasColumn('eventos', 'regulamento_id')) $table->dropColumn('regulamento_id');
            if (Schema::hasColumn('eventos', 'transporte_disponivel')) $table->dropColumn('transporte_disponivel');
            if (Schema::hasColumn('eventos', 'local_partida')) $table->dropColumn('local_partida');
            if (Schema::hasColumn('eventos', 'hora_partida')) $table->dropColumn('hora_partida');
            if (Schema::hasColumn('eventos', 'convocatoria_path')) $table->dropColumn('convocatoria_path');
            if (Schema::hasColumn('eventos', 'regulamento_path')) $table->dropColumn('regulamento_path');
        });
    }
};
