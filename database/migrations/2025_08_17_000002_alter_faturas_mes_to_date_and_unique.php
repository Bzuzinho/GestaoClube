<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1) Adicionar coluna nova (DATE) para a competência
        if (!Schema::hasColumn('faturas', 'competencia')) {
            Schema::table('faturas', function (Blueprint $table) {
                $table->date('competencia')->nullable()->after('user_id');
            });
        }

        // 2) Backfill da competência com base no campo antigo 'mes' (formato 'YYYY-MM')
        // Se já tiveres valores, isto preenche 1º dia do mês.
        DB::statement("
            UPDATE faturas
            SET competencia = STR_TO_DATE(CONCAT(mes, '-01'), '%Y-%m-%d')
            WHERE competencia IS NULL AND mes IS NOT NULL AND mes <> ''
        ");

        // 3) Se existir um índice único antigo, tentamos removê-lo de forma segura
        // (neste dump não existe; protegemos para ambientes onde possa existir)
        $exists = DB::selectOne("
            SELECT COUNT(1) AS n
            FROM INFORMATION_SCHEMA.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'faturas'
              AND INDEX_NAME = 'faturas_user_id_mes_unique'
        ");
        if (!empty($exists) && (int)$exists->n > 0) {
            Schema::table('faturas', function (Blueprint $table) {
                $table->dropUnique('faturas_user_id_mes_unique');
            });
        }

        // 4) Criar o novo UNIQUE (user_id, competencia) com nome claro
        // Evita colisões com nomes existentes.
        $existsNew = DB::selectOne("
            SELECT COUNT(1) AS n
            FROM INFORMATION_SCHEMA.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'faturas'
              AND INDEX_NAME = 'faturas_user_competencia_unique'
        ");
        if (empty($existsNew) || (int)$existsNew->n === 0) {
            Schema::table('faturas', function (Blueprint $table) {
                $table->unique(['user_id', 'competencia'], 'faturas_user_competencia_unique');
            });
        }

        // 5) (Opcional – fase 2) Trocar 'mes' varchar -> eliminar e renomear 'competencia' para 'mes'
        // NOTA: renomear colunas em alguns setups requer doctrine/dbal.
        // Para evitar bloquear a migration agora, mantemos os dois campos.
        // Quando o código já estiver a usar 'competencia', criamos outra migration só para remover 'mes' antigo.
    }

    public function down(): void
    {
        // Reverter o UNIQUE novo
        $existsNew = DB::selectOne("
            SELECT COUNT(1) AS n
            FROM INFORMATION_SCHEMA.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'faturas'
              AND INDEX_NAME = 'faturas_user_competencia_unique'
        ");
        if (!empty($existsNew) && (int)$existsNew->n > 0) {
            Schema::table('faturas', function (Blueprint $table) {
                $table->dropUnique('faturas_user_competencia_unique');
            });
        }

        // Remover a coluna 'competencia' (só se existir)
        if (Schema::hasColumn('faturas', 'competencia')) {
            Schema::table('faturas', function (Blueprint $table) {
                $table->dropColumn('competencia');
            });
        }
    }
};
