<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fatura_itens', function (Blueprint $table) {
            // DECIMAL para valores monetários e quantidades fraccionadas se precisares (ex.: 1.50)
            $table->decimal('quantidade', 10, 2)->default(1.00)->after('valor_unitario');
            $table->decimal('total_linha', 10, 2)->nullable()->after('imposto_percentual');
        });

        // Backfill do total_linha com base nos campos já existentes
        // total_linha = quantidade * valor_unitario * (1 + imposto_percentual/100)
        DB::statement("
            UPDATE fatura_itens
            SET total_linha = ROUND(quantidade * valor_unitario * (1 + (imposto_percentual/100)), 2)
        ");
    }

    public function down(): void
    {
        Schema::table('fatura_itens', function (Blueprint $table) {
            $table->dropColumn(['quantidade', 'total_linha']);
        });
    }
};
