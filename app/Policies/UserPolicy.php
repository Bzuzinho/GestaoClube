<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Se quiseres que 'admin' tenha sempre acesso total,
     * descomenta o método before().
     */
    // public function before(User $actor, string $ability): ?bool
    // {
    //     if ($actor->hasRole('admin')) {
    //         return true;
    //     }
    //     return null;
    // }

    /** Ver a listagem de utilizadores */
    public function viewAny(User $actor): bool
    {
        return $this->canManage($actor)
            || $actor->can('utilizadores.ver');
    }

    /** Ver o perfil de um utilizador */
    public function view(User $actor, User $target): bool
    {
        return $this->canManage($actor)
            || $actor->can('utilizadores.ver')
            || $actor->id === $target->id; // o próprio pode ver
    }

    /** Criar novos utilizadores */
    public function create(User $actor): bool
    {
        return $this->canManage($actor)
            || $actor->can('utilizadores.criar');
    }

    /** Editar um utilizador (inclui o próprio) */
    public function update(User $actor, User $target): bool
    {
        return $this->canManage($actor)
            || $actor->can('utilizadores.editar')
            || $actor->id === $target->id; // o próprio pode editar o seu perfil
    }

    /** Apagar utilizadores (nunca a si próprio) */
    public function delete(User $actor, User $target): bool
    {
        if ($actor->id === $target->id) {
            return false; // impedir auto-remoção
        }

        return $this->canManage($actor)
            || $actor->can('utilizadores.apagar');
    }

    /** Restaurar utilizadores (se usares soft deletes) */
    public function restore(User $actor, User $target): bool
    {
        return $this->canManage($actor)
            || $actor->can('utilizadores.apagar');
    }

    /** Eliminação definitiva (se usares soft deletes) */
    public function forceDelete(User $actor, User $target): bool
    {
        return $this->canManage($actor)
            || $actor->can('utilizadores.apagar');
    }

    /** Permite definir/alterar perfis (roles) de um utilizador */
    public function setRole(User $actor, User $target): bool
    {
        // evita que o próprio retire acidentalmente o seu role sem ser gestor
        if ($actor->id === $target->id && ! $this->canManage($actor)) {
            return false;
        }

        return $this->canManage($actor)
            || $actor->can('utilizadores.definir-role')
            || $actor->can('utilizadores.gerir');
    }

    /**
     * Helper: perfis “gestores” do módulo de pessoas.
     * Ajusta os nomes de roles conforme o teu projeto.
     */
    private function canManage(User $actor): bool
{
    // aceita Spatie OU o campo legado users.role
    return $actor->hasAnyRole(['admin','administrador','Administrador'])
        || $actor->role === 'admin'             //  <-- legado
        || $actor->can('utilizadores.gerir');
}
}
