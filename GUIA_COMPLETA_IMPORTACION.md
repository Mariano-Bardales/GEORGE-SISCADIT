# 📋 GUÍA COMPLETA DE IMPORTACIÓN - TODOS LOS DATOS EN UN SOLO ARCHIVO

## ✅ DATOS QUE PUEDES IMPORTAR EN UN SOLO ARCHIVO EXCEL

Puedes importar **TODOS** estos datos en un solo archivo Excel con múltiples hojas:

---

## 📄 HOJA 1: "Niños" (OBLIGATORIA - DEBE IR PRIMERO)

### Columnas Requeridas:

| Columna | Tipo | Requerido | Descripción | Ejemplo |
|---------|------|-----------|-------------|---------|
| `id_niño` | Número | Opcional | ID del niño (si no se proporciona, se auto-genera) | `1` |
| `establecimiento` | Texto | Opcional | Nombre del establecimiento de salud | `EESS Modelo` |
| `tipo_doc` | Texto | Opcional | Tipo de documento (DNI, CE, PASS, etc.) | `DNI` |
| `numero_doc` | Texto | Opcional | Número de documento | `10000001` |
| `apellidos_nombres` | Texto | **REQUERIDO*** | Nombre completo del niño | `Prueba 1` |
| `fecha_nacimiento` | Fecha | **REQUERIDO** | Fecha de nacimiento (DD/MM/YYYY o YYYY-MM-DD) | `05/12/2024` |
| `genero` | Texto | Opcional | Género (M o F) | `M` |

**Nota:** *Debes tener al menos `apellidos_nombres` O (`numero_doc` + `tipo_doc`)

---

## 📄 HOJA 2: "Datos Extra" o "Extra"

### Columnas Requeridas:

| Columna | Tipo | Requerido | Descripción | Ejemplo |
|---------|------|-----------|-------------|---------|
| `id_extra` | Número | Opcional | ID del registro (si no se proporciona, se auto-genera) | `1` |
| `id_niño` | Número | **REQUERIDO** | ID del niño (debe existir en la hoja "Niños") | `1` |
| `red` | Texto | Opcional | Red de salud | `CORONEL PORTILLO` |
| `microred` | Texto | Opcional | Microred | `MR1` |
| `eess_nacimiento` | Texto | Opcional | Establecimiento de salud de nacimiento | `EESS Modelo` |
| `distrito` | Texto | Opcional | Distrito | `Callería` |
| `provincia` | Texto | Opcional | Provincia | `Coronel Portillo` |
| `departamento` | Texto | Opcional | Departamento | `Ucayali` |
| `seguro` | Texto | Opcional | Tipo de seguro | `SIS` |
| `programa` | Texto | Opcional | Programa social | `Juntos` |

---

## 📄 HOJA 3: "Madre"

### Columnas Requeridas:

| Columna | Tipo | Requerido | Descripción | Ejemplo |
|---------|------|-----------|-------------|---------|
| `id_madre` | Número | Opcional | ID de la madre (si no se proporciona, se auto-genera) | `1` |
| `id_niño` | Número | **REQUERIDO** | ID del niño (debe existir en la hoja "Niños") | `1` |
| `dni` | Texto | Opcional | DNI de la madre | `140000001` |
| `apellidos_nombres` | Texto | Opcional | Nombre completo de la madre | `Madre1` |
| `celular` | Texto | Opcional | Número de celular | `987654321` |
| `domicilio` | Texto | Opcional | Dirección | `Jr. Perú 123` |
| `referencia_direccion` | Texto | Opcional | Referencia de dirección | `Jr. Los Cedros 145` |

---

## 📄 HOJA 4: "Controles RN" o "Controles RN"

### Columnas Requeridas:

| Columna | Tipo | Requerido | Descripción | Ejemplo |
|---------|------|-----------|-------------|---------|
| `id_crn` | Número | Opcional | ID del control (si no se proporciona, se auto-genera) | `1` |
| `id_niño` | Número | **REQUERIDO** | ID del niño (debe existir en la hoja "Niños") | `1` |
| `numero_control` | Número | **REQUERIDO** | Número de control (1-4) | `1` |
| `fecha` | Fecha | **REQUERIDO** | Fecha del control (DD/MM/YYYY o YYYY-MM-DD) | `08/12/2024` |

**Variaciones Aceptadas:**
- `fecha` también acepta: `fecha_control`
- `numero_control` también acepta: `nro_control`

**Nota:** El sistema calculará automáticamente la edad en días y el estado (CUMPLE/NO CUMPLE) basándose en la fecha de nacimiento del niño.

---

## 📄 HOJA 5: "Controles CRED" o "Controles CRED" o "CRED"

### Columnas Requeridas:

| Columna | Tipo | Requerido | Descripción | Ejemplo |
|---------|------|-----------|-------------|---------|
| `id_control` | Número | Opcional | ID del control (si no se proporciona, se auto-genera) | `1` |
| `id_niño` | Número | **REQUERIDO** | ID del niño (debe existir en la hoja "Niños") | `1` |
| `nro_control` | Número | **REQUERIDO** | Número de control (1-11) | `1` |
| `fecha_contro` | Fecha | **REQUERIDO** | Fecha del control (DD/MM/YYYY o YYYY-MM-DD) | `06/12/2024` |

**Variaciones Aceptadas:**
- `fecha_contro` también acepta: `fecha_control`, `fecha`
- `nro_control` también acepta: `numero_control`
- `id_control` también acepta: `id_cred`, `idcred`, `idcontrol`, `id`

**Nota:** El sistema calculará automáticamente la edad en días y el estado (CUMPLE/NO CUMPLE) basándose en la fecha de nacimiento del niño.

**⚠️ IMPORTANTE:** Los Controles CRED NO incluyen peso, talla ni perimetro_cefalico.

---

## 📄 HOJA 6: "Tamizaje" o "Tamisaje"

### Columnas Requeridas:

| Columna | Tipo | Requerido | Descripción | Ejemplo |
|---------|------|-----------|-------------|---------|
| `id_tamizaje` | Número | Opcional | ID del tamizaje (si no se proporciona, se auto-genera) | `1` |
| `id_niño` | Número | **REQUERIDO** | ID del niño (debe existir en la hoja "Niños") | `1` |
| `numero_control` | Número | Opcional | Número de control | `1` |
| `fecha_tam_neo` | Fecha | **REQUERIDO** | Fecha del tamizaje neonatal (DD/MM/YYYY o YYYY-MM-DD) | `03/12/2024` |
| `galen_fecha_tam_feo` | Fecha | Opcional | Fecha de tamizaje Galen (DD/MM/YYYY o YYYY-MM-DD) | `03/12/2024` |

**Variaciones Aceptadas:**
- `fecha_tam_neo` también acepta: `fecha_tamizaje`
- `galen_fecha_tam_feo` también acepta: `galen_fecha`

**Nota:** El sistema calculará automáticamente la edad en días y si cumple (debe realizarse antes de los 29 días). Estos campos se mostrarán en los cuadros de la interfaz.

---

## 📄 HOJA 7: "Vacunas" o "Vacuna" o "Vacuna RN"

### Columnas Requeridas:

| Columna | Tipo | Requerido | Descripción | Ejemplo |
|---------|------|-----------|-------------|---------|
| `id_vacuna` | Número | Opcional | ID de la vacuna (si no se proporciona, se auto-genera) | `1` |
| `id_niño` | Número | **REQUERIDO** | ID del niño (debe existir en la hoja "Niños") | `1` |
| `numero_control` | Número | Opcional | Número de control | `1` |
| `fecha_bcg` | Fecha | **REQUERIDO** | Fecha de aplicación de BCG (DD/MM/YYYY o YYYY-MM-DD) | `06/12/2024` |
| `fecha_hvb` | Fecha | **REQUERIDO** | Fecha de aplicación de HVB (DD/MM/YYYY o YYYY-MM-DD) | `08/12/2024` |

**Nota:** El sistema calculará automáticamente la edad en días y el estado (deben aplicarse en los primeros 2 días). Estos campos se mostrarán en los cuadros de la interfaz.

---

## 📄 HOJA 8: "Visitas" o "Visita"

### Columnas Requeridas:

| Columna | Tipo | Requerido | Descripción | Ejemplo |
|---------|------|-----------|-------------|---------|
| `id_visita` | Número | Opcional | ID de la visita (si no se proporciona, se auto-genera) | `1` |
| `id_niño` | Número | **REQUERIDO** | ID del niño (debe existir en la hoja "Niños") | `1` |
| `numero_control` | Número | Opcional | Número de control | `1` |
| `fecha_visita` | Fecha | **REQUERIDO** | Fecha de la visita (DD/MM/YYYY o YYYY-MM-DD) | `02/01/2025` |
| `grupo_visita` | Texto | Opcional | Grupo de visita (A, B, C, D) | `A` |

**Variaciones Aceptadas:**
- `fecha_visita` también acepta: `fecha`
- `grupo_visita` también acepta: `periodo`, `grupo`

**Nota:** El sistema calculará automáticamente la edad en días de la visita.

---

## 📄 HOJA 9: "Recien Nacido" o "Recién Nacido" o "CNV"

### Columnas Requeridas:

| Columna | Tipo | Requerido | Descripción | Ejemplo |
|---------|------|-----------|-------------|---------|
| `id_rn` | Número | Opcional | ID del recién nacido (si no se proporciona, se auto-genera) | `1` |
| `id_niño` | Número | **REQUERIDO** | ID del niño (debe existir en la hoja "Niños") | `1` |
| `peso` | Número entero | Opcional | Peso al nacer en gramos (valores de 500 a 5000+ gramos) | `3200` |
| `edad_gestacional` | Número | Opcional | Edad gestacional en semanas | `38` |
| `clasificacion` | Texto | Opcional | Clasificación | `9 Normal` |

**Nota sobre Peso:** El campo `peso` acepta valores enteros en gramos. Ejemplos: 2500 (2.5 kg), 3200 (3.2 kg), 4000 (4.0 kg). El sistema puede almacenar valores de hasta 32,767 gramos (32.7 kg).

---

## 📊 RESUMEN: QUÉ PUEDES IMPORTAR EN UN SOLO ARCHIVO

### ✅ Datos que SÍ puedes importar:

1. ✅ **Niños** - Datos básicos del niño
2. ✅ **Datos Extra** - Información adicional del niño (red, distrito, seguro, etc.)
3. ✅ **Madre** - Datos de la madre del niño
4. ✅ **Controles RN** - Controles de Recién Nacido (1-4 controles)
5. ✅ **Controles CRED** - Controles CRED mensuales (1-11 controles)
6. ✅ **Tamizaje** - Tamizaje neonatal
7. ✅ **Vacunas** - Vacunas BCG y HVB
8. ✅ **Visitas** - Visitas domiciliarias
9. ✅ **Recien Nacido** - Datos del CNV (Carné de Nacido Vivo)

### ❌ Datos que NO se importan (se calculan automáticamente):

- ❌ `edad` - Se calcula automáticamente
- ❌ `estado` - Se calcula automáticamente (CUMPLE/NO CUMPLE/SEGUIMIENTO)
- ❌ `edad_meses` - No existe en la tabla
- ❌ `edad_dias` - No existe en la tabla (solo se usa para cálculos internos)
- ❌ `peso`, `talla`, `perimetro_cefalico` en Controles CRED - No existen en la tabla

---

## 📝 EJEMPLO DE ESTRUCTURA COMPLETA DEL ARCHIVO EXCEL

Tu archivo Excel debe tener estas 9 hojas (en cualquier orden, pero "Niños" se procesará primero):

```
📁 ejemplo_importacion_completo.xlsx
├── 📄 Niños (OBLIGATORIA)
├── 📄 Datos Extra
├── 📄 Madre
├── 📄 Controles RN
├── 📄 Controles CRED
├── 📄 Tamizaje
├── 📄 Vacunas
├── 📄 Visitas
└── 📄 Recien Nacido
```

---

## ✅ CHECKLIST ANTES DE IMPORTAR

- [ ] La hoja "Niños" existe y tiene al menos una fila con datos
- [ ] La columna `fecha_nacimiento` está presente en "Niños" y tiene datos válidos
- [ ] Los nombres de las columnas coinciden exactamente con los de esta guía
- [ ] Los `id_niño` en las otras hojas existen en la hoja "Niños"
- [ ] Las fechas están en formato válido (DD/MM/YYYY o YYYY-MM-DD, o formato de Excel)
- [ ] Los números son válidos (sin texto, sin caracteres especiales)
- [ ] No hay filas completamente vacías

---

## 🎯 VENTAJAS DE IMPORTAR TODO EN UN SOLO ARCHIVO

1. ✅ **Una sola importación** - No necesitas importar múltiples archivos
2. ✅ **Datos relacionados** - Todos los datos están vinculados correctamente
3. ✅ **Menos errores** - El sistema valida que los `id_niño` coincidan
4. ✅ **Más rápido** - Una sola transacción de base de datos
5. ✅ **Más organizado** - Todo está en un solo lugar

---

## 📌 NOTAS IMPORTANTES

1. **Orden de importación:** La hoja "Niños" se procesa primero automáticamente, independientemente del orden en el Excel
2. **IDs personalizados:** Si proporcionas IDs, deben ser únicos
3. **Fechas:** Acepta formatos DD/MM/YYYY, YYYY-MM-DD, o formato serial de Excel
4. **Caracteres especiales:** La "ñ" se preserva correctamente (ej: `id_niño`)
5. **Actualización:** Si un registro ya existe (mismo ID o mismo niño), se actualiza en lugar de crear uno nuevo
6. **Cálculos automáticos:** El sistema calcula automáticamente edad, estado, y si cumple o no cumple

---

*Documentación generada para el Sistema SISCADIT - Guía Completa de Importación*

