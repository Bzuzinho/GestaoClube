<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaturaItensSeeder extends Seeder
{
    public function run(): void
    {
        $agora = now();
        $itens = [
            ['descricao' => 'Inscrição', 'valor_unitario' => 15.00, 'imposto_percentual' => 0],
            ['descricao' => 'Touca',     'valor_unitario' => 5.00,  'imposto_percentual' => 0],
            ['descricao' => 'Sweat',     'valor_unitario' => 25.00, 'imposto_percentual' => 0],
            ['descricao' => 'Mochila',   'valor_unitario' => 30.00, 'imposto_percentual' => 0],
            ['descricao' => 'Óculos',    'valor_unitario' => 12.50, 'imposto_percentual' => 0],
            ['descricao' => 'Calções',   'valor_unitario' => 18.00, 'imposto_percentual' => 0],
        ];

        foreach ($itens as $i) {
            DB::table('fatura_itens')->insert([
                'fatura_id'          => 0, // <- se tiveres FK estrita, usa um fatura real ou torna nullable
                'descricao'          => $i['descricao'],
                'valor_unitario'     => $i['valor_unitario'],
                'quantidade'         => 1,
                'imposto_percentual' => $i['imposto_percentual'],
                'total_linha'        => $i['valor_unitario'] * 1 * (1 + $i['imposto_percentual']/100),
                'created_at'         => $agora,
                'updated_at'         => $agora,
            ]);
        }
    }
}
