# 📋 RESUMEN: SISTEMA DE ALERTAS Y USUARIOS

## 🚨 SISTEMA DE ALERTAS

### **¿Qué hace el sistema de alertas?**

El sistema de alertas detecta automáticamente anomalías en los controles de salud de los niños, comparando:
- **Edad actual del niño** (para detectar controles faltantes)
- **Edad al momento del control** (para validar si cumple el rango permitido)
- **Rangos de edad permitidos** para cada tipo de control
- **Controles registrados** en la base de datos

### **Tipos de Alertas que Detecta:**

1. **Controles Faltantes**
   - Detecta cuando un niño debería tener un control según su edad pero no está registrado
   - Ejemplo: Niño de 74 días sin el "Mes 2" (rango 60-89 días)

2. **Controles Fuera de Rango**
   - Detecta cuando un control fue realizado fuera del rango de edad permitido
   - Ejemplo: Control "Mes 1" realizado a los 25 días (debería ser entre 29-59 días)

3. **Controles Tardíos**
   - Detecta cuando ya pasó el límite máximo del rango y el control aún no se ha realizado
   - Ejemplo: Niño de 95 días sin el "Mes 1" (límite máximo: 59 días)

### **Rangos de Controles:**

**Controles Recién Nacido (CRN):**
- CRN1: 2-6 días
- CRN2: 7-13 días
- CRN3: 14-20 días
- CRN4: 21-28 días

**Controles CRED Mensual:**
- Mes 1: 29-59 días
- Mes 2: 60-89 días
- Mes 3: 90-119 días
- ... hasta Mes 11: 330-359 días

### **Ubicación en el Código:**
- **Archivo:** `app/Http/Controllers/ApiController.php`
- **Método principal:** `obtenerAlertas()` (líneas 1339-1762)
- **Ruta API:** `GET /api/alertas`

### **Prioridades de Alertas:**
- **Alta:** Control fuera de rango o control tardío (ya pasó el límite máximo)
- **Media:** Control faltante pero aún dentro del rango permitido

---

## 👥 SISTEMA DE USUARIOS

### **¿Qué hace el sistema de usuarios?**

El sistema de usuarios gestiona la autenticación y autorización de acceso al sistema, permitiendo diferentes roles con distintos niveles de permisos.

### **Roles Disponibles:**

1. **ADMIN / admin**
   - Administrador del sistema
   - Acceso completo a todas las funcionalidades
   - Método: `isAdmin()`

2. **JefeDeRed / jefe_de_red**
   - Jefe de Red de Salud
   - Método: `isJefeDeRed()`

3. **CoordinadorDeMicroRed / coordinador_de_microred**
   - Coordinador de Microred
   - Método: `isCoordinadorDeMicroRed()`

4. **usuario** (por defecto)
   - Usuario estándar del sistema

### **Funcionalidades del Sistema de Usuarios:**

1. **Autenticación**
   - Login con email y contraseña
   - Sesiones de usuario
   - Tokens de autenticación (Laravel Sanctum)

2. **Gestión de Roles**
   - Asignación de roles a usuarios
   - Validación de permisos según rol
   - Métodos helper para verificar roles

3. **Solicitudes de Acceso**
   - Los usuarios pueden crear solicitudes para acceder al sistema
   - Relación: `User` → `Solicitud` (uno a uno)

### **Datos del Usuario:**
- **Campos principales:**
  - `name`: Nombre del usuario
  - `email`: Correo electrónico (usado para login)
  - `password`: Contraseña (encriptada)
  - `role`: Rol del usuario (ADMIN, JefeDeRed, CoordinadorDeMicroRed, usuario)

### **Ubicación en el Código:**
- **Modelo:** `app/Models/User.php`
- **Controlador de Autenticación:** `app/Http/Controllers/Auth/LoginController.php`
- **Controlador de Usuarios:** `app/Http/Controllers/UsuarioController.php`
- **Tabla:** `users`

### **Usuarios por Defecto (Seeders):**
- **Administrador DIRESA:**
  - Email: `diresa@siscadit.com`
  - Password: `diresa123`
  - Rol: `admin`

- **Jefe de Red:**
  - Email: `jefedered@siscadit.com`
  - Password: `jefedered123`
  - Rol: `jefe_red`

- **Coordinador:**
  - Email: `coordinador@siscadit.com`
  - Password: `coordinador123`
  - Rol: `coordinador_de_microred`

---

## 🔗 RELACIÓN ENTRE ALERTAS Y USUARIOS

- Los **usuarios** acceden al sistema y pueden ver las **alertas** en el dashboard
- Las alertas se generan automáticamente para todos los niños registrados
- Los usuarios pueden filtrar y gestionar las alertas según su rol y permisos
- El dashboard muestra el total de alertas activas para todos los usuarios

---

## 📊 RESUMEN EJECUTIVO

| Aspecto | Sistema de Alertas | Sistema de Usuarios |
|---------|-------------------|-------------------|
| **Propósito** | Detectar anomalías en controles de salud | Gestionar acceso y permisos del sistema |
| **Funcionamiento** | Automático, basado en edad y rangos | Manual, requiere autenticación |
| **Frecuencia** | Se calcula en tiempo real al consultar | Persistente, basado en sesiones |
| **Tipos** | Faltantes, fuera de rango, tardíos | ADMIN, JefeDeRed, Coordinador, Usuario |
| **Ubicación** | `ApiController::obtenerAlertas()` | `User` model + `LoginController` |

