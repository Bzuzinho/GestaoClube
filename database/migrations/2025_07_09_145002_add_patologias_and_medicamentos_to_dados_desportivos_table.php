<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('dados_desportivos', function (Blueprint $table) {
            $table->text('patologias')->nullable();
            $table->text('medicamentos')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('dados_desportivos', function (Blueprint $table) {
            $table->dropColumn('patologias');
            $table->dropColumn('medicamentos');
        });
    }
};
