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
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('mensalidade_id')->nullable()->after('email');

        $table->foreign('mensalidade_id')
              ->references('id')
              ->on('mensalidades')
              ->onDelete('set null');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['mensalidade_id']);
        $table->dropColumn('mensalidade_id');
    });
}

};
