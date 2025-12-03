# 🚨 DOCUMENTACIÓN: SISTEMA DE DETECCIÓN DE ALERTAS
## Explicación de la Lógica de Detección de Anomalías en Controles

---

## 📍 UBICACIÓN DE LA LÓGICA DE ALERTAS

### **1. Controlador Principal: `ApiController.php`**

La lógica principal de detección de alertas se encuentra en el archivo:
```
app/Http/Controllers/ApiController.php
```

#### **Métodos Clave:**

1. **`obtenerAlertas()`** - Líneas **1362-1762**
   - Método principal que genera todas las alertas del sistema
   - Ruta API: `GET /api/alertas`
   - Retorna: JSON con array de alertas detalladas

2. **`totalAlertas()`** - Líneas **1207-1360**
   - Calcula el total de alertas para el dashboard
   - Ruta API: `GET /api/alertas/total`
   - Retorna: Número total de alertas

3. **`dashboardStats()`** - Líneas **38-150**
   - Calcula estadísticas del dashboard incluyendo total de alertas
   - Ruta API: `GET /api/dashboard/stats`

---

## 🔍 CÓMO FUNCIONA EL SISTEMA DE ALERTAS

### **Concepto General:**

El sistema detecta alertas basándose en:
1. **Edad actual del niño** (fecha actual - fecha nacimiento) - Para detectar controles faltantes
2. **Edad al momento del control** (fecha_control - fecha_nacimiento) - Para validar si cumple el rango
3. **Rangos de edad permitidos** para cada tipo de control
4. **Controles registrados** en la base de datos
5. **Validación de cumplimiento** de rangos

### **⚠️ IMPORTANTE: Dos Tipos de Cálculo de Edad**

#### **1. Edad Actual (para detectar controles faltantes)**
```php
$edadDias = $fechaNacimiento->diffInDays($hoy); // Fecha actual - Fecha nacimiento
```
- Se usa para determinar qué controles debería tener el niño según su edad actual
- Ejemplo: Si tiene 74 días, debería tener "Mes 2" (rango 60-89 días)

#### **2. Edad al Momento del Control (para validar cumplimiento)**
```php
$edadDiasControl = $fechaNacimiento->diffInDays($fechaControl); // Fecha control - Fecha nacimiento
```
- Se usa para validar si un control registrado cumple con su rango permitido
- Ejemplo: Si el control fue el 20 de febrero y nació el 1 de enero, la edad del control es 50 días
- Esta edad se compara con el rango del control (ej: 29-59 días) para saber si CUMPLE o NO CUMPLE

### **Flujo de Detección:**

```
1. Obtener todos los niños del sistema
2. Para cada niño:
   a. Calcular EDAD ACTUAL en días (fecha actual - fecha nacimiento)
   b. Determinar qué controles debería tener según su edad actual
   c. Verificar qué controles tiene registrados
   d. Para cada control registrado:
      - Calcular EDAD AL MOMENTO DEL CONTROL (fecha_control - fecha_nacimiento)
      - Comparar edad del control con el rango permitido
      - Si está fuera de rango → Generar alerta "Control fuera de rango"
   e. Para controles faltantes:
      - Comparar controles esperados vs registrados
      - Si falta un control que debería tener → Generar alerta "Control faltante"
   f. Generar alertas para:
      - Controles faltantes (usa edad actual)
      - Controles fuera de rango (usa edad del control)
      - Datos incompletos
```

---

## 📊 TIPOS DE CONTROLES Y SUS RANGOS

### **1. Controles Recién Nacido (CRN) - 4 Controles**

**Ubicación de rangos:** `ApiController.php` líneas **1382-1387**

```php
$rangosRN = [
    1 => ['min' => 2, 'max' => 6, 'nombre' => 'CRN1'],
    2 => ['min' => 7, 'max' => 13, 'nombre' => 'CRN2'],
    3 => ['min' => 14, 'max' => 20, 'nombre' => 'CRN3'],
    4 => ['min' => 21, 'max' => 28, 'nombre' => 'CRN4']
];
```

**Edad aplicable:** 0-28 días

**Lógica de detección:** Líneas **1377-1421**
- Si el niño tiene ≤ 28 días, verifica controles RN
- Compara controles registrados vs esperados según edad
- Genera alerta si falta algún control o está fuera de rango

---

### **2. Controles CRED Mensuales - 11 Controles**

**Ubicación de rangos:** `ApiController.php` líneas **1431-1443**

```php
$rangosCred = [
    1 => ['min' => 29, 'max' => 59, 'nombre' => 'Mes 1'],
    2 => ['min' => 60, 'max' => 89, 'nombre' => 'Mes 2'],
    3 => ['min' => 90, 'max' => 119, 'nombre' => 'Mes 3'],
    4 => ['min' => 120, 'max' => 149, 'nombre' => 'Mes 4'],
    5 => ['min' => 150, 'max' => 179, 'nombre' => 'Mes 5'],
    6 => ['min' => 180, 'max' => 209, 'nombre' => 'Mes 6'],
    7 => ['min' => 210, 'max' => 239, 'nombre' => 'Mes 7'],
    8 => ['min' => 240, 'max' => 269, 'nombre' => 'Mes 8'],
    9 => ['min' => 270, 'max' => 299, 'nombre' => 'Mes 9'],
    10 => ['min' => 300, 'max' => 329, 'nombre' => 'Mes 10'],
    11 => ['min' => 330, 'max' => 359, 'nombre' => 'Mes 11']
];
```

**Edad aplicable:** 29-359 días

**Lógica de detección:** Líneas **1423-1515**
- Si el niño tiene entre 29-359 días, verifica controles CRED
- Para cada mes (1-11):
  - Verifica si el niño debería tener ese control (según edad)
  - Si tiene control registrado: valida que esté dentro del rango
  - Si no tiene control: genera alerta de control faltante
  - Si el control está fuera de rango: genera alerta de anomalía

**Validación de rango:**
```php
// Líneas 1457-1488
if ($control && $control->fecha) {
    // ⚠️ IMPORTANTE: Se calcula la edad que tenía el niño CUANDO SE REALIZÓ EL CONTROL
    $fechaControl = Carbon::parse($control->fecha);
    $edadDiasControl = $fechaNacimiento->diffInDays($fechaControl); // Fecha control - Fecha nacimiento
    
    // Se compara la edad del control con el rango permitido
    if ($edadDiasControl < $rango['min'] || $edadDiasControl > $rango['max']) {
        // GENERAR ALERTA: Control fuera de rango
        // Ejemplo: Si el control fue a los 70 días pero el rango es 29-59 días → NO CUMPLE
    }
}
```

**Ejemplo de validación:**
- Fecha nacimiento: 1 de enero 2024
- Fecha del control "Mes 1": 20 de febrero 2024
- Edad al momento del control: 50 días (20 feb - 1 ene)
- Rango permitido Mes 1: 29-59 días
- Validación: 50 días está entre 29-59 → ✅ **CUMPLE**
- Si hubiera sido a los 70 días → ❌ **NO CUMPLE** (fuera del rango máximo)

---

### **3. Tamizaje Neonatal - 1 Control**

**Ubicación:** `ApiController.php` líneas **1657-1684**

**Rango:** 0-29 días

**Lógica:**
- Si el niño tiene entre 0-29 días, debe tener tamizaje neonatal
- Verifica que exista registro con `fecha_tam_neo`
- Genera alerta si falta o si ya pasó el límite (29 días)

---

### **4. Vacunas del Recién Nacido - 2 Controles**

**Ubicación:** `ApiController.php` líneas **1686-1744**

**Rango:** 0-2 días

**Controles requeridos:**
- **BCG** (Vacuna contra tuberculosis)
- **HVB** (Vacuna contra hepatitis B)

**Lógica:**
- Si el niño tiene entre 0-2 días, debe tener ambas vacunas
- Verifica que `fecha_bcg` y `estado_bcg = 'SI'` existan
- Verifica que `fecha_hvb` y `estado_hvb = 'SI'` existan
- Genera alerta separada para cada vacuna faltante

---

### **5. CNV (Carné de Nacido Vivo) - Datos Requeridos**

**Ubicación:** `ApiController.php` líneas **1517-1540**

**Campos requeridos:**
- `peso` (Peso al nacer)
- `edad_gestacional` (Edad gestacional)
- `clasificacion` (Clasificación del recién nacido)

**Lógica:**
- Verifica que exista registro de CNV
- Verifica que todos los campos requeridos estén completos
- Genera alerta si falta algún campo

---

### **6. Visitas Domiciliarias - 4 Visitas (Mínimo 2 requeridas)**

**Ubicación:** `ApiController.php` líneas **1542-1655**

**Rangos:**
```php
$rangosVisitas = [
    'A' => ['min' => 28, 'max' => 28, 'nombre' => 'Visita A (28 días)'],
    'B' => ['min' => 60, 'max' => 150, 'nombre' => 'Visita B (2-5 meses)'],
    'C' => ['min' => 180, 'max' => 240, 'nombre' => 'Visita C (6-8 meses)'],
    'D' => ['min' => 270, 'max' => 330, 'nombre' => 'Visita D (9-11 meses)']
];
```

**Lógica:**
- Si el niño tiene ≥ 28 días, verifica visitas domiciliarias
- Valida que cada visita esté dentro de su rango
- Requiere mínimo 2 visitas cumplidas
- Genera alerta si:
  - Visita está fuera de rango
  - Falta visita requerida
  - Tiene menos de 2 visitas cumplidas

---

## 🔧 SERVICIO DE RANGOS: `RangosCredService.php`

**Ubicación:** `app/Services/RangosCredService.php`

Este servicio centraliza la definición de rangos y proporciona métodos de validación.

### **Métodos principales:**

1. **`getRangosRecienNacido()`** - Líneas **17-25**
   - Retorna rangos para controles RN (1-4)

2. **`getRangosCredMensual()`** - Líneas **31-46**
   - Retorna rangos para controles CRED (1-11)

3. **`validarControl()`** - Líneas **99-157**
   - Valida si un control cumple con su rango
   - Retorna: `['cumple' => bool, 'estado' => string, 'rango' => array]`

---

## 📡 ENDPOINTS API DE ALERTAS

### **1. Obtener Todas las Alertas Detalladas**

**Ruta:** `GET /api/alertas`

**Controlador:** `ApiController@obtenerAlertas`

**Respuesta:**
```json
{
    "success": true,
    "data": [
        {
            "tipo": "control_cred_mensual",
            "nino_id": 1,
            "nino_nombre": "Juan Pérez",
            "nino_dni": "12345678",
            "establecimiento": "Centro de Salud",
            "control": "Mes 1",
            "mes": 1,
            "edad_dias": 65,
            "edad_dias_control": 70,
            "rango_min": 29,
            "rango_max": 59,
            "rango_dias": "29-59",
            "prioridad": "alta",
            "fecha_nacimiento": "2024-01-01",
            "fecha_control": "2024-03-11",
            "mensaje": "El control Mes 1 fue realizado a los 70 días, fuera del rango permitido (29-59 días). Está 11 día(s) fuera del límite máximo.",
            "dias_fuera": 11
        }
    ],
    "total": 1
}
```

### **2. Obtener Total de Alertas**

**Ruta:** `GET /api/alertas/total`

**Controlador:** `ApiController@totalAlertas`

**Respuesta:**
```json
{
    "success": true,
    "total": 15
}
```

---

## 🎯 TIPOS DE ALERTAS GENERADAS

### **1. Control Faltante**
- **Cuándo:** El niño debería tener un control según su **edad actual** pero no está registrado
- **Cálculo usado:** `Edad actual = Fecha actual - Fecha nacimiento`
- **Prioridad:** 
  - `alta`: Si ya pasó el límite máximo del rango
  - `media`: Si aún está dentro del rango esperado
- **Ejemplo:** Niño de 74 días debería tener "Mes 2" (60-89 días) pero no está registrado

### **2. Control Fuera de Rango**
- **Cuándo:** El control está registrado pero la **edad al momento del control** no cumple con el rango permitido
- **Cálculo usado:** `Edad del control = Fecha control - Fecha nacimiento`
- **Prioridad:** `alta`
- **Cálculo de días fuera:** Diferencia entre edad del control y límites del rango
- **Ejemplo:** Control "Mes 1" realizado a los 70 días, pero el rango es 29-59 días → Está 11 días fuera del límite máximo

### **3. Datos Incompletos**
- **Cuándo:** Falta información requerida (ej: CNV incompleto)
- **Prioridad:** `alta`

---

## 📋 ESTRUCTURA DE UNA ALERTA

Cada alerta contiene:

```php
[
    'tipo' => string,              // Tipo de control (control_cred_mensual, control_recien_nacido, etc.)
    'nino_id' => int,              // ID del niño
    'nino_nombre' => string,       // Nombre completo del niño
    'nino_dni' => string,          // DNI del niño
    'establecimiento' => string,   // Establecimiento de salud
    'control' => string,           // Nombre del control (ej: "Mes 1", "CRN1")
    'edad_dias' => int,            // Edad actual del niño en días
    'rango_min' => int,            // Límite mínimo del rango
    'rango_max' => int,            // Límite máximo del rango
    'rango_dias' => string,        // Rango en formato "min-max"
    'prioridad' => string,         // 'alta' o 'media'
    'fecha_nacimiento' => string,  // Fecha de nacimiento (Y-m-d)
    'mensaje' => string,           // Mensaje descriptivo de la alerta
    'dias_fuera' => int,           // Días fuera del rango (si aplica)
]
```

---

## 🔄 ORDENAMIENTO DE ALERTAS

**Ubicación:** `ApiController.php` líneas **1747-1753**

Las alertas se ordenan por:
1. **Prioridad** (alta primero)
2. **Edad del niño** (mayor edad primero)

```php
usort($alertas, function($a, $b) {
    if ($a['prioridad'] === $b['prioridad']) {
        return $b['edad_dias'] - $a['edad_dias'];
    }
    return $a['prioridad'] === 'alta' ? -1 : 1;
});
```

---

## 🖥️ DÓNDE SE MUESTRAN LAS ALERTAS

### **1. Dashboard Principal**

**Archivo:** `resources/views/dashboard/index.blade.php`

**Tarjeta de estadísticas:**
- Muestra total de alertas detectadas
- Se actualiza automáticamente cada 30 segundos

### **2. Página de Alertas CRED**

**Ruta:** `/alertas-cred`

**Archivo:** `resources/views/dashboard/alertas-cred.blade.php`

**Funcionalidad:**
- Muestra lista completa de alertas
- Filtros por tipo de alerta
- Información detallada de cada alerta

### **3. JavaScript del Dashboard**

**Archivo:** `public/JS/dashbord.js`

**Función:** `generarResumenAlertasParaNino()` - Líneas **395-579**

Genera alertas en tiempo real para mostrar en el dashboard.

---

## 💡 EJEMPLO PRÁCTICO: Cómo se Detecta una Alerta

### **Escenario:**
- Niño nació el **1 de enero de 2024**
- Hoy es **15 de marzo de 2024**
- Edad actual del niño: **74 días** (fecha actual - fecha nacimiento)
- Tiene registrado el "Mes 1" (Control CRED 1) con fecha **20 de febrero de 2024**

### **Proceso de Detección:**

#### **PASO 1: Calcular edad actual (para detectar faltantes)**
```php
$fechaNacimiento = Carbon::parse('2024-01-01');
$hoy = Carbon::parse('2024-03-15');
$edadDias = $fechaNacimiento->diffInDays($hoy); // = 74 días
```

#### **PASO 2: Determinar controles esperados según edad actual**
- Con 74 días, debería tener:
  - Mes 1 (29-59 días) ✅ Ya pasó el rango
  - Mes 2 (60-89 días) ✅ Está en este rango

#### **PASO 3: Verificar control registrado "Mes 1"**
- Tiene "Mes 1" registrado con fecha: 20 de febrero 2024

#### **PASO 4: Validar si el control CUMPLE (usa edad del control)**
```php
// ⚠️ IMPORTANTE: Se calcula la edad que tenía CUANDO SE REALIZÓ EL CONTROL
$fechaControl = Carbon::parse('2024-02-20');
$edadDiasControl = $fechaNacimiento->diffInDays($fechaControl); // = 50 días

// Validar contra el rango
Rango Mes 1: 29-59 días
Edad del control: 50 días
Validación: 50 >= 29 && 50 <= 59 → ✅ CUMPLE
```

**Resultado:** El control "Mes 1" está correcto, no genera alerta.

#### **PASO 5: Verificar control faltante "Mes 2"**
- Debería tener "Mes 2" (ya tiene 74 días, rango es 60-89)
- No tiene "Mes 2" registrado
- **GENERAR ALERTA:** Control Mes 2 faltante

### **Alerta Generada:**
```json
{
    "tipo": "control_cred_mensual",
    "nino_id": 1,
    "nino_nombre": "Juan Pérez",
    "control": "Mes 2",
    "edad_dias": 74,
    "rango_min": 60,
    "rango_max": 89,
    "prioridad": "media",
    "mensaje": "El niño tiene 74 días y debe realizarse el control Mes 2 entre los 60 y 89 días."
}
```

---

### **Ejemplo 2: Control Fuera de Rango**

**Escenario:**
- Niño nació el **1 de enero de 2024**
- Tiene "Mes 1" registrado con fecha **5 de marzo de 2024** (incorrecta, muy tarde)

**Proceso:**
```php
// Calcular edad al momento del control
$fechaControl = Carbon::parse('2024-03-05');
$edadDiasControl = $fechaNacimiento->diffInDays($fechaControl); // = 64 días

// Validar contra el rango
Rango Mes 1: 29-59 días
Edad del control: 64 días
Validación: 64 > 59 → ❌ NO CUMPLE (está 5 días fuera del límite máximo)

// GENERAR ALERTA: Control fuera de rango
```

**Alerta Generada:**
```json
{
    "tipo": "control_cred_mensual",
    "control": "Mes 1",
    "edad_dias_control": 64,
    "rango_min": 29,
    "rango_max": 59,
    "dias_fuera": 5,
    "prioridad": "alta",
    "mensaje": "El control Mes 1 fue realizado a los 64 días, fuera del rango permitido (29-59 días). Está 5 día(s) fuera del límite máximo."
}
```

---

## 📝 RESUMEN DE ARCHIVOS CLAVE

| Archivo | Líneas | Función |
|---------|--------|---------|
| `app/Http/Controllers/ApiController.php` | 1362-1762 | Método principal `obtenerAlertas()` |
| `app/Http/Controllers/ApiController.php` | 1207-1360 | Método `totalAlertas()` |
| `app/Http/Controllers/ApiController.php` | 38-150 | Método `dashboardStats()` |
| `app/Services/RangosCredService.php` | Todo el archivo | Servicio de rangos y validación |
| `routes/web.php` | 120-121 | Rutas API de alertas |
| `public/JS/dashbord.js` | 395-579 | Función JavaScript para alertas |
| `resources/views/dashboard/alertas-cred.blade.php` | Todo el archivo | Vista de alertas |

---

## 🎓 CONCEPTOS IMPORTANTES

### **1. Cálculo de Edad Actual (para detectar faltantes)**
```php
$fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
$hoy = Carbon::now();
$edadDias = $fechaNacimiento->diffInDays($hoy); // Fecha actual - Fecha nacimiento
```
**Uso:** Determinar qué controles debería tener el niño según su edad actual.

### **2. Cálculo de Edad al Momento del Control (para validar cumplimiento)**
```php
$fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
$fechaControl = Carbon::parse($control->fecha);
$edadDiasControl = $fechaNacimiento->diffInDays($fechaControl); // Fecha control - Fecha nacimiento
```
**Uso:** Validar si un control registrado cumple con su rango permitido.

### **3. Validación de Rango**
Un control cumple si la **edad al momento del control** está dentro del rango:
```php
$edadDiasControl >= $rango['min'] && $edadDiasControl <= $rango['max']
```

**Ejemplo:**
- Rango Mes 1: 29-59 días
- Control realizado cuando el niño tenía 50 días → ✅ CUMPLE
- Control realizado cuando el niño tenía 70 días → ❌ NO CUMPLE (fuera del rango)

### **4. Determinación de Controles Esperados (usa edad actual)**
- Si `$edadDias > $rango['max']`: El control ya debería estar realizado → Alerta si falta
- Si `$edadDias >= $rango['min'] && $edadDias <= $rango['max']`: El control está en período de realización → Alerta si falta

---

## ✅ CHECKLIST PARA EXPLICAR EL SISTEMA

- [x] Ubicación de la lógica principal (`ApiController.php`)
- [x] Tipos de controles y sus rangos
- [x] Cómo se detectan alertas (faltantes y fuera de rango)
- [x] Estructura de datos de una alerta
- [x] Endpoints API disponibles
- [x] Dónde se muestran las alertas en la interfaz
- [x] Ejemplo práctico de detección
- [x] Archivos clave del sistema

---

*Documentación generada para el Sistema SISCADIT - Sistema de Control y Alerta de Etapas de Vida del Niño*

