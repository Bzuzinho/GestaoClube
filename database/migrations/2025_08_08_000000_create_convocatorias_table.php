<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('convocatorias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->date('data')->nullable();
            $table->string('ficheiro_path')->nullable(); // se quiseres anexar o PDF da convocatória
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('convocatorias');
    }
};
