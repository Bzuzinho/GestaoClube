<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
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
    }
    public function down(): void {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('convocatoria_id');
            $table->dropColumn([
                'tem_transporte','transporte_descricao','local_partida','hora_partida',
                'convocatoria_path','regulamento_path','observacoes'
            ]);
        });
    }
};