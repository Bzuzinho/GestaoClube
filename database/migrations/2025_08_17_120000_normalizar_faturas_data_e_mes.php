<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) data_fatura (DATE) — criar e preencher a partir de 'competencia' (se existir) ou 'created_at'
        if (!Schema::hasColumn('faturas', 'data_fatura')) {
            Schema::table('faturas', function (Blueprint $table) {
                $table->date('data_fatura')->nullable()->after('user_id');
            });

            // se houver 'competencia', copiar; senão usar created_at (ou hoje)
            if (Schema::hasColumn('faturas', 'competencia')) {
                DB::statement("UPDATE faturas SET data_fatura = competencia WHERE data_fatura IS NULL AND competencia IS NOT NULL");
            }
            DB::statement("UPDATE faturas SET data_fatura = DATE(created_at) WHERE data_fatura IS NULL");
        }

        // 2) mes (YYYY-MM) — garantir que está preenchido a partir da data_fatura
        if (!Schema::hasColumn('faturas', 'mes')) {
            Schema::table('faturas', function (Blueprint $table) {
                $table->string('mes', 7)->nullable()->after('data_fatura'); // ex.: 2025-07
            });
        }
        DB::statement("UPDATE faturas SET mes = DATE_FORMAT(data_fatura, '%Y-%m') WHERE (mes IS NULL OR mes = '') AND data_fatura IS NOT NULL");

        // 3) índice único (user_id, mes)
        $ix = DB::selectOne("
            SELECT COUNT(*) AS n FROM INFORMATION_SCHEMA.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'faturas'
              AND INDEX_NAME = 'faturas_user_mes_unique'
        ");
        if (empty($ix) || (int)$ix->n === 0) {
            Schema::table('faturas', function (Blueprint $table) {
                $table->unique(['user_id','mes'], 'faturas_user_mes_unique');
            });
        }

        // 4) (opcional) remover 'competencia' antigo se existir
        if (Schema::hasColumn('faturas', 'competencia')) {
            Schema::table('faturas', function (Blueprint $table) {
                $table->dropColumn('competencia');
            });
        }
    }

    public function down(): void
    {
        // Reverter índice único
        Schema::table('faturas', function (Blueprint $table) {
            $table->dropUnique('faturas_user_mes_unique');
        });

        // Repor 'competencia' (opcional)
        Schema::table('faturas', function (Blueprint $table) {
            $table->date('competencia')->nullable()->after('user_id');
        });
        DB::statement("UPDATE faturas SET competencia = STR_TO_DATE(CONCAT(mes,'-01'), '%Y-%m-%d') WHERE competencia IS NULL AND mes IS NOT NULL");

        // Remover 'data_fatura'
        if (Schema::hasColumn('faturas', 'data_fatura')) {
            Schema::table('faturas', function (Blueprint $table) {
                $table->dropColumn('data_fatura');
            });
        }
    }
};
