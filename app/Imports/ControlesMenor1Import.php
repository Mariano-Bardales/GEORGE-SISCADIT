<?php

namespace App\Imports;

use App\Models\ControlMenor1;
use App\Models\Nino;
use App\Imports\Traits\BuscaNinoTrait;
use App\Services\RangosCredService;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ControlesMenor1Import
{
    use BuscaNinoTrait;
    
    protected $errors = [];
    protected $success = [];
    protected $alertas = []; // Alertas para controles fuera de rango
    protected $stats = ['controles_cred' => 0, 'actualizados' => 0];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $this->importControlCred($row);
            } catch (\Exception $e) {
                $this->errors[] = "Error en fila: " . $e->getMessage();
            }
        }
    }

    protected function importControlCred($row)
    {
        // Buscar el niño por id_niño (llave primaria) o por documento
        $nino = $this->buscarNino($row);
        
        if (!$nino) {
            $idNino = $row['id_nino'] ?? $row['id_niño'] ?? 'N/A';
            $numeroDoc = $row['numero_doc'] ?? 'N/A';
            $this->errors[] = "No se encontró niño con ID: {$idNino} o documento: {$numeroDoc}. Asegúrate de que el niño exista en la hoja 'Niños'.";
            return;
        }
        
        $ninoId = $nino->id;

        // Aceptar tanto 'numero_control' como 'nro_control'
        $numeroControl = (int)($row['numero_control'] ?? $row['nro_control'] ?? 0);
        if ($numeroControl < 1 || $numeroControl > 11) {
            $this->errors[] = "Número de control CRED inválido: {$numeroControl} (debe ser 1-11)";
            return;
        }

        // Aceptar 'fecha', 'fecha_control' o 'fecha_contro' (sin la 'l' final)
        $fechaRaw = $row['fecha'] ?? $row['fecha_control'] ?? $row['fecha_contro'] ?? null;
        \Log::info('Parseando fecha CRED', [
            'valor_raw' => $fechaRaw,
            'tipo' => gettype($fechaRaw),
            'numero_control' => $numeroControl,
            'nino_id' => $ninoId
        ]);
        
        $fecha = $this->parseDate($fechaRaw);
        if (!$fecha) {
            $this->errors[] = "Fecha requerida o inválida para control CRED {$numeroControl} del niño ID: {$ninoId}. Valor recibido: " . ($fechaRaw ?? 'NULL');
            \Log::warning('Fecha CRED inválida', [
                'valor_raw' => $fechaRaw,
                'numero_control' => $numeroControl,
                'nino_id' => $ninoId
            ]);
            return;
        }
        
        \Log::info('Fecha CRED parseada correctamente', [
            'valor_raw' => $fechaRaw,
            'fecha_parseada' => $fecha->format('Y-m-d'),
            'numero_control' => $numeroControl
        ]);
        
        // Obtener fecha de nacimiento del niño para calcular edad
        $nino = Nino::find($ninoId);
        if (!$nino || !$nino->fecha_nacimiento) {
            $this->errors[] = "No se encontró fecha de nacimiento para el niño ID: {$ninoId}";
            return;
        }

        // Calcular edad en días usando el servicio centralizado
        $edad = RangosCredService::calcularEdadEnDias($nino->fecha_nacimiento, $fecha);
        
        if ($edad === null) {
            $this->errors[] = "No se pudo calcular la edad en días para el control CRED {$numeroControl} del niño ID: {$ninoId}";
            return;
        }
        
        // Validar control usando el servicio centralizado
        $validacion = RangosCredService::validarControl($numeroControl, $edad, 'cred');
        $estado = $validacion['estado'];
        $rango = $validacion['rango'] ?? null;
        
        // Generar alerta si no cumple
        if (!$validacion['cumple'] && $validacion['estado'] === 'NO CUMPLE') {
            $alerta = RangosCredService::generarAlerta($numeroControl, $edad, $fecha->format('Y-m-d'), 'cred');
            $this->alertas[] = [
                'tipo' => 'Control CRED',
                'numero_control' => $numeroControl,
                'fecha_control' => $fecha->format('Y-m-d'),
                // edad_dias eliminado - se calcula dinámicamente desde fecha_nacimiento y fecha del control
                'rango_min' => $rango['min'] ?? null,
                'rango_max' => $rango['max'] ?? null,
                'mensaje' => $alerta,
                'nino_id' => $ninoId,
                'nino_nombre' => $nino->apellidos_nombres ?? 'N/A'
            ];
        }

        // Preparar datos del control
        // NOTA: La tabla 'control_menor1s' solo tiene: id, id_niño, numero_control, fecha
        $data = [
            'id_niño' => $ninoId,
            'numero_control' => $numeroControl,
            'fecha' => $fecha->format('Y-m-d'),
        ];

        // Verificar si hay ID personalizado del Excel - Buscar en múltiples variaciones de nombres de columnas
        $idCredPersonalizado = $row['id'] 
                            ?? $row['id_cred'] 
                            ?? $row['idcred'] 
                            ?? $row['id_control'] 
                            ?? $row['idcontrol'] 
                            ?? null;
        
        // Aceptar también 'id_control' como nombre principal (según las imágenes)
        if (empty($idCredPersonalizado) && !empty($row['id_control'])) {
            $idCredPersonalizado = $row['id_control'];
        }
        
        // Limpiar el ID: quitar espacios, convertir a número
        if ($idCredPersonalizado !== null) {
            // Si es string, limpiarlo
            if (is_string($idCredPersonalizado)) {
                $idCredPersonalizado = trim($idCredPersonalizado);
                // Si está vacío después de trim, considerar como null
                if ($idCredPersonalizado === '') {
                    $idCredPersonalizado = null;
                }
            }
        }
        
        $controlExistente = null;
        
        try {
            // ESTRATEGIA: Primero buscar por ID personalizado si está presente
            if ($idCredPersonalizado !== null && $idCredPersonalizado !== '') {
                // Intentar convertir a número
                $idNumero = is_numeric($idCredPersonalizado) ? (int)$idCredPersonalizado : null;
                
                if ($idNumero !== null && $idNumero > 0) {
                    $controlExistente = ControlMenor1::find($idNumero);
                    
                    if ($controlExistente) {
                        // Actualizar el control existente con el ID del Excel
                        $controlExistente->update($data);
                        $this->stats['actualizados']++;
                        $this->success[] = "Control CRED {$numeroControl} actualizado usando ID del Excel (ID: {$idNumero}) para niño ID: {$ninoId}";
                        return;
                    }
                }
            }
            
            // Si no se encontró por ID, buscar por id_niño + numero_control
            if (!$controlExistente) {
                $controlExistente = ControlMenor1::where('id_niño', $ninoId)
                                                ->where('numero_control', $numeroControl)
                                                ->first();
                
                if ($controlExistente) {
                    // Si hay ID personalizado, actualizar también el ID
                    if ($idCredPersonalizado !== null && $idCredPersonalizado !== '') {
                        $idNumero = is_numeric($idCredPersonalizado) ? (int)$idCredPersonalizado : null;
                        
                        if ($idNumero !== null && $idNumero > 0) {
                            // Verificar que el ID personalizado no esté en uso por otro registro
                            $existeConId = ControlMenor1::where('id', $idNumero)
                                                       ->where('id', '!=', $controlExistente->id)
                                                       ->exists();
                            
                            if (!$existeConId) {
                                $data['id'] = $idNumero;
                                // Actualizar con nuevo ID usando DB directo
                                \Illuminate\Support\Facades\DB::table('control_menor1s')
                                    ->where('id', $controlExistente->id)
                                    ->update($data);
                                $this->stats['actualizados']++;
                                $this->success[] = "Control CRED {$numeroControl} actualizado y ID cambiado a {$idNumero} para niño ID: {$ninoId}";
                                return;
                            }
                        }
                    }
                    
                    // Actualizar sin cambiar ID
                    $controlExistente->update($data);
                    $this->stats['actualizados']++;
                    $this->success[] = "Control CRED {$numeroControl} actualizado para niño ID: {$ninoId}";
                    return;
                }
            }
            
            // CREAR NUEVO CONTROL
            // Si hay ID personalizado, crear con ese ID exacto
            if ($idCredPersonalizado !== null && $idCredPersonalizado !== '') {
                $idNumero = is_numeric($idCredPersonalizado) ? (int)$idCredPersonalizado : null;
                
                if ($idNumero !== null && $idNumero > 0) {
                    // Verificar que el ID no esté en uso
                    $existeConId = ControlMenor1::where('id', $idNumero)->exists();
                    
                    if (!$existeConId) {
                        // Insertar con ID personalizado usando DB directo
                        $data['id'] = $idNumero;
                        \Illuminate\Support\Facades\DB::table('control_menor1s')->insert($data);
                        $this->stats['controles_cred']++;
                        $this->success[] = "Control CRED {$numeroControl} creado con ID del Excel (ID: {$idNumero}) para niño ID: {$ninoId}";
                        return;
                    } else {
                        // ID ya existe, crear sin ID (auto-generado)
                        $this->errors[] = "⚠️ El ID {$idNumero} ya existe en la BD. Se creará con ID automático.";
                    }
                }
            }
            
            // Crear sin ID personalizado (auto-generado)
            \Log::info('Creando control CRED nuevo', [
                'nino_id' => $ninoId,
                'numero_control' => $numeroControl,
                'datos' => $data
            ]);
            
            $control = ControlMenor1::create($data);
            if ($control && $control->id) {
                $this->stats['controles_cred']++;
                $this->success[] = "Control CRED {$numeroControl} creado para niño ID: {$ninoId} (ID auto-generado: {$control->id})";
                \Log::info('Control CRED creado exitosamente', [
                    'id' => $control->id,
                    'nino_id' => $ninoId,
                    'numero_control' => $numeroControl
                ]);
            } else {
                $this->errors[] = "No se pudo crear el control CRED {$numeroControl} para niño ID: {$ninoId}";
                \Log::error('Error al crear control CRED', [
                    'nino_id' => $ninoId,
                    'numero_control' => $numeroControl,
                    'datos' => $data,
                    'control_creado' => $control ? $control->toArray() : null
                ]);
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "Error al guardar control CRED {$numeroControl} para niño ID: {$ninoId}: " . $e->getMessage();
            throw $e; // Re-lanzar para que la transacción haga rollback
        }
    }

    protected function parseDate($value)
    {
        if (empty($value) && $value !== 0 && $value !== '0') {
            return null;
        }

        if ($value instanceof \DateTime) {
            return Carbon::instance($value);
        }

        // Si es un número (formato serial de Excel)
        if (is_numeric($value)) {
            $numericValue = (float)$value;
            try {
                $baseDate = Carbon::create(1900, 1, 1);
                $daysToAdd = (int)($numericValue - 2); // Restar 2 porque Excel cuenta desde 1900-01-01 como día 1, y tiene un bug con 1900
                return $baseDate->copy()->addDays($daysToAdd);
            } catch (\Exception $e) {
                \Log::warning('Error al parsear fecha numérica de Excel en CRED', [
                    'valor' => $value,
                    'error' => $e->getMessage()
                ]);
                return null;
            }
        }

        // Si es una cadena, intentar parsearla
        try {
            // Intentar diferentes formatos comunes
            $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'Y/m/d', 'd-m-Y', 'm-d-Y'];
            foreach ($formats as $format) {
                try {
                    $parsed = Carbon::createFromFormat($format, $value);
                    if ($parsed) {
                        return $parsed;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            
            // Si ningún formato funciona, intentar parse automático
            return Carbon::parse($value);
        } catch (\Exception $e) {
            \Log::warning('Error al parsear fecha como string en CRED', [
                'valor' => $value,
                'tipo' => gettype($value),
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    protected function calculateAge($ninoId, $fechaControl)
    {
        if (!$fechaControl) {
            return null;
        }

        $nino = Nino::where('id_niño', $ninoId)->first();
        if (!$nino || !$nino->fecha_nacimiento) {
            return null;
        }

        $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
        return $fechaNacimiento->diffInDays($fechaControl);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function getStats()
    {
        return $this->stats;
    }

    public function getAlertas()
    {
        return $this->alertas;
    }
}

