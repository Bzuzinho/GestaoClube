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
            Schema::table('dados_financeiros', function (Blueprint $table) {
                $table->foreignId('mensalidade_id')->nullable()->constrained('mensalidades')->onDelete('set null');
            });
        }

      public function down()
            {
                Schema::table('dados_financeiros', function (Blueprint $table) {
                    if (Schema::hasColumn('dados_financeiros', 'mensalidade_id')) {
                        $table->dropForeign(['mensalidade_id']);
                        $table->dropColumn('mensalidade_id');
                    }
                });
            }
};
