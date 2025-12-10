<?php

namespace App\Imports;

use App\Models\ControlRn;
use App\Models\Nino;
use App\Imports\Traits\BuscaNinoTrait;
use App\Services\RangosCredService;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ControlesRnImport
{
    use BuscaNinoTrait;
    
    protected $errors = [];
    protected $success = [];
    protected $alertas = []; // Alertas para controles fuera de rango
    protected $stats = ['controles_rn' => 0, 'actualizados' => 0];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $this->importControlRN($row);
            } catch (\Exception $e) {
                $this->errors[] = "Error en fila: " . $e->getMessage();
            }
        }
    }

    protected function importControlRN($row)
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
        if ($numeroControl < 1 || $numeroControl > 4) {
            $this->errors[] = "Número de control RN inválido: {$numeroControl} (debe ser 1-4)";
            return;
        }

        // Aceptar tanto 'fecha' como 'fecha_control'
        $fecha = $this->parseDate($row['fecha'] ?? $row['fecha_control'] ?? null);
        if (!$fecha) {
            $this->errors[] = "Fecha requerida para control RN {$numeroControl} del niño ID: {$ninoId}";
            return;
        }
        
        // Obtener fecha de nacimiento del niño para calcular edad
        $nino = Nino::find($ninoId);
        if (!$nino || !$nino->fecha_nacimiento) {
            $this->errors[] = "No se encontró fecha de nacimiento para el niño ID: {$ninoId}";
            return;
        }

        // Calcular edad en días usando el servicio centralizado
        $edad = RangosCredService::calcularEdadEnDias($nino->fecha_nacimiento, $fecha);
        
        if ($edad === null) {
            $this->errors[] = "No se pudo calcular la edad en días para el control RN {$numeroControl} del niño ID: {$ninoId}";
            return;
        }
        
        // Validar control usando el servicio centralizado
        $validacion = RangosCredService::validarControl($numeroControl, $edad, 'recien_nacido');
        $estado = $validacion['estado'];
        $rango = $validacion['rango'] ?? null;
        
        // Generar alerta si no cumple
        if (!$validacion['cumple'] && $validacion['estado'] === 'NO CUMPLE') {
            $alerta = RangosCredService::generarAlerta($numeroControl, $edad, $fecha->format('Y-m-d'), 'recien_nacido');
            $this->alertas[] = [
                'tipo' => 'Control Recién Nacido',
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
        // NOTA: La tabla 'control_rns' solo tiene: id, id_niño, numero_control, fecha
        $data = [
            'id_niño' => $ninoId,
            'numero_control' => $numeroControl,
            'fecha' => $fecha->format('Y-m-d'),
        ];

        // Verificar si hay ID personalizado del Excel - Buscar en múltiples variaciones de nombres de columnas
        $idCrnPersonalizado = $row['id'] 
                           ?? $row['id_crn'] 
                           ?? $row['idcrn'] 
                           ?? $row['id_control'] 
                           ?? $row['idcontrol'] 
                           ?? null;
        
        // Limpiar el ID: quitar espacios, convertir a número
        if ($idCrnPersonalizado !== null) {
            // Si es string, limpiarlo
            if (is_string($idCrnPersonalizado)) {
                $idCrnPersonalizado = trim($idCrnPersonalizado);
                // Si está vacío después de trim, considerar como null
                if ($idCrnPersonalizado === '') {
                    $idCrnPersonalizado = null;
                }
            }
        }
        
        $controlExistente = null;
        
        // ESTRATEGIA: Primero buscar por ID personalizado si está presente
        if ($idCrnPersonalizado !== null && $idCrnPersonalizado !== '') {
            // Intentar convertir a número
            $idNumero = is_numeric($idCrnPersonalizado) ? (int)$idCrnPersonalizado : null;
            
            if ($idNumero !== null && $idNumero > 0) {
                $controlExistente = ControlRn::find($idNumero);
                
                if ($controlExistente) {
                    // Actualizar el control existente con el ID del Excel
                    $controlExistente->update($data);
                    $this->stats['actualizados']++;
                    $this->success[] = "Control RN {$numeroControl} actualizado usando ID del Excel (ID: {$idNumero}) para niño ID: {$ninoId}";
                    return;
                }
            }
        }
        
        // Si no se encontró por ID, buscar por id_niño + numero_control
        if (!$controlExistente) {
            $controlExistente = ControlRn::where('id_niño', $ninoId)
                                        ->where('numero_control', $numeroControl)
                                        ->first();
            
            if ($controlExistente) {
                // Si hay ID personalizado, actualizar también el ID
                if ($idCrnPersonalizado !== null && $idCrnPersonalizado !== '') {
                    $idNumero = is_numeric($idCrnPersonalizado) ? (int)$idCrnPersonalizado : null;
                    
                        if ($idNumero !== null && $idNumero > 0) {
                            // Verificar que el ID personalizado no esté en uso por otro registro
                            $existeConId = ControlRn::where('id', $idNumero)
                                               ->where('id', '!=', $controlExistente->id)
                                               ->exists();
                            
                            if (!$existeConId) {
                                $data['id'] = $idNumero;
                                // Actualizar con nuevo ID usando DB directo
                                \Illuminate\Support\Facades\DB::table('control_rns')
                                    ->where('id', $controlExistente->id)
                                    ->update($data);
                                $this->stats['actualizados']++;
                                $this->success[] = "Control RN {$numeroControl} actualizado y ID cambiado a {$idNumero} para niño ID: {$ninoId}";
                                return;
                            }
                        }
                }
                
                // Actualizar sin cambiar ID
                $controlExistente->update($data);
                $this->stats['actualizados']++;
                $this->success[] = "Control RN {$numeroControl} actualizado para niño ID: {$ninoId}";
                return;
            }
        }
        
        // CREAR NUEVO CONTROL
        // Si hay ID personalizado, crear con ese ID exacto
        if ($idCrnPersonalizado !== null && $idCrnPersonalizado !== '') {
            $idNumero = is_numeric($idCrnPersonalizado) ? (int)$idCrnPersonalizado : null;
            
            if ($idNumero !== null && $idNumero > 0) {
                // Verificar que el ID no esté en uso
                $existeConId = ControlRn::where('id', $idNumero)->exists();
                
                if (!$existeConId) {
                    // Insertar con ID personalizado usando DB directo
                    $data['id'] = $idNumero;
                    \Illuminate\Support\Facades\DB::table('control_rns')->insert($data);
                    $this->stats['controles_rn']++;
                    $this->success[] = "Control RN {$numeroControl} creado con ID del Excel (ID: {$idNumero}) para niño ID: {$ninoId}";
                    return;
                } else {
                    // ID ya existe, crear sin ID (auto-generado)
                    $this->errors[] = "⚠️ El ID {$idNumero} ya existe en la BD. Se creará con ID automático.";
                }
            }
        }
        
        // Crear sin ID personalizado (auto-generado)
        $control = ControlRn::create($data);
        if ($control && $control->id) {
            $this->stats['controles_rn']++;
            $this->success[] = "Control RN {$numeroControl} creado para niño ID: {$ninoId} (ID auto-generado: {$control->id})";
        }
    }

    protected function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof \DateTime) {
            return Carbon::instance($value);
        }

        if (is_numeric($value)) {
            // Excel date serial number
            return Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($value - 2);
        }

        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
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

