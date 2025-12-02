<?php

namespace App\Imports;

use App\Models\Nino;
use App\Models\Madre;
use App\Models\ControlRn;
use App\Models\ControlMenor1;
use App\Models\TamizajeNeonatal;
use App\Models\VacunaRn;
use App\Models\VisitaDomiciliaria;
use App\Models\DatosExtra;
use App\Models\RecienNacido;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ControlesImport
{
    protected $errors = [];
    protected $success = [];
    protected $stats = [
        'ninos' => 0,
        'madres' => 0,
        'controles_rn' => 0,
        'controles_cred' => 0,
        'tamizajes' => 0,
        'vacunas' => 0,
        'visitas' => 0,
        'datos_extra' => 0,
        'recien_nacido' => 0,
    ];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $this->processRow($row);
            } catch (\Exception $e) {
                $this->errors[] = "Error en fila: " . $e->getMessage();
            }
        }
    }

    protected function processRow($row)
    {
        // Procesar según el tipo de control
        $tipoControl = strtolower(trim($row['tipo_control'] ?? ''));

        // Si es tipo NINO, crear/actualizar el niño
        if ($tipoControl === 'nino' || $tipoControl === 'niño' || $tipoControl === 'nino') {
            $this->importNino($row);
            return;
        }

        // Si es tipo MADRE, crear/actualizar la madre
        if ($tipoControl === 'madre') {
            $this->importMadre($row);
            return;
        }

        // Para los demás tipos, necesitamos el niño
        // Buscar el niño por ID o por número de documento
        $nino = null;
        
        if (!empty($row['id_nino'])) {
            $nino = Nino::where('id_niño', $row['id_nino'])->first();
        } elseif (!empty($row['numero_documento']) && !empty($row['tipo_documento'])) {
            $nino = Nino::where('numero_doc', $row['numero_documento'])
                       ->where('tipo_doc', $row['tipo_documento'])
                       ->first();
        }

        if (!$nino) {
            $this->errors[] = "No se encontró niño con ID: " . ($row['id_nino'] ?? 'N/A') . " o documento: " . ($row['numero_documento'] ?? 'N/A');
            return;
        }

        $ninoId = $nino->id_niño;

        switch ($tipoControl) {
            case 'crn':
            case 'control rn':
            case 'control recien nacido':
                $this->importControlRN($ninoId, $row);
                break;
            
            case 'cred':
            case 'cred mensual':
            case 'control cred':
                $this->importControlCred($ninoId, $row);
                break;
            
            case 'tamizaje':
            case 'tamizaje neonatal':
                $this->importTamizaje($ninoId, $row);
                break;
            
            case 'vacuna':
            case 'vacunas':
            case 'vacuna rn':
                $this->importVacuna($ninoId, $row);
                break;
            
            case 'visita':
            case 'visitas':
            case 'visita domiciliaria':
                $this->importVisita($ninoId, $row);
                break;
            
            case 'datos extra':
            case 'datos_extra':
                $this->importDatosExtra($ninoId, $row);
                break;
            
            case 'recien nacido':
            case 'recien_nacido':
            case 'cnv':
                $this->importRecienNacido($ninoId, $row);
                break;
            
            default:
                $this->errors[] = "Tipo de control desconocido: " . ($row['tipo_control'] ?? 'N/A');
        }
    }

    protected function importNino($row)
    {
        // Buscar el niño por ID o por número de documento
        $nino = null;
        
        if (!empty($row['id_nino'])) {
            $nino = Nino::where('id_niño', $row['id_nino'])->first();
        } elseif (!empty($row['numero_documento']) && !empty($row['tipo_documento'])) {
            $nino = Nino::where('numero_doc', $row['numero_documento'])
                       ->where('tipo_doc', $row['tipo_documento'])
                       ->first();
        }

        // Mapear tipo de documento
        $tipoDocMap = [
            'DNI' => 'DNI', 'dni' => 'DNI', '1' => 'DNI',
            'CE' => 'CE', 'ce' => 'CE', '2' => 'CE',
            'PASS' => 'PASS', 'pass' => 'PASS', '3' => 'PASS',
            'DIE' => 'DIE', 'die' => 'DIE', '4' => 'DIE',
            'S/ DOCUMENTO' => 'S/ DOCUMENTO', 'sin documento' => 'S/ DOCUMENTO', '5' => 'S/ DOCUMENTO',
            'CNV' => 'CNV', 'cnv' => 'CNV', '6' => 'CNV',
        ];
        $tipoDoc = $tipoDocMap[$row['tipo_documento'] ?? ''] ?? 'S/ DOCUMENTO';

        $fechaNacimiento = $this->parseDate($row['fecha_nacimiento'] ?? null);
        if (!$fechaNacimiento) {
            $this->errors[] = "Fecha de nacimiento requerida para crear/actualizar niño";
            return;
        }

        // Calcular edad
        $hoy = Carbon::now();
        $edadMeses = $fechaNacimiento->diffInMonths($hoy);
        $edadDias = $fechaNacimiento->diffInDays($hoy);

        $data = [
            'establecimiento' => $row['establecimiento'] ?? null,
            'tipo_doc' => $tipoDoc,
            'numero_doc' => $row['numero_documento'] ?? null,
            'apellidos_nombres' => $row['apellidos_nombres'] ?? $row['nombre'] ?? null,
            'fecha_nacimiento' => $fechaNacimiento->format('Y-m-d'),
            'genero' => strtoupper($row['genero'] ?? $row['sexo'] ?? 'M'),
            'edad_meses' => $edadMeses,
            'edad_dias' => $edadDias,
        ];

        if ($nino) {
            // Actualizar niño existente
            $nino->update($data);
            $this->success[] = "Niño actualizado: " . ($data['apellidos_nombres'] ?? 'N/A');
        } else {
            // Crear nuevo niño
            $nino = Nino::create($data);
            $this->stats['ninos']++;
            $this->success[] = "Niño creado: " . ($data['apellidos_nombres'] ?? 'N/A') . " (ID: {$nino->id_niño})";
        }

        // Si hay datos extras en la misma fila, importarlos
        if (!empty($row['red']) || !empty($row['microred']) || !empty($row['distrito'])) {
            $this->importDatosExtra($nino->id_niño, $row);
        }
    }

    protected function importMadre($row)
    {
        // Buscar el niño primero (necesario para asociar la madre)
        $nino = null;
        
        if (!empty($row['id_nino'])) {
            $nino = Nino::where('id_niño', $row['id_nino'])->first();
        } elseif (!empty($row['numero_documento_nino']) && !empty($row['tipo_documento_nino'])) {
            $nino = Nino::where('numero_doc', $row['numero_documento_nino'])
                       ->where('tipo_doc', $row['tipo_documento_nino'])
                       ->first();
        }

        if (!$nino) {
            $this->errors[] = "No se encontró niño para asociar la madre. ID: " . ($row['id_nino'] ?? 'N/A');
            return;
        }

        $ninoId = $nino->id_niño;

        // Buscar madre existente por DNI
        $madre = null;
        if (!empty($row['dni_madre'])) {
            $madre = Madre::where('dni', $row['dni_madre'])->first();
        }

        $data = [
            'id_niño' => $ninoId,
            'dni' => $row['dni_madre'] ?? null,
            'apellidos_nombres' => $row['apellidos_nombres_madre'] ?? $row['nombre_madre'] ?? 'Sin especificar',
            'celular' => $row['celular_madre'] ?? null,
            'domicilio' => $row['domicilio_madre'] ?? null,
            'referencia_direccion' => $row['referencia_direccion'] ?? null,
        ];

        if ($madre) {
            // Actualizar madre existente
            $madre->update($data);
            $this->success[] = "Madre actualizada para niño ID: {$ninoId}";
        } else {
            // Crear nueva madre
            Madre::create($data);
            $this->stats['madres']++;
            $this->success[] = "Madre creada para niño ID: {$ninoId}";
        }
    }

    protected function importControlRN($ninoId, $row)
    {
        $numeroControl = (int)($row['numero_control'] ?? 0);
        if ($numeroControl < 1 || $numeroControl > 4) {
            $this->errors[] = "Número de control RN inválido: {$numeroControl} (debe ser 1-4)";
            return;
        }

        // Verificar si ya existe
        $existe = ControlRn::where('id_niño', $ninoId)
                          ->where('numero_control', $numeroControl)
                          ->exists();

        if ($existe && empty($row['sobrescribir'])) {
            $this->errors[] = "Control RN {$numeroControl} ya existe para niño ID: {$ninoId}";
            return;
        }

        $fecha = $this->parseDate($row['fecha'] ?? null);
        if (!$fecha) {
            $this->errors[] = "Fecha requerida para control RN {$numeroControl} del niño ID: {$ninoId}";
            return;
        }
        
        // Calcular edad usando función MySQL o cálculo directo
        $edad = $this->calculateAge($ninoId, $fecha);
        
        // Determinar estado automáticamente basándose en rangos
        $rangosRN = [
            1 => ['min' => 2, 'max' => 6],
            2 => ['min' => 7, 'max' => 13],
            3 => ['min' => 14, 'max' => 20],
            4 => ['min' => 21, 'max' => 28],
        ];
        
        $rango = $rangosRN[$numeroControl] ?? ['min' => 0, 'max' => 28];
        $estado = 'SEGUIMIENTO'; // Por defecto
        
        if ($edad !== null) {
            if ($edad >= $rango['min'] && $edad <= $rango['max']) {
                $estado = 'CUMPLE';
            } elseif ($edad > $rango['max']) {
                $estado = 'NO CUMPLE';
            }
        }

        $data = [
            'id_niño' => $ninoId,
            'numero_control' => $numeroControl,
            'fecha' => $fecha,
            'edad' => $edad,
            'estado' => $estado, // Estado calculado automáticamente
            'peso' => !empty($row['peso']) ? (float)$row['peso'] : null,
            'talla' => !empty($row['talla']) ? (float)$row['talla'] : null,
            'perimetro_cefalico' => !empty($row['perimetro_cefalico']) || !empty($row['pc']) ? (float)($row['perimetro_cefalico'] ?? $row['pc']) : null,
        ];

        if ($existe) {
            ControlRn::where('id_niño', $ninoId)
                     ->where('numero_control', $numeroControl)
                     ->update($data);
        } else {
            ControlRn::create($data);
        }

        $this->stats['controles_rn']++;
        $this->success[] = "Control RN {$numeroControl} importado para niño ID: {$ninoId}";
    }

    protected function importControlCred($ninoId, $row)
    {
        $numeroControl = (int)($row['numero_control'] ?? 0);
        if ($numeroControl < 1 || $numeroControl > 11) {
            $this->errors[] = "Número de control CRED inválido: {$numeroControl} (debe ser 1-11)";
            return;
        }

        // Verificar si ya existe
        $existe = ControlMenor1::where('id_niño', $ninoId)
                               ->where('numero_control', $numeroControl)
                               ->exists();

        if ($existe && empty($row['sobrescribir'])) {
            $this->errors[] = "Control CRED {$numeroControl} ya existe para niño ID: {$ninoId}";
            return;
        }

        $fecha = $this->parseDate($row['fecha'] ?? null);
        if (!$fecha) {
            $this->errors[] = "Fecha requerida para control CRED {$numeroControl} del niño ID: {$ninoId}";
            return;
        }
        
        // Calcular edad usando función MySQL o cálculo directo
        $edad = $this->calculateAge($ninoId, $fecha);
        
        // Determinar estado automáticamente basándose en rangos CRED
        $rangosCRED = [
            1 => ['min' => 29, 'max' => 59],
            2 => ['min' => 60, 'max' => 89],
            3 => ['min' => 90, 'max' => 119],
            4 => ['min' => 120, 'max' => 149],
            5 => ['min' => 150, 'max' => 179],
            6 => ['min' => 180, 'max' => 209],
            7 => ['min' => 210, 'max' => 239],
            8 => ['min' => 240, 'max' => 269],
            9 => ['min' => 270, 'max' => 299],
            10 => ['min' => 300, 'max' => 329],
            11 => ['min' => 330, 'max' => 359],
        ];
        
        $rango = $rangosCRED[$numeroControl] ?? ['min' => 0, 'max' => 365];
        $estado = 'SEGUIMIENTO'; // Por defecto
        
        if ($edad !== null) {
            if ($edad >= $rango['min'] && $edad <= $rango['max']) {
                $estado = 'CUMPLE';
            } elseif ($edad > $rango['max']) {
                $estado = 'NO CUMPLE';
            }
        }

        $data = [
            'id_niño' => $ninoId,
            'numero_control' => $numeroControl,
            'fecha' => $fecha,
            'edad' => $edad,
            'estado' => $estado, // Estado calculado automáticamente
            'estado_cred_once' => $row['estado_cred_once'] ?? null,
            'estado_cred_final' => $row['estado_cred_final'] ?? null,
            'peso' => !empty($row['peso']) ? (float)$row['peso'] : null,
            'talla' => !empty($row['talla']) ? (float)$row['talla'] : null,
            'perimetro_cefalico' => !empty($row['perimetro_cefalico']) || !empty($row['pc']) ? (float)($row['perimetro_cefalico'] ?? $row['pc']) : null,
        ];

        if ($existe) {
            ControlMenor1::where('id_niño', $ninoId)
                         ->where('numero_control', $numeroControl)
                         ->update($data);
        } else {
            ControlMenor1::create($data);
        }

        $this->stats['controles_cred']++;
        $this->success[] = "Control CRED {$numeroControl} importado para niño ID: {$ninoId}";
    }

    protected function importTamizaje($ninoId, $row)
    {
        $existe = TamizajeNeonatal::where('id_niño', $ninoId)->exists();

        if ($existe && empty($row['sobrescribir'])) {
            $this->errors[] = "Tamizaje ya existe para niño ID: {$ninoId}";
            return;
        }

        $nino = Nino::find($ninoId);
        $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
        $fecha29Dias = $fechaNacimiento->copy()->addDays(29);
        $fechaTamizaje = $this->parseDate($row['fecha_tamizaje'] ?? null);
        
        if (!$fechaTamizaje) {
            $this->errors[] = "Fecha de tamizaje requerida para niño ID: {$ninoId}";
            return;
        }
        
        $edadTamizaje = $fechaNacimiento->diffInDays($fechaTamizaje);
        
        // Determinar si cumple: debe realizarse antes de los 29 días
        $cumpleTamizaje = ($edadTamizaje >= 0 && $edadTamizaje <= 29) ? 'SI' : 'NO';

        $data = [
            'id_niño' => $ninoId,
            'fecha_29_dias' => $fecha29Dias->format('Y-m-d'),
            'fecha_tam_neo' => $fechaTamizaje->format('Y-m-d'),
            'edad_tam_neo' => $edadTamizaje,
            'galen_fecha_tam_feo' => $this->parseDate($row['galen_fecha'] ?? null)?->format('Y-m-d') ?? $fechaTamizaje->copy()->addDays(5)->format('Y-m-d'),
            'galen_dias_tam_feo' => $row['galen_dias'] ?? rand(30, 35),
            'cumple_tam_neo' => $cumpleTamizaje, // Calculado automáticamente
        ];

        if ($existe) {
            TamizajeNeonatal::where('id_niño', $ninoId)->update($data);
        } else {
            TamizajeNeonatal::create($data);
        }

        $this->stats['tamizajes']++;
        $this->success[] = "Tamizaje importado para niño ID: {$ninoId}";
    }

    protected function importVacuna($ninoId, $row)
    {
        $existe = VacunaRn::where('id_niño', $ninoId)->exists();

        if ($existe && empty($row['sobrescribir'])) {
            $this->errors[] = "Vacunas ya existen para niño ID: {$ninoId}";
            return;
        }

        $nino = Nino::find($ninoId);
        $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
        
        $fechaBCG = $this->parseDate($row['fecha_bcg'] ?? null);
        $fechaHVB = $this->parseDate($row['fecha_hvb'] ?? null);
        
        if (!$fechaBCG || !$fechaHVB) {
            $this->errors[] = "Fechas de vacunas BCG y HVB requeridas para niño ID: {$ninoId}";
            return;
        }
        
        $edadBCG = $fechaNacimiento->diffInDays($fechaBCG);
        $edadHVB = $fechaNacimiento->diffInDays($fechaHVB);
        
        // Determinar estado: deben aplicarse en los primeros 2 días
        $estadoBCG = ($edadBCG >= 0 && $edadBCG <= 2) ? 'SI' : 'NO';
        $estadoHVB = ($edadHVB >= 0 && $edadHVB <= 2) ? 'SI' : 'NO';
        $cumpleVacunas = ($estadoBCG === 'SI' && $estadoHVB === 'SI') ? 'SI' : 'NO';

        $data = [
            'id_niño' => $ninoId,
            'fecha_bcg' => $fechaBCG->format('Y-m-d'),
            'edad_bcg' => $edadBCG,
            'estado_bcg' => $estadoBCG, // Calculado automáticamente
            'fecha_hvb' => $fechaHVB->format('Y-m-d'),
            'edad_hvb' => $edadHVB,
            'estado_hvb' => $estadoHVB, // Calculado automáticamente
            'cumple_BCG_HVB' => $cumpleVacunas, // Calculado automáticamente
        ];

        if ($existe) {
            VacunaRn::where('id_niño', $ninoId)->update($data);
        } else {
            VacunaRn::create($data);
        }

        $this->stats['vacunas']++;
        $this->success[] = "Vacunas importadas para niño ID: {$ninoId}";
    }

    protected function importVisita($ninoId, $row)
    {
        $fechaVisita = $this->parseDate($row['fecha_visita'] ?? null);
        if (!$fechaVisita) {
            $this->errors[] = "Fecha de visita requerida para niño ID: {$ninoId}";
            return;
        }

        // Mapear período de visita
        $periodo = $row['periodo'] ?? $row['grupo_visita'] ?? null;
        $periodoMap = [
            'A' => '28 días', '28D' => '28 días', '28 DÍAS' => '28 días', '28 DIAS' => '28 días', '28 días' => '28 días',
            'B' => '2-5 meses', '2-5M' => '2-5 meses', '2-5 MESES' => '2-5 meses', '2-5 MES' => '2-5 meses', '2-5 meses' => '2-5 meses',
            'C' => '6-8 meses', '6-8M' => '6-8 meses', '6-8 MESES' => '6-8 meses', '6-8 MES' => '6-8 meses', '6-8 meses' => '6-8 meses',
            'D' => '9-11 meses', '9-11M' => '9-11 meses', '9-11 MESES' => '9-11 meses', '9-11 MES' => '9-11 meses', '9-11 meses' => '9-11 meses',
        ];
        $periodoFinal = $periodoMap[strtolower($periodo ?? '')] ?? '28 días';

        // Mapear grupo de visita a código de una letra (A, B, C, D) para compatibilidad
        $grupoVisita = strtoupper(trim($row['grupo_visita'] ?? 'A'));
        $grupoMap = [
            'A' => 'A', '28D' => 'A', '28 DÍAS' => 'A', '28 DIAS' => 'A', '28 días' => 'A',
            'B' => 'B', '2-5M' => 'B', '2-5 MESES' => 'B', '2-5 MES' => 'B', '2-5 meses' => 'B',
            'C' => 'C', '6-8M' => 'C', '6-8 MESES' => 'C', '6-8 MES' => 'C', '6-8 meses' => 'C',
            'D' => 'D', '9-11M' => 'D', '9-11 MESES' => 'D', '9-11 MES' => 'D', '9-11 meses' => 'D',
        ];
        $grupoVisitaCodigo = $grupoMap[$grupoVisita] ?? 'A';

        // Calcular edad en días al momento de la visita
        $nino = Nino::find($ninoId);
        $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
        $edadVisita = $fechaNacimiento->diffInDays($fechaVisita);
        
        // Determinar estado basándose en rangos de visitas
        $rangosVisitas = [
            'A' => ['min' => 28, 'max' => 28],      // 28 días exactos
            'B' => ['min' => 60, 'max' => 150],     // 2-5 meses
            'C' => ['min' => 180, 'max' => 240],    // 6-8 meses
            'D' => ['min' => 270, 'max' => 330],   // 9-11 meses
        ];
        
        $rango = $rangosVisitas[$grupoVisitaCodigo] ?? ['min' => 0, 'max' => 365];
        $estadoVisita = 'SEGUIMIENTO'; // Por defecto
        
        if ($edadVisita !== null) {
            if ($edadVisita >= $rango['min'] && $edadVisita <= $rango['max']) {
                $estadoVisita = 'CUMPLE';
            } elseif ($edadVisita > $rango['max']) {
                $estadoVisita = 'NO CUMPLE';
            }
        }

        // Verificar si ya existe una visita para este período
        $existe = VisitaDomiciliaria::where('id_niño', $ninoId)
                                   ->where('grupo_visita', $grupoVisitaCodigo)
                                   ->exists();

        if ($existe && empty($row['sobrescribir'])) {
            $this->errors[] = "Visita grupo {$grupoVisitaCodigo} ya existe para niño ID: {$ninoId}";
            return;
        }

        $data = [
            'id_niño' => $ninoId,
            'grupo_visita' => $grupoVisitaCodigo,
            'fecha_visita' => $fechaVisita->format('Y-m-d'),
            'numero_visitas' => (int)($row['numero_visita'] ?? $row['numero_visitas'] ?? 1),
        ];

        if ($existe) {
            VisitaDomiciliaria::where('id_niño', $ninoId)
                             ->where('grupo_visita', $grupoVisitaCodigo)
                             ->update($data);
        } else {
            VisitaDomiciliaria::create($data);
        }

        $this->stats['visitas']++;
        $this->success[] = "Visita {$periodoFinal} importada para niño ID: {$ninoId}";
    }

    protected function importDatosExtra($ninoId, $row)
    {
        $existe = DatosExtra::where('id_niño', $ninoId)->exists();

        $data = [
            'id_niño' => $ninoId,
            'red' => $row['red'] ?? null,
            'microred' => $row['microred'] ?? null,
            'eess_nacimiento' => $row['eess_nacimiento'] ?? null,
            'distrito' => $row['distrito'] ?? null,
            'provincia' => $row['provincia'] ?? null,
            'departamento' => $row['departamento'] ?? null,
            'seguro' => $row['seguro'] ?? null,
            'programa' => $row['programa'] ?? null,
        ];

        if ($existe) {
            DatosExtra::where('id_niño', $ninoId)->update($data);
        } else {
            DatosExtra::create($data);
        }

        $this->stats['datos_extra']++;
        $this->success[] = "Datos extra importados para niño ID: {$ninoId}";
    }

    protected function importRecienNacido($ninoId, $row)
    {
        $existe = RecienNacido::where('id_niño', $ninoId)->exists();

        // Convertir peso de gramos a kg si viene en gramos (valores > 10 probablemente son gramos)
        // Puede venir como 'peso' o 'peso_rn' o 'peso_nacer'
        $peso = $row['peso_nacer'] ?? $row['peso_rn'] ?? $row['peso'] ?? null;
        if ($peso && is_numeric($peso) && $peso > 10) {
            $peso = (float)$peso / 1000; // Convertir gramos a kg
        } elseif ($peso && is_numeric($peso)) {
            $peso = (float)$peso;
        }
        
        $edadGestacional = $row['edad_gestacional'] ?? null;
        $clasificacion = $row['clasificacion'] ?? null;
        
        // Validar que todos los campos estén presentes
        $camposFaltantes = [];
        if (empty($peso)) $camposFaltantes[] = 'Peso al Nacer';
        if (empty($edadGestacional)) $camposFaltantes[] = 'Edad Gestacional';
        if (empty($clasificacion)) $camposFaltantes[] = 'Clasificación';
        
        if (!empty($camposFaltantes)) {
            $this->errors[] = "CNV incompleto para niño ID: {$ninoId}. Faltan: " . implode(', ', $camposFaltantes);
            return;
        }

        // Validar clasificación (solo Normal o Bajo Peso al Nacer y/o Prematuro)
        $clasificacionesValidas = ['Normal', 'Bajo Peso al Nacer y/o Prematuro'];
        if (!in_array($clasificacion, $clasificacionesValidas)) {
            $this->errors[] = "Clasificación inválida para niño ID: {$ninoId}. Debe ser 'Normal' o 'Bajo Peso al Nacer y/o Prematuro'";
            return;
        }

        $data = [
            'id_niño' => $ninoId,
            'peso' => $peso,
            'edad_gestacional' => (int)$edadGestacional,
            'clasificacion' => $clasificacion,
        ];

        if ($existe) {
            RecienNacido::where('id_niño', $ninoId)->update($data);
        } else {
            RecienNacido::create($data);
        }

        $this->stats['recien_nacido']++;
        $this->success[] = "CNV importado para niño ID: {$ninoId}";
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
}

