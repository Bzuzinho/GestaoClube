<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('catalogo_fatura_itens', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->decimal('valor_unitario', 8, 2)->default(0);
            $table->decimal('imposto_percentual', 5, 2)->nullable(); // ex.: 0, 6, 23
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('catalogo_fatura_itens');
    }
};
