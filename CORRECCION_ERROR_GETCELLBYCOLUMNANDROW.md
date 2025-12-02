# ✅ Corrección: Error `getCellByColumnAndRow()`

## ❌ Error Encontrado

```
Call to undefined method PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::getCellByColumnAndRow()
```

## 🔍 Causa del Error

PhpSpreadsheet **NO tiene** el método `getCellByColumnAndRow()` que existía en PHPExcel.

En PhpSpreadsheet, para obtener una celda se usa:
- `$sheet->getCell('A1')` - Con coordenada como string
- O convertir el índice numérico a letra de columna

## ✅ Solución Aplicada

### Antes (Incorrecto):
```php
$cell = $sheet->getCellByColumnAndRow($col, $row); // ❌ No existe en PhpSpreadsheet
```

### Ahora (Correcto):
```php
// Convertir índice de columna a letra (A, B, C, etc.)
$columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
$cell = $sheet->getCell($columnLetter . $row); // ✅ Correcto
```

## 📝 Código Corregido

```php
protected function sheetToArrayPhpSpreadsheet($sheet)
{
    $rows = [];
    
    // Obtener el rango de datos
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
    
    // Leer encabezados (primera fila)
    $headers = [];
    for ($col = 1; $col <= $highestColumnIndex; $col++) {
        // Convertir índice de columna a letra (A, B, C, etc.)
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $cell = $sheet->getCell($columnLetter . '1');
        $cellValue = $cell->getCalculatedValue() ?? $cell->getValue();
        $headers[] = $this->normalizeHeader($cellValue);
    }
    
    // Leer datos (desde la fila 2)
    for ($row = 2; $row <= $highestRow; $row++) {
        $rowData = [];
        $isEmpty = true;
        
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            // Convertir índice de columna a letra
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $cell = $sheet->getCell($columnLetter . $row);
            $cellValue = $cell->getCalculatedValue() ?? $cell->getValue();
            
            if (is_string($cellValue)) {
                $cellValue = trim($cellValue);
            }
            if ($cellValue !== null && $cellValue !== '') {
                $isEmpty = false;
            }
            $rowData[$headers[$col - 1]] = $cellValue;
        }
        
        if (!$isEmpty) {
            $rows[] = $rowData;
        }
    }
    
    return $rows;
}
```

## 🔑 Diferencias Clave

| Aspecto | PHPExcel (Antiguo) | PhpSpreadsheet (Nuevo) |
|---------|-------------------|------------------------|
| **Método** | `getCellByColumnAndRow($col, $row)` | `getCell($columnLetter . $row)` |
| **Índice columna** | Base 0 | Base 1 |
| **Coordenadas** | Numéricas | String ('A1', 'B2', etc.) |
| **Conversión** | No necesaria | `Coordinate::stringFromColumnIndex()` |

## ✅ Resultado

Ahora el código:
- ✅ Usa la sintaxis correcta de PhpSpreadsheet
- ✅ Convierte índices numéricos a letras de columna
- ✅ Lee correctamente todas las celdas
- ✅ Funciona perfectamente en PHP 8

---

**Última actualización:** Diciembre 2024

