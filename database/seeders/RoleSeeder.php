<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Criação de roles principais
        $roles = ['administrador', 'treinador', 'encarregado', 'atleta', 'utilizador' ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Exemplo: Criação de permissões e atribuição (opcional, se quiseres usar permissões mais tarde)
        $permissions = [
            'aceder_dashboard',
            'gerir_utilizadores',
            'ver_faturas',
            'editar_fichas',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Atribuir todas as permissões ao administrador
        $adminRole = Role::where('name', 'administrador')->first();
        if ($adminRole) {
            $adminRole->syncPermissions($permissions);
        }
    }
}
