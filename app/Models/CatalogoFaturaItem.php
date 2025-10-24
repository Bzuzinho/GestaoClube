<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogoFaturaItem extends Model
{
    protected $table = 'catalogo_fatura_itens';   // nome da tabela de catálogo
    protected $fillable = ['descricao','valor_unitario','imposto_percentual'];
    public $timestamps = true;
}
