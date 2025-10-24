<?php

namespace App\Observers;

use Spatie\Permission\Models\Role;
use App\Models\User;

class UserObserver
{

    public function saved(User $user)
    {
        if ($user->role) {
            // Garante que o role existe na tabela da Spatie
            Role::firstOrCreate(['name' => $user->role]);

            // Sincroniza o role da Spatie com o campo da base de dados
            $user->syncRoles([$user->role]);
        }
    }
    
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
