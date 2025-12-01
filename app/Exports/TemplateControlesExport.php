<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateControlesExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        $hoy = \Carbon\Carbon::now();
        $fechaNacimiento = $hoy->copy()->subDays(45);
        
        return [
            // NINO - Crear/actualizar datos del niño
            ['', 'NINO', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '73811019', 'DNI', 'Juan Pérez García', $fechaNacimiento->format('Y-m-d'), 'M', 'PREVISTO', '', '', '', '', ''],
            // MADRE - Crear/actualizar datos de la madre
            ['1', 'MADRE', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12345678', 'María García López', '987654321', 'Jr. Ejemplo 123', 'Frente al parque', ''],
            // CRED con todos los campos
            ['1', 'CRED', '1', $fechaNacimiento->copy()->addDays(45)->format('Y-m-d'), 'Completo', 'Normal', 'Normal', '3800', '53.2', '36.8', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            ['1', 'CRED', '2', $fechaNacimiento->copy()->addDays(75)->format('Y-m-d'), 'Completo', 'Normal', 'Normal', '4200', '56.5', '38.2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            // CRN con todos los campos
            ['1', 'CRN', '1', $fechaNacimiento->copy()->addDays(4)->format('Y-m-d'), 'Completo', '', '', '3200', '50.5', '35.2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            ['1', 'CRN', '2', $fechaNacimiento->copy()->addDays(10)->format('Y-m-d'), 'Completo', '', '', '3400', '51.8', '35.8', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            // Vacunas
            ['1', 'VACUNA', '', '', '', '', '', '', '', '', $fechaNacimiento->copy()->addDays(1)->format('Y-m-d'), 'SI', $fechaNacimiento->copy()->addDays(1)->format('Y-m-d'), 'SI', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            // Tamizaje
            ['1', 'TAMIZAJE', '', '', '', '', '', '', '', '', '', '', '', $fechaNacimiento->copy()->addDays(5)->format('Y-m-d'), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            // Visita
            ['1', 'VISITA', '', '', '', '', '', '', '', '', '', '', '', '', $fechaNacimiento->copy()->addDays(28)->format('Y-m-d'), '28 días', 'A', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            // Datos Extra
            ['1', 'DATOS_EXTRA', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Red de Salud Lima Norte', 'Microred 01', 'San Juan de Lurigancho', 'Lima', 'Lima', 'SIS', 'Programa CRED', '', '', '', '', '', '', '', '', '', '', ''],
            // Recién Nacido
            ['1', 'RECIEN_NACIDO', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '3500', '38', 'Normal', '', '', '', '', '', '', '', ''],
        ];
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
            // Campos para NINO
            'NUMERO_DOCUMENTO',
            'TIPO_DOCUMENTO',
            'APELLIDOS_NOMBRES',
            'FECHA_NACIMIENTO',
            'GENERO',
            'ESTABLECIMIENTO',
            // Campos para MADRE
            'DNI_MADRE',
            'APELLIDOS_NOMBRES_MADRE',
            'CELULAR_MADRE',
            'DOMICILIO_MADRE',
            'REFERENCIA_DIRECCION',
            'SOBRESCRIBIR',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']]],
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
            'Q' => 15,  // GRUPO_VISITA
            'R' => 20,  // RED
            'S' => 15,  // MICRORED
            'T' => 20,  // DISTRITO
            'U' => 12,  // PESO_RN
            'V' => 15,  // EDAD_GESTACIONAL
            'W' => 25,  // CLASIFICACION
            'X' => 15,  // SOBRESCRIBIR
        ];
    }
}

