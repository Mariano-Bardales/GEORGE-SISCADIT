# 📋 Documentación del Sistema de Alertas - SISCADIT

## 📌 Índice
1. [Introducción](#introducción)
2. [Arquitectura del Sistema](#arquitectura-del-sistema)
3. [Proceso de Detección](#proceso-de-detección)
4. [Tipos de Alertas](#tipos-de-alertas)
5. [Sistema de Prioridades](#sistema-de-prioridades)
6. [Consolidación de Alertas](#consolidación-de-alertas)
7. [Estructura de Datos](#estructura-de-datos)
8. [Endpoints API](#endpoints-api)
9. [Diagramas de Flujo](#diagramas-de-flujo)
10. [Ejemplos Prácticos](#ejemplos-prácticos)

---

## 🎯 Introducción

El Sistema de Alertas de SISCADIT es un módulo automatizado que detecta y reporta situaciones que requieren atención en el seguimiento de crecimiento y desarrollo (CRED) de niños menores de 1 año. El sistema analiza continuamente los datos registrados y genera alertas cuando detecta:

- Controles faltantes o fuera de rango
- Datos incompletos
- Procedimientos médicos pendientes
- Incumplimientos de protocolos CRED

---

## 🏗️ Arquitectura del Sistema

### Componentes Principales

#### 1. **AlertasService** (`app/Services/AlertasService.php`)
Servicio principal que contiene toda la lógica de detección de alertas.

**Responsabilidades:**
- Calcular edad de los niños
- Detectar controles faltantes
- Verificar controles fuera de rango
- Generar mensajes de alerta

**Métodos principales:**
```php
obtenerTodasLasAlertas()        // Obtiene todas las alertas del sistema
obtenerAlertasRecienNacido()    // Alertas para niños 0-28 días
obtenerAlertasCred()            // Alertas para niños 29-359 días
contarTotalAlertas()            // Cuenta total de alertas
```

#### 2. **ApiController** (`app/Http/Controllers/ApiController.php`)
Controlador que maneja las peticiones HTTP relacionadas con alertas.

**Métodos principales:**
```php
obtenerAlertas()        // Retorna alertas detalladas con consolidación
totalAlertas()          // Retorna solo el conteo total
dashboardStats()        // Incluye total de alertas en estadísticas
```

#### 3. **AlertasController** (`app/Http/Controllers/Api/AlertasController.php`)
Controlador API REST para acceso a alertas.

**Endpoints:**
- `GET /api/alertas` - Lista todas las alertas
- `GET /api/alertas/total` - Total de alertas

#### 4. **RangosCredService** (`app/Services/RangosCredService.php`)
Servicio que define los rangos oficiales de edad para cada control.

**Rangos definidos:**
- Controles Recién Nacido (RN): 4 controles
- Controles CRED Mensual: 11 controles

---

## 🔄 Proceso de Detección

### Flujo General

```
┌─────────────────────────────────┐
│  1. Obtener todos los niños     │
│     Nino::all()                 │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│  2. Para cada niño:            │
│     - Calcular edad en días     │
│     - Validar fecha nacimiento  │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│  3. Verificar según edad:       │
│     - 0-28 días: RN, Tamizaje, │
│       Vacunas                   │
│     - 29-359 días: CRED,        │
│       Visitas                   │
│     - Todos: CNV, Datos         │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│  4. Consolidar alertas         │
│     similares                   │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│  5. Ordenar por prioridad y     │
│     edad                        │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│  6. Retornar alertas            │
└─────────────────────────────────┘
```

### Cálculo de Edad

El sistema utiliza la librería **Carbon** de Laravel para calcular la edad en días:

```php
$fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
$hoy = Carbon::now();
$edadDias = $fechaNacimiento->diffInDays($hoy);
```

**Ejemplo:**
- Fecha de nacimiento: 2025-01-01
- Fecha actual: 2025-01-15
- Edad en días: **14 días**

---

## 🚨 Tipos de Alertas

### A. Alertas de Datos Faltantes

#### 1. Datos del Niño
**Campos verificados:**
- Tipo de Documento
- Número de Documento
- Apellidos y Nombres
- Fecha de Nacimiento
- Género
- Establecimiento

**Prioridad:** ALTA

**Ejemplo de alerta:**
```json
{
    "tipo": "datos_faltantes_nino",
    "nino_nombre": "Juan Pérez",
    "control": "Datos del Niño",
    "mensaje": "Faltan datos del niño: Tipo de Documento, Género",
    "campos_faltantes": ["Tipo de Documento", "Género"],
    "prioridad": "alta"
}
```

#### 2. Datos de la Madre
**Campos verificados:**
- DNI
- Apellidos y Nombres
- Celular
- Domicilio

**Prioridad:** ALTA

#### 3. Datos Extras
**Campos adicionales del niño**

**Prioridad:** MEDIA

---

### B. Alertas de Controles Recién Nacido (0-28 días)

#### Rangos Definidos

| Control | Rango (días) | Descripción |
|---------|--------------|-------------|
| Control 1 | 2-6 | Verifica adaptación y lactancia |
| Control 2 | 7-13 | Seguimiento del peso y signos de alarma |
| Control 3 | 14-20 | Evaluación del crecimiento |
| Control 4 | 21-28 | Confirmación final del estado neonatal |

#### Lógica de Detección

**1. Control Faltante:**
```php
// Si el niño tiene edad dentro o pasó el rango del control
if ($edadDias >= rango['min'] && $edadDias <= rango['max']) {
    // Y no existe registro del control
    if (!existeControl(numero)) {
        // Generar alerta: "Control faltante"
    }
}
```

**2. Control Fuera de Rango:**
```php
// Si existe el control pero la fecha está fuera del rango
if (existeControl(numero)) {
    $edadAlMomento = calcularEdad(fechaControl);
    if ($edadAlMomento < rango['min'] || $edadAlMomento > rango['max']) {
        // Generar alerta: "Control fuera de rango"
        $diasFuera = calcularDiasFuera();
    }
}
```

#### Ejemplos Prácticos

**Ejemplo 1: Control Faltante**
- Niño con 10 días de edad
- Debe tener Control 2 (rango: 7-13 días)
- No tiene registro del Control 2
- **Alerta generada:** "El niño tiene 10 días y debe realizarse el control Control 2 entre los 7 y 13 días."

**Ejemplo 2: Control Fuera de Rango**
- Niño con 15 días de edad
- Tiene Control 2 registrado a los 5 días (fuera del rango 7-13)
- **Alerta generada:** "El control CRN2 fue realizado a los 5 días, fuera del rango permitido (7-13 días). Está 2 día(s) antes del límite mínimo."

**Ejemplo 3: Control Vencido**
- Niño con 30 días de edad
- Debe tener Control 4 (rango: 21-28 días)
- Ya pasaron 2 días del límite máximo
- **Alerta generada:** "El niño tiene 30 días y el control Control 4 debió realizarse entre los 21 y 28 días. Ya pasaron 2 día(s) del límite máximo."

---

### C. Alertas de Controles CRED Mensual (29-359 días)

#### Rangos Definidos

| Control | Rango (días) | Mes Aproximado |
|---------|--------------|----------------|
| Control 1 | 29-59 | 1 mes |
| Control 2 | 60-89 | 2 meses |
| Control 3 | 90-119 | 3 meses |
| Control 4 | 120-149 | 4 meses |
| Control 5 | 150-179 | 5 meses |
| Control 6 | 180-209 | 6 meses |
| Control 7 | 210-239 | 7 meses |
| Control 8 | 240-269 | 8 meses |
| Control 9 | 270-299 | 9 meses |
| Control 10 | 300-329 | 10 meses |
| Control 11 | 330-359 | 11 meses |

#### Lógica de Detección

Similar a los controles RN, pero con 11 controles mensuales.

**Criterio de "debe tener":**
```php
// El niño debe tener un control si:
if ($edadDias > rango['max']) {
    // Ya pasó el rango máximo
    $debeTener = true;
} elseif ($edadDias >= rango['min'] && $edadDias <= rango['max']) {
    // Está dentro del rango
    $debeTener = true;
}
```

#### Ejemplo Práctico

**Niño con 100 días:**
- Debe tener: Control 1 (29-59) ✅, Control 2 (60-89) ✅, Control 3 (90-119) ⚠️
- Si falta Control 1 o 2: **Alerta ALTA** (ya vencieron)
- Si falta Control 3: **Alerta MEDIA** (aún en plazo)

---

### D. Alertas de Tamizaje Neonatal (0-29 días)

#### Rango: 0-29 días

**Lógica:**
```php
if ($edadDias >= 0 && $edadDias <= 29) {
    $tamizaje = TamizajeNeonatal::where('id_niño', $ninoId)->first();
    
    // Solo se verifica fecha_tam_neo (tamizaje neonatal)
    // fecha_tam_galen es opcional
    if (!$tamizaje || !$tamizaje->fecha_tam_neo) {
        // Generar alerta
    }
}
```

**Prioridad:**
- Si $edadDias > 29: **ALTA** (ya venció)
- Si $edadDias <= 29: **MEDIA** (aún en plazo)

**Ejemplo:**
- Niño con 35 días
- No tiene tamizaje neonatal registrado
- **Alerta:** "El niño tiene 35 días y el tamizaje neonatal debió realizarse entre los 0 y 29 días. Ya pasaron 6 día(s) del límite máximo."

---

### E. Alertas de Vacunas (0-2 días)

#### Vacunas Requeridas

| Vacuna | Rango (días) | Descripción |
|--------|--------------|-------------|
| BCG | 0-2 | Vacuna BCG |
| HVB | 0-2 | Hepatitis B |

**Lógica:**
```php
if ($edadDias >= 0 && $edadDias <= 2) {
    $vacunas = VacunaRn::where('id_niño', $ninoId)->first();
    
    // Verificar BCG
    if ($vacunas && $vacunas->fecha_bcg) {
        $edadBCG = calcularEdad(fecha_bcg);
        $tieneBCG = ($edadBCG >= 0 && $edadBCG <= 2);
    }
    
    // Verificar HVB
    if ($vacunas && $vacunas->fecha_hvb) {
        $edadHVB = calcularEdad(fecha_hvb);
        $tieneHVB = ($edadHVB >= 0 && $edadHVB <= 2);
    }
    
    // Si falta alguna, generar alerta individual
    if (!$tieneBCG) { /* Alerta BCG */ }
    if (!$tieneHVB) { /* Alerta HVB */ }
}
```

**Ejemplo:**
- Niño con 3 días
- Tiene BCG pero falta HVB
- **Alerta:** "El niño tiene 3 días y la vacuna HVB debió aplicarse entre los 0 y 2 días. Ya pasaron 1 día(s) del límite máximo."

---

### F. Alertas de CNV (Carné de Nacido Vivo)

#### Campos Requeridos

- **Peso al Nacer** (obligatorio)
- **Edad Gestacional** (obligatorio)
- **Clasificación** (obligatorio)

**Lógica:**
```php
$cnv = RecienNacido::where('id_niño', $ninoId)->first();

if (!$cnv || empty($cnv->peso) || 
    empty($cnv->edad_gestacional) || 
    empty($cnv->clasificacion)) {
    // Generar alerta con campos faltantes
}
```

**Prioridad:** ALTA

**Ejemplo:**
- Niño sin registro de CNV
- **Alerta:** "El CNV (Carné de Nacido Vivo) está incompleto. Faltan los siguientes datos: Peso al Nacer, Edad Gestacional, Clasificación"

---

### G. Alertas de Visitas Domiciliarias (≥28 días)

#### Rangos Definidos

| Visita | Rango (días) | Descripción |
|--------|--------------|-------------|
| Visita 1 | 28 (exacto) | Primera visita |
| Visita 2 | 60-150 | Segunda visita |
| Visita 3 | 180-240 | Tercera visita |
| Visita 4 | 270-330 | Cuarta visita |

#### Requisitos

- **Mínimo 2 visitas cumplidas** son requeridas

**Lógica:**
```php
if ($edadDias >= 28) {
    $visitas = VisitaDomiciliaria::where('id_niño', $ninoId)->get();
    $visitasCumplen = 0;
    
    foreach ($rangosVisitas as $rango) {
        // Verificar si hay visita en el rango
        if (existeVisitaEnRango($rango)) {
            $visitasCumplen++;
        }
    }
    
    // Si tiene menos de 2 visitas cumplidas
    if ($visitasCumplen < 2) {
        // Generar alerta general
    }
}
```

**Tipos de alertas:**
1. **Visitas faltantes:** Visitas que debieron realizarse pero no están registradas
2. **Visitas fuera de rango:** Visitas registradas fuera del rango permitido
3. **Alerta general:** Menos de 2 visitas cumplidas cuando ya debería tenerlas

**Ejemplo:**
- Niño con 200 días
- Tiene solo 1 visita cumplida (Visita 1 a los 28 días)
- Debería tener mínimo 2 visitas
- **Alerta:** "El niño tiene 200 días y debe tener mínimo 2 visitas domiciliarias cumplidas. Actualmente tiene 1 visita(s) cumplida(s). Faltan 1 visita(s)."

---

## ⚠️ Sistema de Prioridades

### Niveles de Prioridad

#### 1. **ALTA** 🔴
Se asigna cuando:
- Controles fuera de rango
- Controles faltantes que ya vencieron (pasaron del rango máximo)
- Datos faltantes críticos (niño, madre)
- Tamizaje o vacunas fuera de plazo
- CNV incompleto

**Ejemplo:**
```json
{
    "prioridad": "alta",
    "mensaje": "El control CRN2 debió realizarse. Ya pasaron 5 día(s) del límite máximo."
}
```

#### 2. **MEDIA** 🟡
Se asigna cuando:
- Controles faltantes pero aún en plazo
- Datos extras faltantes
- Tamizaje o vacunas aún en plazo

**Ejemplo:**
```json
{
    "prioridad": "media",
    "mensaje": "El niño tiene 10 días y debe realizarse el control Control 2 entre los 7 y 13 días."
}
```

#### 3. **BAJA** 🟢
(No implementada actualmente, reservada para futuras funcionalidades)

---

## 🔗 Consolidación de Alertas

El sistema **consolida múltiples alertas similares** para evitar saturación de información.

### Ejemplo Sin Consolidar:
```json
[
    {"tipo": "control_recien_nacido", "control": "Control 1", "mensaje": "Control 1 faltante"},
    {"tipo": "control_recien_nacido", "control": "Control 2", "mensaje": "Control 2 faltante"},
    {"tipo": "control_recien_nacido", "control": "Control 3", "mensaje": "Control 3 faltante"}
]
```

### Ejemplo Consolidado:
```json
[
    {
        "tipo": "control_recien_nacido",
        "control": "Controles RN",
        "mensaje": "Los controles Control 1, Control 2, Control 3 debieron realizarse. Ya pasaron hasta 5 día(s) del límite máximo (3 controles faltantes).",
        "controles_faltantes": ["Control 1", "Control 2", "Control 3"],
        "total_controles_faltantes": 3,
        "max_dias_fuera": 5
    }
]
```

### Beneficios de la Consolidación:
- ✅ Reduce el número de alertas mostradas
- ✅ Facilita la lectura y comprensión
- ✅ Agrupa problemas relacionados
- ✅ Muestra información resumida pero completa

---

## 📊 Estructura de Datos

### Estructura Completa de una Alerta

```json
{
    "tipo": "control_recien_nacido",
    "nino_id": 123,
    "nino_nombre": "Juan Pérez García",
    "nino_dni": "12345678",
    "establecimiento": "Centro de Salud Callería",
    "control": "Control 1",
    "edad_dias": 10,
    "edad_dias_control": null,
    "rango_min": 2,
    "rango_max": 6,
    "rango_dias": "2-6",
    "prioridad": "alta",
    "fecha_nacimiento": "2025-01-01",
    "fecha_control": null,
    "mensaje": "El niño tiene 10 días y el control Control 1 debió realizarse entre los 2 y 6 días. Ya pasaron 4 día(s) del límite máximo.",
    "dias_fuera": 4,
    "controles_faltantes": ["Control 1"],
    "total_controles_faltantes": 1
}
```

### Campos Comunes

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `tipo` | string | Tipo de alerta (control_recien_nacido, control_cred_mensual, tamizaje, vacuna, etc.) |
| `nino_id` | integer | ID del niño en la base de datos |
| `nino_nombre` | string | Nombre completo del niño |
| `nino_dni` | string | Número de documento del niño |
| `establecimiento` | string | Establecimiento de salud |
| `control` | string | Nombre del control o procedimiento |
| `edad_dias` | integer | Edad actual del niño en días |
| `rango_min` | integer | Día mínimo del rango permitido |
| `rango_max` | integer | Día máximo del rango permitido |
| `prioridad` | string | Nivel de prioridad (alta, media) |
| `mensaje` | string | Mensaje descriptivo de la alerta |
| `dias_fuera` | integer | Días fuera del rango (si aplica) |

### Campos Específicos por Tipo

#### Controles Faltantes:
- `controles_faltantes`: Array de nombres de controles faltantes
- `total_controles_faltantes`: Número total de controles faltantes
- `max_dias_fuera`: Máximo de días fuera del rango

#### Controles Fuera de Rango:
- `edad_dias_control`: Edad en días cuando se realizó el control
- `fecha_control`: Fecha en que se realizó el control
- `controles_fuera_rango`: Array de controles fuera de rango

#### Datos Faltantes:
- `campos_faltantes`: Array de campos que faltan

---

## 🌐 Endpoints API

### 1. Obtener Todas las Alertas

**Endpoint:** `GET /api/alertas`

**Respuesta:**
```json
{
    "success": true,
    "data": [
        {
            "tipo": "control_recien_nacido",
            "nino_id": 123,
            "nino_nombre": "Juan Pérez",
            ...
        }
    ],
    "total": 15
}
```

**Características:**
- Retorna todas las alertas del sistema
- Incluye consolidación automática
- Ordenadas por prioridad y edad
- Sin caché (siempre datos actuales)

### 2. Obtener Total de Alertas

**Endpoint:** `GET /api/alertas/total`

**Respuesta:**
```json
{
    "success": true,
    "total": 15
}
```

**Uso:** Para mostrar contadores rápidos sin cargar todas las alertas.

### 3. Estadísticas del Dashboard

**Endpoint:** `GET /api/dashboard/stats`

**Respuesta:**
```json
{
    "success": true,
    "data": {
        "total_ninos": 49,
        "total_controles": 720,
        "total_usuarios": 2,
        "total_alertas": 15
    }
}
```

**Uso:** Para el dashboard principal que muestra resumen general.

---

## 📈 Diagramas de Flujo

### Flujo de Detección de Alertas RN

```
┌─────────────────────────────┐
│ Niño con edad ≤ 28 días     │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐
│ Obtener controles RN        │
│ registrados                 │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐
│ Para cada control (1-4):    │
│                             │
│ ¿Edad dentro o pasó rango?  │
└──────────────┬──────────────┘
               │
        ┌──────┴──────┐
        │             │
       SÍ            NO
        │             │
        ▼             │
┌───────────────┐     │
│ ¿Existe       │     │
│ control?      │     │
└───────┬───────┘     │
        │             │
   ┌────┴────┐        │
   │         │        │
  NO        SÍ        │
   │         │        │
   ▼         ▼        │
┌──────┐ ┌──────────┐ │
│Alerta│ │Verificar │ │
│Faltan│ │si está   │ │
│te    │ │en rango  │ │
└──────┘ └────┬─────┘ │
              │       │
         ┌────┴────┐  │
         │         │  │
      Dentro   Fuera  │
      rango    rango  │
         │         │  │
         │         ▼  │
         │    ┌───────┐
         │    │Alerta │
         │    │Fuera  │
         │    │Rango  │
         │    └───────┘
         │
         ▼
    Sin alerta
```

### Flujo de Detección de Alertas CRED

```
┌─────────────────────────────┐
│ Niño con edad 29-359 días  │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐
│ Obtener controles CRED     │
│ registrados                 │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐
│ Para cada control (1-11):  │
│                             │
│ ¿Edad > rango_max O        │
│   dentro del rango?        │
└──────────────┬──────────────┘
               │
        ┌──────┴──────┐
        │             │
       SÍ            NO
        │             │
        ▼             │
┌───────────────┐     │
│ ¿Existe       │     │
│ control?      │     │
└───────┬───────┘     │
        │             │
   ┌────┴────┐        │
   │         │        │
  NO        SÍ        │
   │         │        │
   ▼         ▼        │
┌──────┐ ┌──────────┐ │
│Alerta│ │Verificar │ │
│Faltan│ │si está   │ │
│te    │ │en rango  │ │
└──────┘ └────┬─────┘ │
              │       │
         ┌────┴────┐  │
         │         │  │
      Dentro   Fuera  │
      rango    rango  │
         │         │  │
         │         ▼  │
         │    ┌───────┐
         │    │Alerta │
         │    │Fuera  │
         │    │Rango  │
         │    └───────┘
         │
         ▼
    Sin alerta
```

---

## 💡 Ejemplos Prácticos

### Ejemplo 1: Niño Recién Nacido Completo

**Datos del niño:**
- Fecha de nacimiento: 2025-01-01
- Fecha actual: 2025-01-10
- Edad: 9 días

**Controles registrados:**
- Control 1: Sí (a los 3 días) ✅
- Control 2: No ❌
- Control 3: No ❌
- Control 4: No ❌

**Alertas generadas:**
```json
{
    "tipo": "control_recien_nacido",
    "control": "Controles RN",
    "mensaje": "Los controles Control 2 debieron realizarse. Ya pasaron 0 día(s) del límite máximo (1 control faltante).",
    "controles_faltantes": ["Control 2"],
    "prioridad": "media"
}
```

**Explicación:**
- Control 1: ✅ Cumplido (rango 2-6 días, realizado a los 3 días)
- Control 2: ⚠️ Falta pero aún en plazo (rango 7-13 días, tiene 9 días)
- Control 3: ⏳ Aún no corresponde (rango 14-20 días)
- Control 4: ⏳ Aún no corresponde (rango 21-28 días)

---

### Ejemplo 2: Niño con Controles Fuera de Rango

**Datos del niño:**
- Fecha de nacimiento: 2025-01-01
- Fecha actual: 2025-01-20
- Edad: 19 días

**Controles registrados:**
- Control 1: Sí (a los 8 días) ❌ Fuera de rango (debe ser 2-6)
- Control 2: Sí (a los 5 días) ❌ Fuera de rango (debe ser 7-13)
- Control 3: No ❌

**Alertas generadas:**
```json
[
    {
        "tipo": "control_recien_nacido_fuera_rango",
        "control": "Controles RN",
        "mensaje": "Los controles Control 1, Control 2 fueron realizados fuera del rango permitido. Están hasta 2 día(s) fuera del límite (2 controles fuera de rango).",
        "controles_fuera_rango": ["Control 1", "Control 2"],
        "prioridad": "alta"
    },
    {
        "tipo": "control_recien_nacido",
        "control": "Controles RN",
        "mensaje": "El control Control 3 debió realizarse. Ya pasaron 0 día(s) del límite máximo.",
        "controles_faltantes": ["Control 3"],
        "prioridad": "media"
    }
]
```

---

### Ejemplo 3: Niño CRED con Múltiples Alertas

**Datos del niño:**
- Fecha de nacimiento: 2024-10-01
- Fecha actual: 2025-01-15
- Edad: 106 días

**Controles registrados:**
- Control 1: Sí (a los 35 días) ✅
- Control 2: No ❌
- Control 3: No ❌

**Alertas generadas:**
```json
{
    "tipo": "control_cred_mensual",
    "control": "Controles CRED",
    "mensaje": "Los controles CRED Control 2 debieron realizarse. Ya pasaron 17 día(s) del límite máximo (1 control faltante).",
    "controles_faltantes": ["Control 2"],
    "prioridad": "alta",
    "max_dias_fuera": 17
}
```

**Explicación:**
- Control 1: ✅ Cumplido (rango 29-59 días, realizado a los 35 días)
- Control 2: ❌ Faltante y vencido (rango 60-89 días, tiene 106 días, pasaron 17 días del límite)
- Control 3: ⚠️ Falta pero aún en plazo (rango 90-119 días, tiene 106 días)

---

### Ejemplo 4: Niño con Tamizaje y Vacunas Pendientes

**Datos del niño:**
- Fecha de nacimiento: 2025-01-10
- Fecha actual: 2025-01-12
- Edad: 2 días

**Registros:**
- Tamizaje Neonatal: No ❌
- Vacuna BCG: No ❌
- Vacuna HVB: No ❌

**Alertas generadas:**
```json
[
    {
        "tipo": "tamizaje",
        "control": "Tamizaje Neonatal",
        "mensaje": "El niño tiene 2 días y debe realizarse el tamizaje neonatal entre los 0 y 29 días de vida.",
        "prioridad": "media"
    },
    {
        "tipo": "vacuna",
        "control": "Vacuna BCG",
        "mensaje": "El niño tiene 2 días y debe aplicarse la vacuna BCG entre los 0 y 2 días de vida.",
        "prioridad": "media"
    },
    {
        "tipo": "vacuna",
        "control": "Vacuna HVB",
        "mensaje": "El niño tiene 2 días y debe aplicarse la vacuna HVB entre los 0 y 2 días de vida.",
        "prioridad": "media"
    }
]
```

---

## 🔍 Casos Especiales

### Caso 1: Niño sin Fecha de Nacimiento

**Comportamiento:**
- Se omite el niño en las verificaciones de controles
- Solo se generan alertas de datos faltantes

### Caso 2: Múltiples Controles del Mismo Tipo

**Comportamiento:**
- Se toma el primer control encontrado
- Se verifica si está en rango
- Si hay múltiples fuera de rango, se consolidan

### Caso 3: Edad Negativa o Inválida

**Comportamiento:**
- Se valida la fecha de nacimiento antes de calcular
- Si hay error, se omite el niño
- Se registra en logs para depuración

---

## 📝 Notas Técnicas

### Rendimiento

- El sistema procesa todos los niños en cada consulta
- Para sistemas grandes, considerar caché o procesamiento asíncrono
- Las consultas a la base de datos están optimizadas con índices

### Mantenimiento

- Los rangos están centralizados en `RangosCredService`
- Cambios en rangos solo requieren modificar un archivo
- Los mensajes de alerta son dinámicos y descriptivos

### Extensibilidad

- Fácil agregar nuevos tipos de alertas
- Estructura modular permite agregar servicios adicionales
- Los tipos de alerta son configurables

---

## 🎓 Conclusión

El Sistema de Alertas de SISCADIT es una herramienta poderosa que:

✅ **Detecta automáticamente** situaciones que requieren atención  
✅ **Prioriza** las alertas según su urgencia  
✅ **Consolida** información relacionada  
✅ **Proporciona mensajes claros** y accionables  
✅ **Se actualiza en tiempo real** con cada consulta  

Este sistema ayuda a los profesionales de salud a:
- Identificar rápidamente niños que requieren atención
- Seguir protocolos CRED correctamente
- Completar datos faltantes
- Realizar controles en los tiempos adecuados

---

**Versión del Documento:** 1.0  
**Fecha de Creación:** 2025-01-15  
**Última Actualización:** 2025-01-15  
**Autor:** Sistema SISCADIT

