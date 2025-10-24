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
        Schema::table('fatura_itens', function (Blueprint $table) {
            $table->foreignId('dados_financeiros_id')->nullable()->constrained('dados_financeiros')->onDelete('cascade');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fatura_itens', function (Blueprint $table) {
            //
        });
    }
};
