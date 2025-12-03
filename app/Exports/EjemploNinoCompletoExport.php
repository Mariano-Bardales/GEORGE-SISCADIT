<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class EjemploNinoCompletoExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Fecha de nacimiento: hace 120 días (4 meses aproximadamente)
        $fechaNacimiento = Carbon::now()->subDays(120);
        $fechaNacimientoStr = $fechaNacimiento->format('Y-m-d');
        
        $data = [];
        
        // ========== FILA 1: DATOS DEL NIÑO (NINO) ==========
        // IMPORTANTE: Solo se especifica FECHA_NACIMIENTO, el sistema calculará edad y estado
        $data[] = [
            '', // ID_NINO (vacío para crear nuevo)
            'NINO', // TIPO_CONTROL
            '', // NUMERO_CONTROL
            '', // FECHA
            '', // ESTADO (se calculará automáticamente)
            '', // ESTADO_CRED_ONCE
            '', // ESTADO_CRED_FINAL
            '', // PESO
            '', // TALLA
            '', // PERIMETRO_CEFALICO
            '', // FECHA_BCG
            '', // ESTADO_BCG
            '', // FECHA_HVB
            '', // ESTADO_HVB
            '', // FECHA_TAMIZAJE
            '', // FECHA_VISITA
            '', // PERIODO
            '', // GRUPO_VISITA
            '', // RED
            '', // MICRORED
            '', // DISTRITO
            '', // PROVINCIA
            '', // DEPARTAMENTO
            '', // SEGURO
            '', // PROGRAMA
            '', // PESO_RN
            '', // EDAD_GESTACIONAL
            '', // CLASIFICACION
            '73807207', // NUMERO_DOCUMENTO
            'DNI', // TIPO_DOCUMENTO
            'Nayely Pérez García', // APELLIDOS_NOMBRES
            $fechaNacimientoStr, // FECHA_NACIMIENTO ⭐ IMPORTANTE: El sistema calculará edad desde aquí
            'F', // GENERO
            'SANTA ROSA DE AGUAYTIA', // ESTABLECIMIENTO
            '', // DNI_MADRE
            '', // APELLIDOS_NOMBRES_MADRE
            '', // CELULAR_MADRE
            '', // DOMICILIO_MADRE
            '', // REFERENCIA_DIRECCION
            '' // SOBRESCRIBIR
        ];
        
        // ========== FILA 2: DATOS DE LA MADRE (MADRE) ==========
        $data[] = [
            '1', // ID_NINO (se asignará después de crear el niño)
            'MADRE', // TIPO_CONTROL
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
        ];
        // Llenar campos de madre
        $data[1][34] = '12345678'; // DNI_MADRE
        $data[1][35] = 'María García López'; // APELLIDOS_NOMBRES_MADRE
        $data[1][36] = '987654321'; // CELULAR_MADRE
        $data[1][37] = 'Jr. Los Olivos 123'; // DOMICILIO_MADRE
        $data[1][38] = 'Frente al parque principal'; // REFERENCIA_DIRECCION
        
        // ========== FILA 3: DATOS EXTRA (DATOS_EXTRA) ==========
        $data[] = [
            '1', // ID_NINO
            'DATOS_EXTRA', // TIPO_CONTROL
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
        ];
        $data[2][18] = 'AGUAYTIA'; // RED
        $data[2][19] = 'Microred 01'; // MICRORED
        $data[2][20] = 'Aguaytía'; // DISTRITO
        $data[2][21] = 'Padre Abad'; // PROVINCIA
        $data[2][22] = 'Ucayali'; // DEPARTAMENTO
        $data[2][23] = 'SIS'; // SEGURO
        $data[2][24] = 'CRED'; // PROGRAMA
        
        // ========== FILA 4: RECIÉN NACIDO (RECIEN_NACIDO) ==========
        $data[] = [
            '1', // ID_NINO
            'RECIEN_NACIDO', // TIPO_CONTROL
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
        ];
        $data[3][25] = '3500'; // PESO_RN (gramos)
        $data[3][26] = '38'; // EDAD_GESTACIONAL (semanas)
        $data[3][27] = 'Normal'; // CLASIFICACION
        
        // ========== FILA 5: VACUNA BCG y HVB (VACUNA) ==========
        // BCG y HVB deben aplicarse en los primeros 2 días
        $fechaVacuna = $fechaNacimiento->copy()->addDay(1); // Día 1 después del nacimiento
        $data[] = [
            '1', // ID_NINO
            'VACUNA', // TIPO_CONTROL
            '', // NUMERO_CONTROL
            '', // FECHA
            '', // ESTADO (se calculará automáticamente)
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
        ];
        $data[4][10] = $fechaVacuna->format('Y-m-d'); // FECHA_BCG ⭐ Solo fecha, sistema calculará edad y estado
        $data[4][12] = $fechaVacuna->format('Y-m-d'); // FECHA_HVB ⭐ Solo fecha, sistema calculará edad y estado
        
        // ========== FILA 6: TAMIZAJE NEONATAL (TAMIZAJE) ==========
        // Tamizaje debe realizarse antes de los 29 días
        $fechaTamizaje = $fechaNacimiento->copy()->addDays(5); // Día 5 después del nacimiento
        $data[] = [
            '1', // ID_NINO
            'TAMIZAJE', // TIPO_CONTROL
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
        ];
        $data[5][14] = $fechaTamizaje->format('Y-m-d'); // FECHA_TAMIZAJE ⭐ Solo fecha, sistema calculará edad y estado
        
        // ========== FILAS 7-10: CONTROLES RECIÉN NACIDO (CRN) ==========
        // 4 controles RN: días 3, 8, 15, 25
        $controlesRN = [
            ['numero' => 1, 'dias' => 3],
            ['numero' => 2, 'dias' => 8],
            ['numero' => 3, 'dias' => 15],
            ['numero' => 4, 'dias' => 25]
        ];
        
        foreach ($controlesRN as $control) {
            $fechaControl = $fechaNacimiento->copy()->addDays($control['dias']);
            $data[] = [
                '1', // ID_NINO
                'CRN', // TIPO_CONTROL
                $control['numero'], // NUMERO_CONTROL
                $fechaControl->format('Y-m-d'), // FECHA ⭐ Solo fecha, sistema calculará edad y estado
                '', // ESTADO (se calculará automáticamente basándose en la fecha de nacimiento y fecha del control)
                '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
            ];
        }
        
        // ========== FILAS 11-21: CONTROLES CRED (11 controles) ==========
        // Controles CRED: días 30, 60, 90, 120, 150, 180, 210, 240, 270, 300, 330
        $controlesCRED = [
            ['numero' => 1, 'dias' => 30],
            ['numero' => 2, 'dias' => 60],
            ['numero' => 3, 'dias' => 90],
            ['numero' => 4, 'dias' => 120],
            ['numero' => 5, 'dias' => 150],
            ['numero' => 6, 'dias' => 180],
            ['numero' => 7, 'dias' => 210],
            ['numero' => 8, 'dias' => 240],
            ['numero' => 9, 'dias' => 270],
            ['numero' => 10, 'dias' => 300],
            ['numero' => 11, 'dias' => 330]
        ];
        
        foreach ($controlesCRED as $control) {
            $fechaControl = $fechaNacimiento->copy()->addDays($control['dias']);
            $data[] = [
                '1', // ID_NINO
                'CRED', // TIPO_CONTROL
                $control['numero'], // NUMERO_CONTROL
                $fechaControl->format('Y-m-d'), // FECHA ⭐ Solo fecha, sistema calculará edad y estado
                '', // ESTADO (se calculará automáticamente basándose en la fecha de nacimiento y fecha del control)
                '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
            ];
        }
        
        // ========== FILAS 22-25: VISITAS DOMICILIARIAS (4 visitas) ==========
        // Visitas: días 28, 90, 210, 300
        $visitas = [
            ['grupo' => '1', 'dias' => 28],
            ['grupo' => '2', 'dias' => 90],
            ['grupo' => '3', 'dias' => 210],
            ['grupo' => '4', 'dias' => 300]
        ];
        
        foreach ($visitas as $visita) {
            $fechaVisita = $fechaNacimiento->copy()->addDays($visita['dias']);
            $data[] = [
                '1', // ID_NINO
                'VISITA', // TIPO_CONTROL
                '', // NUMERO_CONTROL
                '', // FECHA
                '', // ESTADO (se calculará automáticamente)
                '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
            ];
            $data[count($data) - 1][15] = $fechaVisita->format('Y-m-d'); // FECHA_VISITA ⭐ Solo fecha, sistema calculará edad y estado
            $data[count($data) - 1][17] = $visita['grupo']; // GRUPO_VISITA
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [
            'ID_NINO',
            'TIPO_CONTROL',
            'NUMERO_CONTROL',
            'FECHA',
            'ESTADO',
            'ESTADO_CRED_ONCE',
            'ESTADO_CRED_FINAL',
            'PESO',
            'TALLA',
            'PERIMETRO_CEFALICO',
            'FECHA_BCG',
            'ESTADO_BCG',
            'FECHA_HVB',
            'ESTADO_HVB',
            'FECHA_TAMIZAJE',
            'FECHA_VISITA',
            'PERIODO',
            'GRUPO_VISITA',
            'RED',
            'MICRORED',
            'DISTRITO',
            'PROVINCIA',
            'DEPARTAMENTO',
            'SEGURO',
            'PROGRAMA',
            'PESO_RN',
            'EDAD_GESTACIONAL',
            'CLASIFICACION',
            'NUMERO_DOCUMENTO',
            'TIPO_DOCUMENTO',
            'APELLIDOS_NOMBRES',
            'FECHA_NACIMIENTO',
            'GENERO',
            'ESTABLECIMIENTO',
            'DNI_MADRE',
            'APELLIDOS_NOMBRES_MADRE',
            'CELULAR_MADRE',
            'DOMICILIO_MADRE',
            'REFERENCIA_DIRECCION',
            'SOBRESCRIBIR'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ],
            // Colorear filas según tipo
            2 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F4F8']]], // NINO
            3 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF4E6']]], // MADRE
            4 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F8E8']]], // DATOS_EXTRA
            5 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5E6FF']]], // RECIEN_NACIDO
            6 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F5E8']]], // VACUNA
            7 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF0E6']]], // TAMIZAJE
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // ID_NINO
            'B' => 15,  // TIPO_CONTROL
            'C' => 15,  // NUMERO_CONTROL
            'D' => 15,  // FECHA
            'E' => 12,  // ESTADO
            'F' => 18,  // ESTADO_CRED_ONCE
            'G' => 18,  // ESTADO_CRED_FINAL
            'H' => 12,  // PESO
            'I' => 12,  // TALLA
            'J' => 18,  // PERIMETRO_CEFALICO
            'K' => 15,  // FECHA_BCG
            'L' => 12,  // ESTADO_BCG
            'M' => 15,  // FECHA_HVB
            'N' => 12,  // ESTADO_HVB
            'O' => 15,  // FECHA_TAMIZAJE
            'P' => 15,  // FECHA_VISITA
            'Q' => 15,  // PERIODO
            'R' => 15,  // GRUPO_VISITA
            'S' => 20,  // RED
            'T' => 15,  // MICRORED
            'U' => 20,  // DISTRITO
            'V' => 12,  // PROVINCIA
            'W' => 12,  // DEPARTAMENTO
            'X' => 12,  // SEGURO
            'Y' => 12,  // PROGRAMA
            'Z' => 12,  // PESO_RN
            'AA' => 15, // EDAD_GESTACIONAL
            'AB' => 20, // CLASIFICACION
            'AC' => 18, // NUMERO_DOCUMENTO
            'AD' => 15, // TIPO_DOCUMENTO
            'AE' => 25, // APELLIDOS_NOMBRES
            'AF' => 18, // FECHA_NACIMIENTO
            'AG' => 12, // GENERO
            'AH' => 25, // ESTABLECIMIENTO
            'AI' => 15, // DNI_MADRE
            'AJ' => 25, // APELLIDOS_NOMBRES_MADRE
            'AK' => 15, // CELULAR_MADRE
            'AL' => 25, // DOMICILIO_MADRE
            'AM' => 25, // REFERENCIA_DIRECCION
            'AN' => 12, // SOBRESCRIBIR
        ];
    }
}





