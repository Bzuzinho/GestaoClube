<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EventoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Criar permissões
        $permissoes = [
            'ver_eventos',
            'criar_eventos',
            'editar_eventos',
            'apagar_eventos',
            'gerir_convocados',
        ];

        foreach ($permissoes as $permissao) {
            Permission::firstOrCreate(['name' => $permissao, 'guard_name' => 'web']);
        }

        // Associar permissões aos roles
        $admin = Role::where('name', 'administrador')->first();
        $treinador = Role::where('name', 'treinador')->first();

        if ($admin) {
            $admin->givePermissionTo([
                'ver_eventos',
                'criar_eventos',
                'editar_eventos',
                'apagar_eventos',
                'gerir_convocados',
            ]);
        }

        if ($treinador) {
            $treinador->givePermissionTo([
                'ver_eventos',
                'criar_eventos',
                'editar_eventos',
                'gerir_convocados',
            ]);
        }
    }
}
