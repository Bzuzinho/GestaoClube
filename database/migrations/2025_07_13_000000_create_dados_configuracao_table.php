<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dados_configuracao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->boolean('consentimento')->default(false)->nullable();
            $table->date('data_consentimento')->nullable();
            $table->string('ficheiro_consentimento')->nullable();

            $table->boolean('declaracao_transporte')->default(false)->nullable();
            $table->date('data_transporte')->nullable();
            $table->string('ficheiro_transporte')->nullable();

            $table->boolean('afiliacao')->default(false)->nullable();
            $table->date('data_afiliacao')->nullable();
            $table->string('ficheiro_afiliacao')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dados_configuracao');
    }
};
