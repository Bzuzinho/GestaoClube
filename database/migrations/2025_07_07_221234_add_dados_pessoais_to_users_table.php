<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDadosPessoaisToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cartao_cidadao')->nullable()->after('nif');
            $table->string('empresa')->nullable()->after('localidade');
            $table->string('escola')->nullable()->after('empresa');
            $table->string('estado_civil')->nullable()->after('escola');
            $table->string('ocupacao')->nullable()->after('estado_civil');
            $table->string('nacionalidade')->nullable()->after('ocupacao');
            $table->integer('numero_irmaos')->nullable()->after('nacionalidade');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'cartao_cidadao',
                'empresa',
                'escola',
                'estado_civil',
                'ocupacao',
                'nacionalidade',
                'numero_irmaos',
            ]);
        });
    }
}
