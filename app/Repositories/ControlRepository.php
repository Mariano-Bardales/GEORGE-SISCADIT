<?php

namespace App\Repositories;

use App\Models\ControlMenor1;
use App\Models\ControlRn;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository para acceso a datos de Controles
 */
class ControlRepository
{
    /**
     * Obtener controles CRED de un niño
     */
    public function getCredByNino(int $ninoId): Collection
    {
        return ControlMenor1::where('id_niño', $ninoId)
            ->orderBy('numero_control', 'asc')
            ->get();
    }

    /**
     * Obtener controles RN de un niño
     */
    public function getRnByNino(int $ninoId): Collection
    {
        return ControlRn::where('id_niño', $ninoId)
            ->orderBy('numero_control', 'asc')
            ->get();
    }

    /**
     * Buscar control CRED por ID
     */
    public function findCredById(int $id): ?ControlMenor1
    {
        return ControlMenor1::find($id);
    }

    /**
     * Buscar control RN por ID
     */
    public function findRnById(int $id): ?ControlRn
    {
        return ControlRn::find($id);
    }

    /**
     * Buscar control CRED por número de control y niño
     */
    public function findCredByNumero(int $ninoId, int $numeroControl): ?ControlMenor1
    {
        return ControlMenor1::where('id_niño', $ninoId)
            ->where('numero_control', $numeroControl)
            ->first();
    }

    /**
     * Crear control CRED
     */
    public function createCred(array $data): ControlMenor1
    {
        return ControlMenor1::create($data);
    }

    /**
     * Crear control RN
     */
    public function createRn(array $data): ControlRn
    {
        return ControlRn::create($data);
    }

    /**
     * Actualizar control CRED
     */
    public function updateCred(ControlMenor1 $control, array $data): bool
    {
        return $control->update($data);
    }

    /**
     * Actualizar control RN
     */
    public function updateRn(ControlRn $control, array $data): bool
    {
        return $control->update($data);
    }

    /**
     * Eliminar control CRED
     */
    public function deleteCred(ControlMenor1 $control): bool
    {
        return $control->delete();
    }

    /**
     * Eliminar control RN
     */
    public function deleteRn(ControlRn $control): bool
    {
        return $control->delete();
    }

    /**
     * Contar total de controles
     */
    public function countAll(): int
    {
        return ControlRn::count() + ControlMenor1::count();
    }
}

