<?php

namespace App\Services;

use Carbon\Carbon;

/**
 * Servicio centralizado para manejar rangos de edad de controles CRED
 * Define los rangos oficiales y proporciona métodos de validación
 */
class RangosCredService
{
    /**
     * Rangos de edad (en días) para controles de Recién Nacido (RN)
     * Control 1-4: Para niños de 0-28 días
     */
    public static function getRangosRecienNacido(): array
    {
        return [
            1 => ['min' => 2, 'max' => 6, 'descripcion' => 'Control 1 - Verifica adaptación y lactancia'],
            2 => ['min' => 7, 'max' => 13, 'descripcion' => 'Control 2 - Seguimiento del peso y signos de alarma'],
            3 => ['min' => 14, 'max' => 20, 'descripcion' => 'Control 3 - Evaluación del crecimiento y orientación al cuidador'],
            4 => ['min' => 21, 'max' => 28, 'descripcion' => 'Control 4 - Confirmación final del estado de salud neonatal'],
        ];
    }

    /**
     * Rangos de edad (en días) para controles CRED Mensual
     * Control 1-11: Para niños de 29-359 días
     */
    public static function getRangosCredMensual(): array
    {
        return [
            1 => ['min' => 29, 'max' => 59, 'descripcion' => 'Control 1 - Primer mes (29-59 días)'],
            2 => ['min' => 60, 'max' => 89, 'descripcion' => 'Control 2 - Segundo mes (60-89 días)'],
            3 => ['min' => 90, 'max' => 119, 'descripcion' => 'Control 3 - Tercer mes (90-119 días)'],
            4 => ['min' => 120, 'max' => 149, 'descripcion' => 'Control 4 - Cuarto mes (120-149 días)'],
            5 => ['min' => 150, 'max' => 179, 'descripcion' => 'Control 5 - Quinto mes (150-179 días)'],
            6 => ['min' => 180, 'max' => 209, 'descripcion' => 'Control 6 - Sexto mes (180-209 días)'],
            7 => ['min' => 210, 'max' => 239, 'descripcion' => 'Control 7 - Séptimo mes (210-239 días)'],
            8 => ['min' => 240, 'max' => 269, 'descripcion' => 'Control 8 - Octavo mes (240-269 días)'],
            9 => ['min' => 270, 'max' => 299, 'descripcion' => 'Control 9 - Noveno mes (270-299 días)'],
            10 => ['min' => 300, 'max' => 329, 'descripcion' => 'Control 10 - Décimo mes (300-329 días)'],
            11 => ['min' => 330, 'max' => 359, 'descripcion' => 'Control 11 - Undécimo mes (330-359 días)'],
        ];
    }

    /**
     * Obtener todos los rangos disponibles
     */
    public static function getAllRangos(): array
    {
        return [
            'recien_nacido' => self::getRangosRecienNacido(),
            'cred_mensual' => self::getRangosCredMensual(),
        ];
    }

    /**
     * Calcular edad en días entre dos fechas
     * 
     * @param string|Carbon $fechaNacimiento
     * @param string|Carbon $fechaControl
     * @return int|null
     */
    public static function calcularEdadEnDias($fechaNacimiento, $fechaControl): ?int
    {
        try {
            if (is_string($fechaNacimiento)) {
                $fechaNac = Carbon::parse($fechaNacimiento);
            } elseif ($fechaNacimiento instanceof Carbon) {
                $fechaNac = $fechaNacimiento->copy();
            } else {
                return null;
            }

            if (is_string($fechaControl)) {
                $fechaCtrl = Carbon::parse($fechaControl);
            } elseif ($fechaControl instanceof Carbon) {
                $fechaCtrl = $fechaControl->copy();
            } else {
                return null;
            }

            // Normalizar fechas a inicio del día para evitar problemas con horas
            $fechaNac->startOfDay();
            $fechaCtrl->startOfDay();
            
            return $fechaNac->diffInDays($fechaCtrl);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Validar si un control CRED cumple con el rango de edad
     * 
     * @param int $numeroControl Número del control (1-11 para CRED, 1-4 para RN)
     * @param int $edadEnDias Edad del niño en días al momento del control
     * @param string $tipoControl Tipo de control: 'cred' o 'recien_nacido'
     * @return array ['cumple' => bool, 'estado' => string, 'rango' => array]
     */
    public static function validarControl($numeroControl, $edadEnDias, $tipoControl = 'cred'): array
    {
        if ($edadEnDias === null || $edadEnDias < 0) {
            return [
                'cumple' => false,
                'estado' => 'SEGUIMIENTO',
                'rango' => null,
                'mensaje' => 'No se pudo calcular la edad en días'
            ];
        }

        // Obtener rangos según el tipo
        if ($tipoControl === 'recien_nacido' || $tipoControl === 'rn') {
            $rangos = self::getRangosRecienNacido();
        } else {
            $rangos = self::getRangosCredMensual();
        }

        // Verificar si el número de control existe
        if (!isset($rangos[$numeroControl])) {
            return [
                'cumple' => false,
                'estado' => 'SEGUIMIENTO',
                'rango' => null,
                'mensaje' => "Número de control inválido: {$numeroControl}"
            ];
        }

        $rango = $rangos[$numeroControl];
        $min = $rango['min'];
        $max = $rango['max'];

        // Determinar cumplimiento
        if ($edadEnDias >= $min && $edadEnDias <= $max) {
            return [
                'cumple' => true,
                'estado' => 'CUMPLE',
                'rango' => $rango,
                'edad_en_dias' => $edadEnDias,
                'mensaje' => "Control realizado a los {$edadEnDias} días (rango: {$min}-{$max} días)"
            ];
        } elseif ($edadEnDias < $min) {
            return [
                'cumple' => false,
                'estado' => 'SEGUIMIENTO',
                'rango' => $rango,
                'edad_en_dias' => $edadEnDias,
                'mensaje' => "Control realizado antes del rango mínimo (edad: {$edadEnDias} días, mínimo: {$min} días)"
            ];
        } else {
            return [
                'cumple' => false,
                'estado' => 'NO CUMPLE',
                'rango' => $rango,
                'edad_en_dias' => $edadEnDias,
                'mensaje' => "Control realizado fuera del rango (edad: {$edadEnDias} días, rango: {$min}-{$max} días)"
            ];
        }
    }

    /**
     * Generar mensaje de alerta para un control que no cumple
     */
    public static function generarAlerta($numeroControl, $edadEnDias, $fechaControl, $tipoControl = 'cred'): string
    {
        $validacion = self::validarControl($numeroControl, $edadEnDias, $tipoControl);
        
        if ($validacion['cumple']) {
            return '';
        }

        $tipoNombre = $tipoControl === 'recien_nacido' ? 'Control Recién Nacido' : 'Control CRED';
        $rango = $validacion['rango'];
        
        if ($rango) {
            return "⚠️ {$tipoNombre} {$numeroControl}: Realizado el {$fechaControl} a los {$edadEnDias} días, fuera del rango estimado ({$rango['min']}-{$rango['max']} días).";
        }
        
        return "⚠️ {$tipoNombre} {$numeroControl}: Error en validación - {$validacion['mensaje']}";
    }

    /**
     * Obtener el rango de un control específico
     */
    public static function getRango($numeroControl, $tipoControl = 'cred'): ?array
    {
        if ($tipoControl === 'recien_nacido' || $tipoControl === 'rn') {
            $rangos = self::getRangosRecienNacido();
        } else {
            $rangos = self::getRangosCredMensual();
        }

        return $rangos[$numeroControl] ?? null;
    }
}



