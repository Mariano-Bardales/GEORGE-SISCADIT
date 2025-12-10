# 📊 Análisis Completo de las Tablas de la Base de Datos

## 📋 Resumen Ejecutivo

Este documento analiza **TODAS** las tablas de la base de datos del sistema SISCADIT, explicando el propósito de cada una y si son necesarias o no.

---

## 🔍 Tablas del Sistema

### 1. **`users`** ✅ **NECESARIA**

**Propósito:**
- Almacena los usuarios del sistema (administradores, jefes de red, coordinadores)
- Contiene información de autenticación (email, password)
- Define el rol de cada usuario (`role`)

**Campos principales:**
- `id` - Identificador único
- `name` - Nombre del usuario
- `email` - Correo electrónico (único)
- `password` - Contraseña hasheada
- `role` - Rol del usuario (ADMIN, JefeDeRed, CoordinadorDeMicroRed)
- `email_verified_at` - Fecha de verificación de email (nullable)
- `remember_token` - Token para "Recordarme"

**Uso en el sistema:**
- ✅ Autenticación de usuarios
- ✅ Control de acceso basado en roles
- ✅ Gestión de usuarios en el dashboard

**¿Es necesaria?** ✅ **SÍ** - Es fundamental para el sistema de autenticación.

---

### 2. **`password_reset_tokens`** ⚠️ **PROBABLEMENTE NO NECESARIA**

**Propósito:**
- Laravel la usa para almacenar tokens temporales cuando un usuario solicita restablecer su contraseña
- Almacena el email y un token único con fecha de creación
- Los tokens expiran después de 60 minutos (configurado en `config/auth.php`)

**Campos:**
- `email` - Email del usuario (primary key)
- `token` - Token único para resetear contraseña
- `created_at` - Fecha de creación del token

**Uso en el sistema:**
- ❌ **NO se usa actualmente** - No hay funcionalidad de "Olvidé mi contraseña" implementada
- ⚠️ Configurada en `config/auth.php` pero sin controladores ni rutas

**¿Es necesaria?** ⚠️ **NO ACTUALMENTE**
- Si no planeas implementar "Olvidé mi contraseña", puedes eliminarla
- Si planeas implementarla en el futuro, déjala

**Recomendación:** 
- Si no hay planes de implementar reset de contraseña → **ELIMINAR**
- Si planeas implementarlo → **MANTENER**

---

### 3. **`personal_access_tokens`** ⚠️ **PROBABLEMENTE NO NECESARIA**

**Propósito:**
- Laravel Sanctum la usa para autenticación de APIs mediante tokens
- Permite generar tokens de acceso para aplicaciones móviles o APIs externas
- Almacena tokens con permisos (abilities) y fecha de expiración

**Campos:**
- `id` - Identificador único
- `tokenable_type` - Tipo de modelo (ej: App\Models\User)
- `tokenable_id` - ID del modelo
- `name` - Nombre del token (ej: "mobile-app")
- `token` - Token único (64 caracteres)
- `abilities` - Permisos del token (JSON)
- `last_used_at` - Última vez que se usó el token
- `expires_at` - Fecha de expiración
- `created_at`, `updated_at` - Timestamps

**Uso en el sistema:**
- ❌ **NO se usa actualmente** - El modelo `User` tiene `HasApiTokens` pero no hay:
  - Rutas de API que requieran tokens
  - Controladores que generen tokens
  - Aplicaciones móviles o externas que consuman la API

**¿Es necesaria?** ⚠️ **NO ACTUALMENTE**
- Si no planeas crear una API con autenticación por tokens, puedes eliminarla
- Si planeas crear una app móvil o API externa, déjala

**Recomendación:**
- Si no hay planes de API con tokens → **ELIMINAR**
- Si planeas crear API móvil/externa → **MANTENER**

---

### 4. **`failed_jobs`** ⚠️ **PROBABLEMENTE NO NECESARIA**

**Propósito:**
- Laravel la usa para almacenar trabajos en cola (queues) que fallaron
- Útil para debugging y reintentos de trabajos fallidos

**Campos:**
- `id` - Identificador único
- `uuid` - UUID único del trabajo
- `connection` - Conexión de la cola
- `queue` - Nombre de la cola
- `payload` - Datos del trabajo (JSON)
- `exception` - Mensaje de error
- `failed_at` - Fecha del fallo

**Uso en el sistema:**
- ❌ **NO se usa actualmente** - No hay trabajos en cola configurados
- No hay uso de `Queue::push()` o trabajos asíncronos

**¿Es necesaria?** ⚠️ **NO ACTUALMENTE**
- Si no usas colas de trabajos (queues), puedes eliminarla
- Si planeas usar trabajos asíncronos (emails, reportes, etc.), déjala

**Recomendación:**
- Si no usas queues → **ELIMINAR**
- Si planeas usar queues → **MANTENER**

---

### 5. **`solicitudes`** ✅ **NECESARIA**

**Propósito:**
- Almacena las solicitudes de registro de nuevos usuarios
- Contiene información del solicitante (DNI, establecimiento, motivo, etc.)
- Tiene estados: `pendiente`, `aprobada`, `rechazada`

**Campos principales:**
- `id` - Identificador único
- `id_tipo_documento` - Tipo de documento
- `numero_documento` - Número de DNI
- `codigo_red`, `codigo_microred`, `id_establecimiento` - Códigos del establecimiento
- `motivo` - Motivo de la solicitud
- `cargo` - Cargo del solicitante
- `celular`, `correo` - Datos de contacto
- `accept_terms` - Aceptación de términos
- `estado` - Estado de la solicitud
- `user_id` - ID del usuario asociado (si fue aprobada)

**Uso en el sistema:**
- ✅ Gestión de solicitudes en "Gestión de Usuarios"
- ✅ Aprobación/rechazo de solicitudes
- ✅ Creación de usuarios desde solicitudes aprobadas

**¿Es necesaria?** ✅ **SÍ** - Es fundamental para el flujo de registro de usuarios.

---

### 6. **`ninos`** ✅ **NECESARIA**

**Propósito:**
- Almacena la información principal de los niños registrados en el sistema
- Es la tabla central del sistema CRED

**Campos principales:**
- `id` - Identificador único
- `id_madre` - ID de la madre (foreign key)
- `establecimiento` - Establecimiento de salud
- `tipo_doc`, `numero_doc` - Documento de identidad
- `apellidos_nombres` - Nombre completo
- `fecha_nacimiento` - Fecha de nacimiento (usada para calcular edad)
- `genero` - Género del niño

**Uso en el sistema:**
- ✅ Tabla central del sistema
- ✅ Relacionada con todas las demás tablas de controles
- ✅ Dashboard principal muestra esta información

**¿Es necesaria?** ✅ **SÍ** - Es la tabla más importante del sistema.

---

### 7. **`madres`** ✅ **NECESARIA**

**Propósito:**
- Almacena información de las madres de los niños
- Relacionada con `ninos` mediante `id_madre`

**Campos principales:**
- `id` - Identificador único
- `dni` - DNI de la madre
- `apellidos_nombres` - Nombre completo
- `celular` - Teléfono de contacto
- `domicilio`, `referencia_direccion` - Dirección

**Uso en el sistema:**
- ✅ Información de contacto y ubicación
- ✅ Relación con niños
- ✅ Alertas de datos faltantes

**¿Es necesaria?** ✅ **SÍ** - Información esencial del sistema CRED.

---

### 8. **`datos_extras`** ✅ **NECESARIA**

**Propósito:**
- Almacena información adicional del niño (ubicación geográfica, seguro, programa)
- Relacionada con `ninos` mediante `id_niño`

**Campos principales:**
- `id` - Identificador único
- `id_niño` - ID del niño (foreign key)
- `red`, `microred` - Red y microred de salud
- `eess_nacimiento` - Establecimiento de nacimiento
- `distrito`, `provincia`, `departamento` - Ubicación geográfica
- `seguro` - Tipo de seguro
- `programa` - Programa de salud

**Uso en el sistema:**
- ✅ Información geográfica y administrativa
- ✅ Alertas de datos faltantes
- ✅ Reportes y filtros

**¿Es necesaria?** ✅ **SÍ** - Información importante para reportes y gestión.

---

### 9. **`recien_nacidos`** ✅ **NECESARIA**

**Propósito:**
- Almacena datos del Control Recién Nacido (CNV)
- Contiene peso, edad gestacional y clasificación

**Campos principales:**
- `id` - Identificador único
- `id_niño` - ID del niño (foreign key)
- `peso` - Peso al nacer (decimal)
- `edad_gestacional` - Edad gestacional en semanas
- `clasificacion` - Clasificación (AEG, PEG, GEG)

**Uso en el sistema:**
- ✅ Control CNV (uno de los 6 tipos de controles)
- ✅ Alertas de datos faltantes
- ✅ Dashboard de controles

**¿Es necesaria?** ✅ **SÍ** - Uno de los 6 controles esenciales.

---

### 10. **`tamizaje_neonatals`** ✅ **NECESARIA**

**Propósito:**
- Almacena datos del Tamizaje Neonatal
- Contiene fechas de tamizaje y resultados

**Campos principales:**
- `id` - Identificador único
- `id_niño` - ID del niño (foreign key)
- `fecha_tam_neo` - Fecha del tamizaje neonatal
- `galen_fecha_tam_feo` - Fecha del tamizaje de fenilcetonuria

**Uso en el sistema:**
- ✅ Control Tamizaje (uno de los 6 tipos de controles)
- ✅ Alertas de datos faltantes
- ✅ Dashboard de controles

**¿Es necesaria?** ✅ **SÍ** - Uno de los 6 controles esenciales.

---

### 11. **`vacuna_rns`** ✅ **NECESARIA**

**Propósito:**
- Almacena datos de vacunación del Recién Nacido
- Contiene fechas de aplicación de BCG y HVB

**Campos principales:**
- `id` - Identificador único
- `id_niño` - ID del niño (foreign key)
- `fecha_bcg` - Fecha de aplicación de BCG
- `fecha_hvb` - Fecha de aplicación de HVB

**Uso en el sistema:**
- ✅ Control Vacunas (uno de los 6 tipos de controles)
- ✅ Alertas de datos faltantes
- ✅ Dashboard de controles
- ✅ Estado calculado dinámicamente (0-2 días)

**¿Es necesaria?** ✅ **SÍ** - Uno de los 6 controles esenciales.

---

### 12. **`control_rns`** ✅ **NECESARIA**

**Propósito:**
- Almacena los 4 controles de Recién Nacido (Control 1, 2, 3, 4)
- Cada niño puede tener hasta 4 controles RN

**Campos principales:**
- `id` - Identificador único
- `id_niño` - ID del niño (foreign key)
- `numero_control` - Número del control (1, 2, 3, 4)
- `fecha` - Fecha del control

**Uso en el sistema:**
- ✅ Controles RN (uno de los 6 tipos de controles)
- ✅ Alertas de controles faltantes
- ✅ Dashboard de controles
- ✅ Estado calculado dinámicamente según rangos de edad

**¿Es necesaria?** ✅ **SÍ** - Uno de los 6 controles esenciales.

---

### 13. **`control_menor1s`** ✅ **NECESARIA**

**Propósito:**
- Almacena los 11 controles CRED mensuales (Control 1 al 11)
- Cada niño puede tener hasta 11 controles CRED

**Campos principales:**
- `id` - Identificador único
- `id_niño` - ID del niño (foreign key)
- `numero_control` - Número del control (1 al 11)
- `fecha` - Fecha del control

**Uso en el sistema:**
- ✅ Controles CRED (uno de los 6 tipos de controles)
- ✅ Alertas de controles faltantes
- ✅ Dashboard de controles
- ✅ Estado calculado dinámicamente según rangos de edad

**¿Es necesaria?** ✅ **SÍ** - Uno de los 6 controles esenciales.

---

### 14. **`visita_domiciliarias`** ✅ **NECESARIA**

**Propósito:**
- Almacena las 4 visitas domiciliarias (Visita 1, 2, 3, 4)
- Cada niño puede tener hasta 4 visitas domiciliarias

**Campos principales:**
- `id` - Identificador único
- `id_niño` - ID del niño (foreign key)
- `control_de_visita` - Número del control (1, 2, 3, 4)
- `fecha_visita` - Fecha de la visita

**Uso en el sistema:**
- ✅ Visitas Domiciliarias (uno de los 6 tipos de controles)
- ✅ Alertas de visitas faltantes
- ✅ Dashboard de controles
- ✅ Estado calculado dinámicamente según rangos de edad

**¿Es necesaria?** ✅ **SÍ** - Uno de los 6 controles esenciales.

---

## 📊 Resumen de Tablas

### ✅ **Tablas NECESARIAS (11):**
1. `users` - Usuarios del sistema
2. `solicitudes` - Solicitudes de registro
3. `ninos` - Niños registrados
4. `madres` - Madres de los niños
5. `datos_extras` - Datos adicionales
6. `recien_nacidos` - Control CNV
7. `tamizaje_neonatals` - Control Tamizaje
8. `vacuna_rns` - Control Vacunas
9. `control_rns` - Controles RN
10. `control_menor1s` - Controles CRED
11. `visita_domiciliarias` - Visitas Domiciliarias

### ⚠️ **Tablas PROBABLEMENTE NO NECESARIAS (3):**
1. `password_reset_tokens` - Reset de contraseñas (no implementado)
2. `personal_access_tokens` - API tokens (no usado)
3. `failed_jobs` - Trabajos fallidos (no usa queues)

---

## 🎯 Recomendaciones

### **Si NO planeas implementar estas funcionalidades:**

1. **Eliminar `password_reset_tokens`:**
   - Si no habrá "Olvidé mi contraseña"
   - Crear migración para eliminarla

2. **Eliminar `personal_access_tokens`:**
   - Si no habrá API móvil o externa
   - Remover `HasApiTokens` del modelo `User`
   - Crear migración para eliminarla

3. **Eliminar `failed_jobs`:**
   - Si no usarás colas de trabajos
   - Crear migración para eliminarla

### **Si SÍ planeas implementar estas funcionalidades:**

- **Mantener todas las tablas** - Son parte del framework Laravel y pueden ser útiles en el futuro

---

## 📝 Notas Importantes

1. **`password_reset_tokens`** y **`personal_access_tokens`** son tablas estándar de Laravel que vienen por defecto
2. Si las eliminas, asegúrate de:
   - Remover referencias en código (`HasApiTokens`, configuraciones)
   - Crear migraciones de rollback
   - Documentar la decisión

3. **`failed_jobs`** solo es útil si usas `Queue::push()` o trabajos asíncronos

---

**Fecha:** Diciembre 2024  
**Versión:** 1.0


