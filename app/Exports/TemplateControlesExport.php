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
        return [
            ['1', 'CRED', '1', '2024-01-15', 'Completo', 'Adecuado', 'Normal', '', '', '', '', '', '', '', 'Red Lima Norte', 'Microred 01', 'San Juan de Lurigancho', ''],
            ['1', 'CRED', '2', '2024-02-15', 'Completo', 'Adecuado', 'Normal', '', '', '', '', '', '', '', '', '', '', ''],
            ['1', 'VACUNA', '', '', '', '', '', '2024-01-01', 'SI', '2024-01-01', 'SI', '', '', '', '', '', '', ''],
            ['1', 'TAMIZAJE', '', '', '', '', '', '', '', '', '', '2024-01-10', '', '', '', '', '', ''],
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
            'FECHA_BCG',
            'ESTADO_BCG',
            'FECHA_HVB',
            'ESTADO_HVB',
            'FECHA_TAMIZAJE',
            'FECHA_VISITA',
            'GRUPO_VISITA',
            'RED',
            'MICRORED',
            'DISTRITO',
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
            'A' => 12,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 12,
            'F' => 18,
            'G' => 18,
            'H' => 15,
            'I' => 12,
            'J' => 15,
            'K' => 12,
            'L' => 15,
            'M' => 15,
            'N' => 15,
            'O' => 20,
            'P' => 15,
            'Q' => 20,
            'R' => 15,
        ];
    }
}

