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
    if (!Schema::hasTable('evento_escalao')) {
        Schema::create('evento_escalao', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evento_id');
            $table->unsignedBigInteger('escalao_id');
            $table->timestamps();
        });

        // (Opcional) se esta migration também definir FKs, faz as adds aqui
        // Schema::table('evento_escalao', function (Blueprint $table) {
        //     $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('cascade');
        //     $table->foreign('escalao_id')->references('id')->on('escaloes')->onDelete('cascade');
        // });
    }
}

public function down()
{
    Schema::dropIfExists('evento_escalao');
}
};
