<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CatalogoFaturaItem;

class CatalogoFaturaItensSeeder extends Seeder
{
    public function run(): void
    {
        $itens = [
            ['descricao'=>'Inscrição', 'valor_unitario'=>15, 'imposto_percentual'=>0],
            ['descricao'=>'Touca',     'valor_unitario'=>5,  'imposto_percentual'=>0],
            ['descricao'=>'Sweat',     'valor_unitario'=>25, 'imposto_percentual'=>23],
            ['descricao'=>'Mochila',   'valor_unitario'=>30, 'imposto_percentual'=>23],
            ['descricao'=>'Óculos',    'valor_unitario'=>20, 'imposto_percentual'=>23],
            ['descricao'=>'Calções',   'valor_unitario'=>18, 'imposto_percentual'=>23],
        ];

        foreach ($itens as $i) {
            CatalogoFaturaItem::firstOrCreate(
                ['descricao'=>$i['descricao']],
                ['valor_unitario'=>$i['valor_unitario'], 'imposto_percentual'=>$i['imposto_percentual']]
            );
        }
    }
}
