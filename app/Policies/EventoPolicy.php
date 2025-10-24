<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Evento;

class EventoPolicy
{
    /**
     * Determina se o utilizador pode ver o evento.
     */
    public function view(User $user, Evento $evento): bool
    {
        // Qualquer utilizador autenticado pode ver eventos públicos
       if ($evento->visibilidade === 'publico') {
        return true; // Qualquer autenticado vê
        }

        return $user->hasRole(['administrador', 'treinador']);
        }

    /**
     * Determina se o utilizador pode criar eventos.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['administrador', 'treinador']);
    }

    /**
     * Determina se o utilizador pode atualizar o evento.
     */
    public function update(User $user, Evento $evento): bool
    {
        return $user->hasRole(['administrador', 'treinador']);
    }

    /**
     * Determina se o utilizador pode apagar o evento.
     */
    public function delete(User $user, Evento $evento): bool
    {
        return $user->hasRole(['administrador', 'treinador']);
    }
}
