<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fatura_itens', function (Blueprint $table) {
            if (!Schema::hasColumn('fatura_itens', 'quantidade')) {
                $table->unsignedInteger('quantidade')->default(1)->after('valor_unitario');
            }
            if (!Schema::hasColumn('fatura_itens', 'total_linha')) {
                $table->decimal('total_linha', 8, 2)->nullable()->after('quantidade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fatura_itens', function (Blueprint $table) {
            if (Schema::hasColumn('fatura_itens', 'total_linha')) {
                $table->dropColumn('total_linha');
            }
            if (Schema::hasColumn('fatura_itens', 'quantidade')) {
                $table->dropColumn('quantidade');
            }
        });
    }
};
