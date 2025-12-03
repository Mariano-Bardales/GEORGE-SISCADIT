<?php

namespace App\Repositories;

use App\Models\Nino;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository para acceso a datos de Niños
 */
class NinoRepository
{
    /**
     * Obtener todos los niños
     */
    public function getAll(): Collection
    {
        return Nino::all();
    }

    /**
     * Buscar niño por ID
     */
    public function findById(int $id): ?Nino
    {
        return Nino::where('id_niño', $id)->first();
    }

    /**
     * Buscar niño por ID o lanzar excepción
     */
    public function findByIdOrFail(int $id): Nino
    {
        return Nino::where('id_niño', $id)->firstOrFail();
    }

    /**
     * Crear nuevo niño
     */
    public function create(array $data): Nino
    {
        return Nino::create($data);
    }

    /**
     * Actualizar niño
     */
    public function update(Nino $nino, array $data): bool
    {
        return $nino->update($data);
    }

    /**
     * Eliminar niño
     */
    public function delete(Nino $nino): bool
    {
        return $nino->delete();
    }

    /**
     * Contar total de niños
     */
    public function count(): int
    {
        return Nino::count();
    }

    /**
     * Obtener ID real del niño (id_niño o id)
     */
    public function getRealId(Nino $nino): ?int
    {
        return $nino->id_niño ?? $nino->id ?? null;
    }
}

