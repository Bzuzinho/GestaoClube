<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UtilizadorAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cria o role 'admin' se não existir
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Cria o utilizador
        $admin = User::firstOrCreate(
            ['email' => 'admin@bscn.pt'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
            ]
        );

        // Atribui o role
        $admin->assignRole($adminRole);
    }
}
