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
        Schema::table('presencas', function (Blueprint $table) {
        $table->date('data')->after('user_id');
        $table->tinyInteger('numero_treino')->after('data');
        $table->boolean('presenca')->default(1)->after('numero_treino');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presencas', function (Blueprint $table) {
            //
        });
    }
};
