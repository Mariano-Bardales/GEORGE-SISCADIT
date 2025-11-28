<?php

namespace App\Imports;

use App\Models\Nino;
use App\Models\ControlRn;
use App\Models\ControlMenor1;
use App\Models\TamizajeNeonatal;
use App\Models\VacunaRn;
use App\Models\VisitaDomiciliaria;
use App\Models\DatosExtra;
use App\Models\RecienNacido;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ControlesImport implements ToCollection, WithHeadingRow
{
    protected $errors = [];
    protected $success = [];
    protected $stats = [
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

        // Procesar según el tipo de control
        $tipoControl = strtolower(trim($row['tipo_control'] ?? ''));

        switch ($tipoControl) {
            case 'crn':
            case 'control rn':
            case 'recien nacido':
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
            case 'rn':
                $this->importRecienNacido($ninoId, $row);
                break;
            
            default:
                $this->errors[] = "Tipo de control desconocido: " . ($row['tipo_control'] ?? 'N/A');
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
        $edad = $this->calculateAge($ninoId, $fecha);

        if ($existe) {
            ControlRn::where('id_niño', $ninoId)
                     ->where('numero_control', $numeroControl)
                     ->update([
                         'fecha' => $fecha,
                         'edad' => $edad,
                         'estado' => $row['estado'] ?? 'Completo',
                     ]);
        } else {
            ControlRn::create([
                'id_niño' => $ninoId,
                'numero_control' => $numeroControl,
                'fecha' => $fecha,
                'edad' => $edad,
                'estado' => $row['estado'] ?? 'Completo',
            ]);
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
        $edad = $this->calculateAge($ninoId, $fecha);

        $data = [
            'id_niño' => $ninoId,
            'numero_control' => $numeroControl,
            'fecha' => $fecha,
            'edad' => $edad,
            'estado' => $row['estado'] ?? 'Completo',
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
        $fechaTamizaje = $this->parseDate($row['fecha_tamizaje'] ?? null) ?? $fechaNacimiento->copy()->addDays(rand(1, 29));
        $edadTamizaje = $fechaNacimiento->diffInDays($fechaTamizaje);

        $data = [
            'id_niño' => $ninoId,
            'fecha_29_dias' => $fecha29Dias->format('Y-m-d'),
            'fecha_tam_neo' => $fechaTamizaje->format('Y-m-d'),
            'edad_tam_neo' => $edadTamizaje,
            'galen_fecha_tam_feo' => $this->parseDate($row['galen_fecha'] ?? null)?->format('Y-m-d') ?? $fechaTamizaje->copy()->addDays(5)->format('Y-m-d'),
            'galen_dias_tam_feo' => $row['galen_dias'] ?? rand(30, 35),
            'cumple_tam_neo' => $row['cumple_tamizaje'] ?? 'SI',
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
        
        $fechaBCG = $this->parseDate($row['fecha_bcg'] ?? null) ?? $fechaNacimiento->copy()->addDays(rand(0, 7));
        $fechaHVB = $this->parseDate($row['fecha_hvb'] ?? null) ?? $fechaNacimiento->copy()->addDays(rand(0, 7));

        $data = [
            'id_niño' => $ninoId,
            'fecha_bcg' => $fechaBCG->format('Y-m-d'),
            'edad_bcg' => $fechaNacimiento->diffInDays($fechaBCG),
            'estado_bcg' => $row['estado_bcg'] ?? 'SI',
            'fecha_hvb' => $fechaHVB->format('Y-m-d'),
            'edad_hvb' => $fechaNacimiento->diffInDays($fechaHVB),
            'estado_hvb' => $row['estado_hvb'] ?? 'SI',
            'cumple_BCG_HVB' => $row['cumple_vacunas'] ?? 'SI',
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

        VisitaDomiciliaria::create([
            'id_niño' => $ninoId,
            'grupo_visita' => $row['grupo_visita'] ?? 'Grupo A',
            'fecha_visita' => $fechaVisita->format('Y-m-d'),
            'numero_visitas' => $row['numero_visita'] ?? 1,
        ]);

        $this->stats['visitas']++;
        $this->success[] = "Visita importada para niño ID: {$ninoId}";
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

        $data = [
            'id_niño' => $ninoId,
            'peso' => $row['peso'] ?? null,
            'edad_gestacional' => $row['edad_gestacional'] ?? null,
            'clasificacion' => $row['clasificacion'] ?? 'AEG',
        ];

        if ($existe) {
            RecienNacido::where('id_niño', $ninoId)->update($data);
        } else {
            RecienNacido::create($data);
        }

        $this->stats['recien_nacido']++;
        $this->success[] = "Datos recién nacido importados para niño ID: {$ninoId}";
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

        $nino = Nino::find($ninoId);
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

