<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Nino;

/**
 * Servicio para cálculos de edad
 */
class EdadService
{
    /**
     * Calcular edad en días desde fecha de nacimiento hasta fecha de control
     * 
     * @param string|Carbon|Nino $fechaNacimiento
     * @param string|Carbon|null $fechaControl Si es null, usa fecha actual
     * @return int|null
     */
    public function calcularEdadEnDias($fechaNacimiento, $fechaControl = null): ?int
    {
        try {
            // Si es un modelo Nino, obtener fecha_nacimiento
            if ($fechaNacimiento instanceof Nino) {
                $fechaNac = $fechaNacimiento->fecha_nacimiento 
                    ? Carbon::parse($fechaNacimiento->fecha_nacimiento) 
                    : null;
            } elseif (is_string($fechaNacimiento)) {
                $fechaNac = Carbon::parse($fechaNacimiento);
            } elseif ($fechaNacimiento instanceof Carbon) {
                $fechaNac = $fechaNacimiento;
            } else {
                return null;
            }

            if (!$fechaNac) {
                return null;
            }

            $fechaCtrl = $fechaControl 
                ? (is_string($fechaControl) ? Carbon::parse($fechaControl) : $fechaControl->copy())
                : Carbon::now();

            // Normalizar fechas a inicio del día para evitar problemas con horas
            $fechaNac->startOfDay();
            $fechaCtrl->startOfDay();

            return $fechaNac->diffInDays($fechaCtrl);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Calcular edad en meses
     */
    public function calcularEdadEnMeses($fechaNacimiento, $fechaControl = null): ?int
    {
        try {
            if ($fechaNacimiento instanceof Nino) {
                $fechaNac = $fechaNacimiento->fecha_nacimiento 
                    ? Carbon::parse($fechaNacimiento->fecha_nacimiento) 
                    : null;
            } elseif (is_string($fechaNacimiento)) {
                $fechaNac = Carbon::parse($fechaNacimiento);
            } elseif ($fechaNacimiento instanceof Carbon) {
                $fechaNac = $fechaNacimiento;
            } else {
                return null;
            }

            if (!$fechaNac) {
                return null;
            }

            $fechaCtrl = $fechaControl 
                ? (is_string($fechaControl) ? Carbon::parse($fechaControl) : $fechaControl)
                : Carbon::now();

            return $fechaNac->diffInMonths($fechaCtrl);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Obtener edad actual del niño
     */
    public function obtenerEdadActual(Nino $nino): array
    {
        $edadDias = $this->calcularEdadEnDias($nino);
        $edadMeses = $this->calcularEdadEnMeses($nino);

        return [
            'dias' => $edadDias,
            'meses' => $edadMeses,
            'fecha_nacimiento' => $nino->fecha_nacimiento 
                ? Carbon::parse($nino->fecha_nacimiento)->format('Y-m-d') 
                : null,
        ];
    }
}

