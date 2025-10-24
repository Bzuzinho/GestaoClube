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
        if (!Schema::hasTable('convocatorias')) {
            Schema::create('convocatorias', function (Blueprint $table) {
                $table->id();
                $table->string('titulo');
                $table->date('data')->nullable();
                $table->string('ficheiro_path')->nullable();
                $table->timestamps();
            });
        } else {
            // (Opcional) Se precisares garantir colunas em ambientes antigos:
            Schema::table('convocatorias', function (Blueprint $table) {
                if (!Schema::hasColumn('convocatorias', 'titulo')) {
                    $table->string('titulo');
                }
                if (!Schema::hasColumn('convocatorias', 'data')) {
                    $table->date('data')->nullable();
                }
                if (!Schema::hasColumn('convocatorias', 'ficheiro_path')) {
                    $table->string('ficheiro_path')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convocatorias');
    }
};
