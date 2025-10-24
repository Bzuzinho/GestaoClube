<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Corrigir registos existentes
        DB::statement("UPDATE `faturas` SET `valor` = 0 WHERE `valor` IS NULL");

        // Impor NOT NULL + DEFAULT 0 (sem Doctrine)
        DB::statement("ALTER TABLE `faturas` MODIFY `valor` DECIMAL(10,2) NOT NULL DEFAULT 0");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `faturas` MODIFY `valor` DECIMAL(10,2) NULL");
    }
};
