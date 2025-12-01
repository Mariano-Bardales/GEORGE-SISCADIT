# 📊 Archivo de Ejemplo para Importación

He creado un archivo de ejemplo con datos reales de tu sistema que puedes usar para probar la importación.

## 📁 Archivo Creado

**Ubicación:** `storage/app/ejemplo_controles.csv`

Este archivo contiene **19 registros** de controles para los 4 niños que tienes en tu base de datos:

1. **george michael aragon davila** (ID: 1, Edad: 4 días)
2. **mariana bardales** (ID: 2, Edad: 4 días)
3. **Maycol joha** (ID: 3, Edad: 2 días)
4. **Jose maria sandoval pizco** (ID: 4, Edad: 24 días)

## 📋 Contenido del Archivo

El archivo incluye:

### Para cada niño (según su edad):

- ✅ **Controles RN (CRN 1-4)** - Para recién nacidos (0-28 días)
  - CRN 1: 2-6 días
  - CRN 2: 7-13 días
  - CRN 3: 14-20 días
  - CRN 4: 21-28 días

- ✅ **Vacunas RN** - BCG y HVB con fechas realistas

- ✅ **Tamizaje Neonatal** - Fecha de tamizaje

- ✅ **Datos Extra** - Red, Microred, Distrito

## 🚀 Cómo Usar el Archivo

### Opción 1: Usar el CSV directamente

1. Ve a `/importar-controles` en tu aplicación
2. Selecciona el archivo: `storage/app/ejemplo_controles.csv`
3. Haz clic en "Importar Controles"
4. Verifica los resultados

### Opción 2: Convertir a Excel

1. Abre `storage/app/ejemplo_controles.csv` en Excel
2. Guarda como `.xlsx` (Archivo > Guardar como > Excel Workbook)
3. Sube el archivo `.xlsx` desde `/importar-controles`

### Opción 3: Usar desde línea de comandos

```bash
php artisan controles:import-excel storage/app/ejemplo_controles.csv
```

## ✅ Verificación

Después de importar, verifica que los datos se muestren correctamente:

1. Ve a **Controles CRED** en el menú
2. Busca los niños por nombre o ID
3. Haz clic en **"Ver Controles"** para cada niño
4. Deberías ver:
   - Controles RN (si el niño tiene menos de 28 días)
   - Vacunas registradas
   - Tamizaje (si aplica)
   - Datos extra

## 📊 Estructura del Archivo

El archivo tiene las siguientes columnas:

| Columna | Descripción | Ejemplo |
|---------|-------------|---------|
| ID_NINO | ID del niño en la BD | 1, 2, 3, 4 |
| TIPO_CONTROL | Tipo de control | CRN, VACUNA, TAMIZAJE, DATOS_EXTRA |
| NUMERO_CONTROL | Número (1-4 para CRN) | 1, 2, 3, 4 |
| FECHA | Fecha del control | 2025-11-27 |
| ESTADO | Estado del control | Completo |
| FECHA_BCG | Fecha vacuna BCG | 2025-11-26 |
| ESTADO_BCG | Estado BCG | SI |
| FECHA_HVB | Fecha vacuna HVB | 2025-11-25 |
| ESTADO_HVB | Estado HVB | SI |
| FECHA_TAMIZAJE | Fecha tamizaje | 2025-11-26 |
| RED | Red de salud | Red de Salud Lima Norte |
| MICRORED | Microred | Microred 01 |
| DISTRITO | Distrito | San Juan de Lurigancho |

## 🎯 Datos Específicos por Niño

### Niño ID: 1 (4 días)
- CRN 1
- Vacunas BCG y HVB
- Tamizaje
- Datos extra

### Niño ID: 2 (4 días)
- CRN 1
- Vacunas BCG y HVB
- Tamizaje
- Datos extra

### Niño ID: 3 (2 días)
- CRN 1
- Vacunas BCG y HVB
- Tamizaje
- Datos extra

### Niño ID: 4 (24 días)
- CRN 1, 2, 3, 4 (todos los controles)
- Vacunas BCG y HVB
- Tamizaje
- Datos extra

## ⚠️ Notas Importantes

- ✅ Todos los IDs de niños son **reales** de tu base de datos
- ✅ Las fechas son **coherentes** con la edad de cada niño
- ✅ Los controles se crean **solo si corresponden** a la edad del niño
- ✅ El archivo está listo para importar **sin modificaciones**

## 🔄 Regenerar el Archivo

Si necesitas regenerar el archivo con datos actualizados:

```bash
php crear_ejemplo_excel.php
```

Esto creará un nuevo archivo `ejemplo_controles.csv` con los datos más recientes de tu base de datos.



