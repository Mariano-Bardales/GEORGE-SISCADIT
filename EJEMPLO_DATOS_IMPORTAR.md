# 📊 Ejemplo de Datos para Importar - SISCADIT

Este documento muestra el archivo de ejemplo completo con datos reales de tu sistema.

## 📁 Archivo Creado

**Ubicación:** `storage/app/ejemplo_controles.csv`

**Formato:** CSV (compatible con Excel)

## 👶 Niños en el Ejemplo

El archivo contiene datos para **4 niños reales** de tu base de datos:

| ID | Nombre | Edad | Documento |
|----|--------|------|-----------|
| 1 | george michael aragon davila | 4 días | 73811019 |
| 2 | mariana bardales | 4 días | 72175734 |
| 3 | Maycol joha | 2 días | 73807207 |
| 4 | Jose maria sandoval pizco | 24 días | 73811022 |

## 📋 Estructura del Archivo

### Encabezados (Primera Fila)

```
ID_NINO | TIPO_CONTROL | NUMERO_CONTROL | FECHA | ESTADO | ESTADO_CRED_ONCE | ESTADO_CRED_FINAL | FECHA_BCG | ESTADO_BCG | FECHA_HVB | ESTADO_HVB | FECHA_TAMIZAJE | FECHA_VISITA | GRUPO_VISITA | RED | MICRORED | DISTRITO | SOBRESCRIBIR
```

## 📊 Datos Completos del Archivo

### Niño ID: 1 (george michael aragon davila - 4 días)

| Tipo | Num | Fecha | Estado | Otros Campos |
|------|-----|-------|--------|-------------|
| **CRN** | 1 | 2025-11-28 | Completo | Control Recién Nacido #1 |
| **VACUNA** | - | - | - | BCG: 2025-11-28 (SI), HVB: 2025-11-27 (SI) |
| **TAMIZAJE** | - | - | - | Fecha: 2025-11-28 |
| **DATOS_EXTRA** | - | - | - | Red: Lima Norte, Microred: 01, Distrito: San Juan de Lurigancho |

### Niño ID: 2 (mariana bardales - 4 días)

| Tipo | Num | Fecha | Estado | Otros Campos |
|------|-----|-------|--------|-------------|
| **CRN** | 1 | 2025-11-26 | Completo | Control Recién Nacido #1 |
| **VACUNA** | - | - | - | BCG: 2025-11-26 (SI), HVB: 2025-11-26 (SI) |
| **TAMIZAJE** | - | - | - | Fecha: 2025-11-28 |
| **DATOS_EXTRA** | - | - | - | Red: Lima Norte, Microred: 01, Distrito: San Juan de Lurigancho |

### Niño ID: 3 (Maycol joha - 2 días)

| Tipo | Num | Fecha | Estado | Otros Campos |
|------|-----|-------|--------|-------------|
| **CRN** | 1 | 2025-11-28 | Completo | Control Recién Nacido #1 |
| **VACUNA** | - | - | - | BCG: 2025-11-26 (SI), HVB: 2025-11-28 (SI) |
| **TAMIZAJE** | - | - | - | Fecha: 2025-11-27 |
| **DATOS_EXTRA** | - | - | - | Red: Lima Norte, Microred: 01, Distrito: San Juan de Lurigancho |

### Niño ID: 4 (Jose maria sandoval pizco - 24 días)

| Tipo | Num | Fecha | Estado | Otros Campos |
|------|-----|-------|--------|-------------|
| **CRN** | 1 | 2025-11-06 | Completo | Control Recién Nacido #1 |
| **CRN** | 2 | 2025-11-14 | Completo | Control Recién Nacido #2 |
| **CRN** | 3 | 2025-11-19 | Completo | Control Recién Nacido #3 |
| **CRN** | 4 | 2025-11-26 | Completo | Control Recién Nacido #4 |
| **VACUNA** | - | - | - | BCG: 2025-11-08 (SI), HVB: 2025-11-07 (SI) |
| **TAMIZAJE** | - | - | - | Fecha: 2025-11-18 |
| **DATOS_EXTRA** | - | - | - | Red: Lima Norte, Microred: 01, Distrito: San Juan de Lurigancho |

## 📝 Formato CSV Completo

```csv
ID_NINO,TIPO_CONTROL,NUMERO_CONTROL,FECHA,ESTADO,ESTADO_CRED_ONCE,ESTADO_CRED_FINAL,FECHA_BCG,ESTADO_BCG,FECHA_HVB,ESTADO_HVB,FECHA_TAMIZAJE,FECHA_VISITA,GRUPO_VISITA,RED,MICRORED,DISTRITO,SOBRESCRIBIR
1,CRN,1,2025-11-28,Completo,,,,,,,,,,,,
1,VACUNA,,,,,,2025-11-28,SI,2025-11-27,SI,,,,,,
1,TAMIZAJE,,,,,,,,,,2025-11-28,,,,,,
1,DATOS_EXTRA,,,,,,,,,,,,,"Red de Salud Lima Norte","Microred 01","San Juan de Lurigancho",
2,CRN,1,2025-11-26,Completo,,,,,,,,,,,,
2,VACUNA,,,,,,2025-11-26,SI,2025-11-26,SI,,,,,,
2,TAMIZAJE,,,,,,,,,,2025-11-28,,,,,,
2,DATOS_EXTRA,,,,,,,,,,,,,"Red de Salud Lima Norte","Microred 01","San Juan de Lurigancho",
3,CRN,1,2025-11-28,Completo,,,,,,,,,,,,
3,VACUNA,,,,,,2025-11-26,SI,2025-11-28,SI,,,,,,
3,TAMIZAJE,,,,,,,,,,2025-11-27,,,,,,
3,DATOS_EXTRA,,,,,,,,,,,,,"Red de Salud Lima Norte","Microred 01","San Juan de Lurigancho",
4,CRN,1,2025-11-06,Completo,,,,,,,,,,,,
4,CRN,2,2025-11-14,Completo,,,,,,,,,,,,
4,CRN,3,2025-11-19,Completo,,,,,,,,,,,,
4,CRN,4,2025-11-26,Completo,,,,,,,,,,,,
4,VACUNA,,,,,,2025-11-08,SI,2025-11-07,SI,,,,,,
4,TAMIZAJE,,,,,,,,,,2025-11-18,,,,,,
4,DATOS_EXTRA,,,,,,,,,,,,,"Red de Salud Lima Norte","Microred 01","San Juan de Lurigancho",
```

## 🎯 Explicación de los Tipos de Control

### CRN (Control Recién Nacido)
- **NUMERO_CONTROL**: 1, 2, 3, o 4
- **FECHA**: Fecha del control (formato: YYYY-MM-DD)
- **ESTADO**: "Completo", "Pendiente", etc.

### VACUNA
- **FECHA_BCG**: Fecha de vacuna BCG
- **ESTADO_BCG**: "SI" o "NO"
- **FECHA_HVB**: Fecha de vacuna HVB
- **ESTADO_HVB**: "SI" o "NO"

### TAMIZAJE
- **FECHA_TAMIZAJE**: Fecha del tamizaje neonatal

### DATOS_EXTRA
- **RED**: Nombre de la red de salud
- **MICRORED**: Nombre de la microred
- **DISTRITO**: Nombre del distrito

## ✅ Características del Archivo

- ✅ **IDs reales** de tu base de datos
- ✅ **Fechas coherentes** con la edad de cada niño
- ✅ **Controles apropiados** según la edad (CRN para recién nacidos)
- ✅ **Formato correcto** para importación
- ✅ **Listo para usar** sin modificaciones

## 🚀 Cómo Importar

1. **Opción Web:**
   - Ve a `/importar-controles`
   - Selecciona: `storage/app/ejemplo_controles.csv`
   - Haz clic en "Importar Controles"

2. **Opción Terminal:**
   ```bash
   php artisan controles:import-excel storage/app/ejemplo_controles.csv
   ```

## 📍 Ubicación del Archivo

El archivo está en:
```
C:\xampp\htdocs\GEORGE-SISCADIT\storage\app\ejemplo_controles.csv
```

O desde la raíz del proyecto:
```
storage/app/ejemplo_controles.csv
```

## 🔄 Regenerar el Archivo

Si necesitas actualizar el archivo con datos más recientes:

```bash
php crear_ejemplo_excel.php
```

Esto regenerará el archivo con los datos actuales de tu base de datos.

