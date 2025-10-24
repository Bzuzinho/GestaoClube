<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Dropar o índice antigo se existir
        if ($this->indexExists('faturas', 'faturas_user_competencia_unique')) {
            DB::statement('ALTER TABLE `faturas` DROP INDEX `faturas_user_competencia_unique`');
        }

        // 2) Ajustar colunas
        Schema::table('faturas', function (Blueprint $table) {
            // remover coluna descontinuada se existir
            if (Schema::hasColumn('faturas', 'competencia')) {
                $table->dropColumn('competencia');
            }

            // garantir coluna 'mes'
            if (!Schema::hasColumn('faturas', 'mes')) {
                // recomendo date (1º dia do mês)
                $table->date('mes')->index();
            }
        });

        // 3) Criar índice único correto, se ainda não existir
        if (!$this->indexExists('faturas', 'faturas_user_mes_unique')) {
            DB::statement('ALTER TABLE `faturas` ADD UNIQUE `faturas_user_mes_unique` (`user_id`,`mes`)');
        }
    }

    public function down(): void
    {
        // remover o índice novo se existir
        if ($this->indexExists('faturas', 'faturas_user_mes_unique')) {
            DB::statement('ALTER TABLE `faturas` DROP INDEX `faturas_user_mes_unique`');
        }

        // (opcional) restaurar a coluna/índice antigos
        Schema::table('faturas', function (Blueprint $table) {
            if (!Schema::hasColumn('faturas', 'competencia')) {
                $table->unsignedTinyInteger('competencia')->default(1);
            }
        });

        if (!$this->indexExists('faturas', 'faturas_user_competencia_unique')) {
            DB::statement('ALTER TABLE `faturas` ADD UNIQUE `faturas_user_competencia_unique` (`user_id`,`competencia`)');
        }
    }

    /**
     * Verifica se um índice existe na tabela atual da BD.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $sql = "SELECT 1
                FROM information_schema.statistics
                WHERE table_schema = DATABASE()
                  AND table_name = ?
                  AND index_name = ?
                LIMIT 1";
        return (bool) DB::selectOne($sql, [$table, $indexName]);
    }
};
