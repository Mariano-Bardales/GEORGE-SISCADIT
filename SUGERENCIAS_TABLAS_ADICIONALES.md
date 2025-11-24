# Sugerencias de Tablas Adicionales para SISCADIT

## Tablas Recomendadas

### 1. **establecimientos** (Normalización)
```sql
CREATE TABLE establecimientos (
    id_establecimiento INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(50) UNIQUE,
    nombre VARCHAR(150),
    codigo_red INT,
    codigo_microred VARCHAR(100),
    direccion TEXT,
    telefono VARCHAR(20),
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (codigo_red) REFERENCES redes(id_red)
);
```
**Razón**: Normalizar los establecimientos para evitar duplicados y facilitar búsquedas.

### 2. **redes** (Normalización)
```sql
CREATE TABLE redes (
    id_red INT PRIMARY KEY AUTO_INCREMENT,
    codigo INT UNIQUE,
    nombre VARCHAR(150),
    descripcion TEXT,
    activo BOOLEAN DEFAULT TRUE
);
```
**Razón**: Centralizar información de redes para mejor gestión.

### 3. **microredes** (Normalización)
```sql
CREATE TABLE microredes (
    id_microred INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(100) UNIQUE,
    nombre VARCHAR(150),
    id_red INT,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_red) REFERENCES redes(id_red)
);
```
**Razón**: Relacionar microredes con sus redes correspondientes.

### 4. **auditoria** (Logs del Sistema)
```sql
CREATE TABLE auditoria (
    id_auditoria INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    tabla_afectada VARCHAR(100),
    accion VARCHAR(50), -- INSERT, UPDATE, DELETE
    registro_id INT,
    datos_anteriores JSON,
    datos_nuevos JSON,
    fecha_hora DATETIME,
    ip_address VARCHAR(45),
    FOREIGN KEY (id_usuario) REFERENCES users(id)
);
```
**Razón**: Rastrear todos los cambios en el sistema para auditoría y seguridad.

### 5. **configuraciones** (Configuración del Sistema)
```sql
CREATE TABLE configuraciones (
    id_config INT PRIMARY KEY AUTO_INCREMENT,
    clave VARCHAR(100) UNIQUE,
    valor TEXT,
    tipo VARCHAR(50), -- string, integer, boolean, json
    descripcion TEXT,
    editable BOOLEAN DEFAULT TRUE
);
```
**Razón**: Configuraciones dinámicas del sistema sin necesidad de modificar código.

### 6. **notificaciones** (Sistema de Notificaciones)
```sql
CREATE TABLE notificaciones (
    id_notificacion INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    tipo VARCHAR(50), -- alerta, recordatorio, informacion
    titulo VARCHAR(200),
    mensaje TEXT,
    leida BOOLEAN DEFAULT FALSE,
    fecha_creacion DATETIME,
    fecha_lectura DATETIME,
    FOREIGN KEY (id_usuario) REFERENCES users(id)
);
```
**Razón**: Notificar a usuarios sobre alertas, recordatorios de controles, etc.

### 7. **alertas_niños** (Sistema de Alertas)
```sql
CREATE TABLE alertas_niños (
    id_alerta INT PRIMARY KEY AUTO_INCREMENT,
    id_niño INT,
    tipo_alerta VARCHAR(50), -- vacuna_pendiente, control_pendiente, tamizaje_pendiente
    mensaje TEXT,
    fecha_alerta DATETIME,
    fecha_vencimiento DATE,
    resuelta BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_niño) REFERENCES niños(id_niño)
);
```
**Razón**: Alertar sobre controles, vacunas o tamizajes pendientes.

### 8. **reportes** (Historial de Reportes)
```sql
CREATE TABLE reportes (
    id_reporte INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    tipo_reporte VARCHAR(100),
    parametros JSON,
    fecha_generacion DATETIME,
    archivo_path VARCHAR(255),
    FOREIGN KEY (id_usuario) REFERENCES users(id)
);
```
**Razón**: Guardar historial de reportes generados.

### 9. **permisos** (Sistema de Permisos Granulares)
```sql
CREATE TABLE permisos (
    id_permiso INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) UNIQUE,
    descripcion TEXT
);

CREATE TABLE rol_permisos (
    id_rol INT,
    id_permiso INT,
    PRIMARY KEY (id_rol, id_permiso),
    FOREIGN KEY (id_rol) REFERENCES roles(id),
    FOREIGN KEY (id_permiso) REFERENCES permisos(id_permiso)
);
```
**Razón**: Control granular de permisos por rol (más flexible que solo roles).

### 10. **sesiones_usuario** (Control de Sesiones)
```sql
CREATE TABLE sesiones_usuario (
    id_sesion INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    token VARCHAR(255) UNIQUE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    fecha_inicio DATETIME,
    fecha_ultima_actividad DATETIME,
    activa BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_usuario) REFERENCES users(id)
);
```
**Razón**: Control de sesiones activas y seguridad.

### 11. **backups** (Registro de Backups)
```sql
CREATE TABLE backups (
    id_backup INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(50), -- completo, incremental
    archivo_path VARCHAR(255),
    tamaño_mb DECIMAL(10,2),
    fecha_backup DATETIME,
    id_usuario INT,
    estado VARCHAR(20), -- completado, fallido, en_proceso
    FOREIGN KEY (id_usuario) REFERENCES users(id)
);
```
**Razón**: Registrar backups realizados del sistema.

### 12. **documentos_adjuntos** (Archivos Adjuntos)
```sql
CREATE TABLE documentos_adjuntos (
    id_documento INT PRIMARY KEY AUTO_INCREMENT,
    id_niño INT,
    tipo_documento VARCHAR(50), -- certificado, examen, otro
    nombre_archivo VARCHAR(255),
    ruta_archivo VARCHAR(500),
    tamaño_kb INT,
    fecha_subida DATETIME,
    id_usuario_subio INT,
    FOREIGN KEY (id_niño) REFERENCES niños(id_niño),
    FOREIGN KEY (id_usuario_subio) REFERENCES users(id)
);
```
**Razón**: Adjuntar documentos relacionados con los niños (certificados, exámenes, etc.).

## Prioridad de Implementación

### Alta Prioridad:
1. **establecimientos** - Normalización esencial
2. **redes** - Normalización esencial
3. **microredes** - Normalización esencial
4. **auditoria** - Seguridad y trazabilidad

### Media Prioridad:
5. **alertas_niños** - Funcionalidad importante
6. **notificaciones** - Mejora UX
7. **configuraciones** - Flexibilidad del sistema

### Baja Prioridad:
8. **permisos** - Si necesitas control más granular
9. **reportes** - Historial de reportes
10. **sesiones_usuario** - Control avanzado de sesiones
11. **backups** - Si haces backups automáticos
12. **documentos_adjuntos** - Si necesitas adjuntar archivos

## Notas Importantes

- Todas las tablas deben tener `timestamps` (created_at, updated_at)
- Considera agregar índices en campos de búsqueda frecuente
- Usa soft deletes si necesitas recuperar registros eliminados
- Considera agregar campos `activo` para desactivar sin eliminar

