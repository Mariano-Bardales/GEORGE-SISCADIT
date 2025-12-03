# 🏗️ ARQUITECTURA DEL SISTEMA SISCADIT

## 📋 Índice
1. [Estructura General](#estructura-general)
2. [Patrones de Diseño](#patrones-de-diseño)
3. [Organización de Capas](#organización-de-capas)
4. [Servicios](#servicios)
5. [Repositorios](#repositorios)
6. [Controladores](#controladores)
7. [Validaciones](#validaciones)
8. [Mejores Prácticas](#mejores-prácticas)

---

## 🏛️ Estructura General

El sistema está organizado siguiendo principios de **Arquitectura Limpia** y **Separación de Responsabilidades**:

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/              # Controladores API específicos
│   │   │   ├── AlertasController.php
│   │   │   ├── ControlCredController.php
│   │   │   └── ControlRnController.php
│   │   ├── Auth/            # Autenticación
│   │   └── ...               # Controladores web
│   └── Requests/             # Form Requests (Validaciones)
│       ├── StoreNinoRequest.php
│       ├── StoreControlCredRequest.php
│       └── StoreControlRnRequest.php
├── Services/                 # Lógica de Negocio
│   ├── AlertasService.php
│   ├── EdadService.php
│   ├── EstadoControlService.php
│   └── RangosCredService.php
├── Repositories/             # Acceso a Datos
│   ├── NinoRepository.php
│   └── ControlRepository.php
└── Models/                   # Modelos Eloquent
    ├── Nino.php
    ├── ControlMenor1.php
    └── ...
```

---

## 🎯 Patrones de Diseño

### 1. **Service Layer Pattern**
- **Propósito**: Separar la lógica de negocio de los controladores
- **Ubicación**: `app/Services/`
- **Ejemplo**: `AlertasService`, `EdadService`, `EstadoControlService`

### 2. **Repository Pattern**
- **Propósito**: Abstraer el acceso a datos
- **Ubicación**: `app/Repositories/`
- **Ejemplo**: `NinoRepository`, `ControlRepository`

### 3. **Form Request Pattern**
- **Propósito**: Centralizar validaciones
- **Ubicación**: `app/Http/Requests/`
- **Ejemplo**: `StoreNinoRequest`, `StoreControlCredRequest`

---

## 📦 Organización de Capas

### **Capa de Presentación (Controllers)**
- **Responsabilidad**: Manejar requests HTTP, validar entrada, retornar respuestas
- **No debe contener**: Lógica de negocio, consultas directas a BD
- **Ejemplo**:
```php
class ControlCredController extends Controller
{
    public function store(StoreControlCredRequest $request)
    {
        // Validación automática por Form Request
        // Llamada a Service para lógica de negocio
        // Retorno de respuesta JSON
    }
}
```

### **Capa de Lógica de Negocio (Services)**
- **Responsabilidad**: Contener toda la lógica de negocio
- **Ejemplo**:
```php
class AlertasService
{
    public function obtenerTodasLasAlertas(): array
    {
        // Lógica compleja de detección de alertas
    }
}
```

### **Capa de Acceso a Datos (Repositories)**
- **Responsabilidad**: Abstraer consultas a la base de datos
- **Ejemplo**:
```php
class NinoRepository
{
    public function findById(int $id): ?Nino
    {
        return Nino::where('id_niño', $id)->first();
    }
}
```

### **Capa de Modelos (Models)**
- **Responsabilidad**: Representar entidades y relaciones
- **No debe contener**: Lógica de negocio compleja

---

## 🔧 Servicios

### **AlertasService**
- **Ubicación**: `app/Services/AlertasService.php`
- **Responsabilidad**: Detectar y generar alertas de controles
- **Métodos principales**:
  - `obtenerTodasLasAlertas()`: Obtiene todas las alertas del sistema
  - `obtenerAlertasRecienNacido()`: Alertas para controles RN
  - `obtenerAlertasCred()`: Alertas para controles CRED
  - `contarTotalAlertas()`: Cuenta total de alertas

### **EdadService**
- **Ubicación**: `app/Services/EdadService.php`
- **Responsabilidad**: Cálculos de edad
- **Métodos principales**:
  - `calcularEdadEnDias()`: Calcula edad en días
  - `calcularEdadEnMeses()`: Calcula edad en meses
  - `obtenerEdadActual()`: Obtiene edad actual completa

### **EstadoControlService**
- **Ubicación**: `app/Services/EstadoControlService.php`
- **Responsabilidad**: Determinar estados de controles
- **Métodos principales**:
  - `determinarEstado()`: Determina estado (CUMPLE/NO CUMPLE/SEGUIMIENTO)
  - `cumpleRango()`: Verifica si cumple rango
  - `obtenerInfoEstado()`: Obtiene información completa del estado

### **RangosCredService**
- **Ubicación**: `app/Services/RangosCredService.php`
- **Responsabilidad**: Definir y validar rangos de controles
- **Métodos principales**:
  - `getRangosRecienNacido()`: Rangos para controles RN
  - `getRangosCredMensual()`: Rangos para controles CRED
  - `validarControl()`: Valida si un control cumple su rango

---

## 🗄️ Repositorios

### **NinoRepository**
- **Ubicación**: `app/Repositories/NinoRepository.php`
- **Responsabilidad**: Acceso a datos de niños
- **Métodos principales**:
  - `getAll()`: Obtener todos los niños
  - `findById()`: Buscar por ID
  - `create()`: Crear nuevo niño
  - `update()`: Actualizar niño
  - `delete()`: Eliminar niño

### **ControlRepository**
- **Ubicación**: `app/Repositories/ControlRepository.php`
- **Responsabilidad**: Acceso a datos de controles
- **Métodos principales**:
  - `getCredByNino()`: Controles CRED de un niño
  - `getRnByNino()`: Controles RN de un niño
  - `createCred()`: Crear control CRED
  - `createRn()`: Crear control RN

---

## 🎮 Controladores

### **Estructura de Controladores API**
Los controladores API están organizados en `app/Http/Controllers/Api/`:

- **AlertasController**: Gestión de alertas
- **ControlCredController**: Gestión de controles CRED
- **ControlRnController**: Gestión de controles RN (a crear)

### **Principios de Controladores**
1. **Delgados**: Solo manejan HTTP, delegan lógica a Services
2. **Específicos**: Un controlador por entidad principal
3. **Validación**: Usan Form Requests para validación
4. **Inyección de Dependencias**: Reciben Services y Repositories por constructor

---

## ✅ Validaciones

### **Form Requests**
Las validaciones están centralizadas en `app/Http/Requests/`:

- **StoreNinoRequest**: Validación para crear/actualizar niños
- **StoreControlCredRequest**: Validación para controles CRED
- **StoreControlRnRequest**: Validación para controles RN

### **Ventajas**:
- Validación reutilizable
- Mensajes de error personalizados
- Separación de responsabilidades
- Fácil de testear

---

## 🚀 Mejores Prácticas

### **1. Separación de Responsabilidades**
- ✅ **Controllers**: Solo manejan HTTP
- ✅ **Services**: Contienen lógica de negocio
- ✅ **Repositories**: Acceso a datos
- ✅ **Models**: Solo representan entidades

### **2. Inyección de Dependencias**
```php
public function __construct(
    AlertasService $alertasService,
    NinoRepository $ninoRepository
) {
    $this->alertasService = $alertasService;
    $this->ninoRepository = $ninoRepository;
}
```

### **3. Uso de Form Requests**
```php
public function store(StoreControlCredRequest $request)
{
    // $request ya está validado
    $data = $request->validated();
}
```

### **4. Manejo de Errores**
```php
try {
    // Lógica
} catch (\Exception $e) {
    return response()->json([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ], 500);
}
```

### **5. Respuestas Consistentes**
```php
// Éxito
return response()->json([
    'success' => true,
    'data' => $data
]);

// Error
return response()->json([
    'success' => false,
    'message' => 'Error message'
], 400);
```

---

## 📈 Escalabilidad

### **Ventajas de esta Arquitectura**:

1. **Mantenibilidad**: Código organizado y fácil de entender
2. **Testabilidad**: Services y Repositories fáciles de testear
3. **Reutilización**: Lógica de negocio reutilizable
4. **Escalabilidad**: Fácil agregar nuevas funcionalidades
5. **Separación de Concerns**: Cada capa tiene una responsabilidad clara

### **Cómo Agregar Nueva Funcionalidad**:

1. **Crear Service** (si hay lógica de negocio)
2. **Crear Repository** (si hay acceso a datos)
3. **Crear Form Request** (si hay validaciones)
4. **Crear Controller** (si hay endpoints)
5. **Agregar Rutas** (en `routes/api.php`)

---

## 🔄 Migración del Código Existente

### **Paso 1: Refactorizar ApiController**
- Dividir `ApiController` en controladores específicos
- Mover lógica de negocio a Services
- Mover consultas a Repositories

### **Paso 2: Actualizar Rutas**
- Organizar rutas en archivos separados
- Agrupar rutas por funcionalidad

### **Paso 3: Actualizar Frontend**
- Actualizar llamadas API si cambian endpoints
- Mantener compatibilidad durante migración

---

## 📝 Notas Importantes

- **Compatibilidad**: Mantener compatibilidad con código existente durante la migración
- **Testing**: Agregar tests para Services y Repositories
- **Documentación**: Mantener documentación actualizada
- **Performance**: Monitorear performance después de refactorización

---

**Última actualización**: Diciembre 2024

