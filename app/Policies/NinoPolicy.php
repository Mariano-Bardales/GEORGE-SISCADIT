<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Nino;

class NinoPolicy
{
    /**
     * Determine if the user can view any niños.
     */
    public function viewAny(User $user): bool
    {
        return in_array(strtolower($user->role), ['admin', 'jefe_red', 'coordinador_microred', 'usuario']);
    }

    /**
     * Determine if the user can view the niño.
     */
    public function view(User $user, Nino $nino): bool
    {
        return in_array(strtolower($user->role), ['admin', 'jefe_red', 'coordinador_microred', 'usuario']);
    }

    /**
     * Determine if the user can create niños.
     */
    public function create(User $user): bool
    {
        return in_array(strtolower($user->role), ['admin', 'jefe_red', 'coordinador_microred', 'usuario']);
    }

    /**
     * Determine if the user can update the niño.
     */
    public function update(User $user, Nino $nino): bool
    {
        // Admin puede editar todo
        if ($user->isAdmin()) {
            return true;
        }
        
        // Otros roles pueden editar según su jurisdicción
        // TODO: Implementar verificación por red/microred
        return in_array(strtolower($user->role), ['admin', 'jefe_red', 'coordinador_microred', 'usuario']);
    }

    /**
     * Determine if the user can delete the niño.
     */
    public function delete(User $user, Nino $nino): bool
    {
        // Solo admin puede eliminar
        return $user->isAdmin();
    }
}


