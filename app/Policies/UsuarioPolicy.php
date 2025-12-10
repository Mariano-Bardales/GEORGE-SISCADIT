<?php

namespace App\Policies;

use App\Models\User;

class UsuarioPolicy
{
    /**
     * Determine if the user can view any usuarios.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can view the usuario.
     */
    public function view(User $user, User $usuario): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can create usuarios.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can update the usuario.
     */
    public function update(User $user, User $usuario): bool
    {
        // No permitir modificar usuarios admin principales
        if (in_array($usuario->email, ['diresa@siscadit.com', 'admin@siscadit.com'])) {
            return false;
        }
        
        return $user->isAdmin();
    }

    /**
     * Determine if the user can delete the usuario.
     */
    public function delete(User $user, User $usuario): bool
    {
        // No permitir eliminar usuarios admin principales
        if (in_array($usuario->email, ['diresa@siscadit.com', 'admin@siscadit.com'])) {
            return false;
        }
        
        // No permitir auto-eliminación
        if ($user->id === $usuario->id) {
            return false;
        }
        
        return $user->isAdmin();
    }
}


