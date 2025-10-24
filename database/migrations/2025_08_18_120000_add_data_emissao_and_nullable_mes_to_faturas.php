<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('faturas', function (Blueprint $table) {
            if (!Schema::hasColumn('faturas', 'data_emissao')) {
                $table->dateTime('data_emissao')->nullable()->after('mes');
            }
        });

        // tornar 'mes' opcional e mais coerente (YYYY-MM = 7 chars)
        DB::statement("ALTER TABLE `faturas` MODIFY `mes` VARCHAR(7) NULL");

        // segurança extra: valor com default 0 (se ainda não aplicaste)
        DB::statement("UPDATE `faturas` SET `valor` = 0 WHERE `valor` IS NULL");
        DB::statement("ALTER TABLE `faturas` MODIFY `valor` DECIMAL(10,2) NOT NULL DEFAULT 0");
    }

    public function down(): void
    {
        // reverter só o que é seguro
        DB::statement("ALTER TABLE `faturas` MODIFY `mes` VARCHAR(7) NOT NULL");
        Schema::table('faturas', function (Blueprint $table) {
            if (Schema::hasColumn('faturas', 'data_emissao')) {
                $table->dropColumn('data_emissao');
            }
        });
    }
};
