<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dados_desportivos', function (Blueprint $table) {
            $table->string('numero_federacao')->nullable()->after('medicamentos');
            $table->string('pmb')->nullable()->after('numero_federacao');
            $table->date('data_inscricao')->nullable()->after('pmb');
            $table->boolean('atestado_medico')->default(false)->after('data_inscricao');
            $table->date('data_atestado')->nullable()->after('atestado_medico');
            $table->text('informacoes_medicas')->nullable()->after('data_atestado');
            $table->string('arquivo_am_path')->nullable()->after('informacoes_medicas');
        });
    }

    public function down(): void
    {
        Schema::table('dados_desportivos', function (Blueprint $table) {
            $table->dropColumn([
                'numero_federacao',
                'pmb',
                'data_inscricao',
                'atestado_medico',
                'data_atestado',
                'informacoes_medicas',
                'arquivo_am_path',
            ]);
        });
    }
};
