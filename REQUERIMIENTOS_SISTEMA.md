# 📋 REQUERIMIENTOS DEL SISTEMA SISCADIT
## Sistema de Control de Salud del Niño

---

## 🎯 PROPÓSITO DEL PROYECTO

El **Sistema de Control de Salud del Niño (SISCADIT)** es una plataforma web diseñada para la gestión integral de los controles de salud de niños menores de un año en establecimientos de salud. El sistema permite registrar, monitorear y evaluar el cumplimiento de los controles de salud establecidos por el Ministerio de Salud del Perú, facilitando la detección temprana de anomalías y el seguimiento oportuno de cada niño.

### **Objetivos Principales:**

1. **Centralizar la información** de todos los controles de salud infantil en una sola plataforma
2. **Automatizar la detección** de controles faltantes o fuera de rango permitido
3. **Facilitar la importación masiva** de datos desde archivos Excel
4. **Generar alertas automáticas** para controles que requieren atención inmediata
5. **Proporcionar estadísticas** y reportes para la toma de decisiones
6. **Garantizar el cumplimiento** de los protocolos de salud establecidos

---

## ✅ REQUERIMIENTOS FUNCIONALES

### **RF-01: Gestión de Niños**

**Descripción:** El sistema debe permitir registrar, consultar, editar y eliminar información de niños menores de un año.

**Funcionalidades:**
- Registrar datos básicos del niño (nombre, DNI, fecha de nacimiento, género, establecimiento)
- Registrar datos adicionales (red, microred, distrito, provincia, departamento, seguro, programa)
- Registrar datos de la madre (DNI, nombre, celular, domicilio)
- Consultar información completa de un niño
- Editar datos del niño y sus relaciones
- Eliminar registros de niños (con validaciones)

**Prioridad:** ALTA

---

### **RF-02: Gestión de Controles CRED Mensuales**

**Descripción:** El sistema debe permitir registrar y gestionar los 11 controles CRED mensuales (del mes 1 al mes 11).

**Funcionalidades:**
- Registrar controles CRED mensuales (1-11 controles)
- Validar que cada control se registre dentro del rango de edad permitido:
  - Control 1: 29-59 días
  - Control 2: 60-89 días
  - Control 3: 90-119 días
  - Control 4: 120-149 días
  - Control 5: 150-179 días
  - Control 6: 180-209 días
  - Control 7: 210-239 días
  - Control 8: 240-269 días
  - Control 9: 270-299 días
  - Control 10: 300-329 días
  - Control 11: 330-359 días
- Calcular automáticamente la edad en días al momento del control
- Determinar automáticamente el estado (CUMPLE/NO CUMPLE/SEGUIMIENTO)
- Editar controles ya registrados
- Eliminar controles registrados

**Prioridad:** ALTA

---

### **RF-03: Gestión de Controles de Recién Nacido**

**Descripción:** El sistema debe permitir registrar y gestionar los 4 controles de recién nacido (0-28 días).

**Funcionalidades:**
- Registrar controles de recién nacido (1-4 controles)
- Validar que cada control se registre dentro del rango de edad permitido:
  - Control 1: 2-6 días
  - Control 2: 7-13 días
  - Control 3: 14-20 días
  - Control 4: 21-28 días
- Calcular automáticamente la edad en días al momento del control
- Determinar automáticamente el estado (CUMPLE/NO CUMPLE/SEGUIMIENTO)
- Editar controles ya registrados
- Eliminar controles registrados

**Prioridad:** ALTA

---

### **RF-04: Gestión de Tamizaje Neonatal**

**Descripción:** El sistema debe permitir registrar y gestionar el tamizaje neonatal y tamizaje Galen.

**Funcionalidades:**
- Registrar fecha de tamizaje neonatal (debe realizarse antes de los 29 días)
- Registrar fecha de tamizaje Galen (opcional)
- Calcular automáticamente la edad en días al momento del tamizaje
- Determinar automáticamente si cumple (debe realizarse antes de los 29 días)
- Editar registros de tamizaje
- Eliminar registros de tamizaje

**Prioridad:** MEDIA

---

### **RF-05: Gestión de Vacunas del Recién Nacido**

**Descripción:** El sistema debe permitir registrar y gestionar las vacunas aplicadas al recién nacido.

**Funcionalidades:**
- Registrar fecha de aplicación de vacuna BCG
- Registrar fecha de aplicación de vacuna HVB (Hepatitis B)
- Calcular automáticamente la edad en días al momento de la vacunación
- Validar que las vacunas se apliquen en los primeros 2 días de vida
- Determinar automáticamente el estado (APLICADA/PENDIENTE)
- Editar registros de vacunas
- Eliminar registros de vacunas

**Prioridad:** MEDIA

---

### **RF-06: Gestión de Visitas Domiciliarias**

**Descripción:** El sistema debe permitir registrar y gestionar las visitas domiciliarias realizadas.

**Funcionalidades:**
- Registrar visitas domiciliarias con fecha y número de control
- Calcular automáticamente la edad en días al momento de la visita
- Determinar automáticamente el período de la visita basado en la edad:
  - 28 días de vida
  - 2-5 meses
  - 6-8 meses
  - 9-11 meses
- Editar registros de visitas
- Eliminar registros de visitas

**Prioridad:** MEDIA

---

### **RF-07: Gestión de Datos del Recién Nacido (CNV)**

**Descripción:** El sistema debe permitir registrar y gestionar los datos del Carné de Nacido Vivo (CNV).

**Funcionalidades:**
- Registrar peso al nacer (en gramos)
- Registrar edad gestacional (en semanas)
- Registrar clasificación del recién nacido
- Editar datos del CNV
- Eliminar datos del CNV

**Prioridad:** MEDIA

---

### **RF-08: Sistema de Alertas Automático**

**Descripción:** El sistema debe detectar automáticamente anomalías en los controles y generar alertas.

**Funcionalidades:**
- Detectar controles faltantes (que deberían estar registrados según la edad del niño)
- Detectar controles fuera de rango (registrados fuera del rango de edad permitido)
- Detectar controles que están próximos a vencer (dentro del rango pero aún no registrados)
- Generar alertas con información detallada:
  - Nombre del niño
  - DNI
  - Edad actual
  - Tipo de control afectado
  - Estado del control
  - Sugerencias de acción
- Mostrar alertas en el dashboard
- Filtrar alertas por tipo y estado
- Marcar alertas como resueltas

**Prioridad:** ALTA

---

### **RF-09: Cálculo Automático de Estados**

**Descripción:** El sistema debe calcular automáticamente el estado de cada control basándose en rangos de edad.

**Funcionalidades:**
- Calcular edad en días desde la fecha de nacimiento hasta la fecha del control
- Comparar la edad del control con el rango permitido
- Asignar estado automáticamente:
  - **CUMPLE:** Control registrado dentro del rango permitido
  - **NO CUMPLE:** Control registrado fuera del rango o control faltante que ya venció
  - **SEGUIMIENTO:** Control no registrado pero aún dentro del plazo
- Recalcular estados cuando se actualiza un control
- Recalcular estados cuando cambia la fecha actual

**Prioridad:** ALTA

---

### **RF-10: Importación Masiva de Datos**

**Descripción:** El sistema debe permitir importar datos desde archivos Excel con múltiples hojas.

**Funcionalidades:**
- Importar datos desde archivo Excel (.xlsx)
- Procesar múltiples hojas en un solo archivo:
  - Niños
  - Datos Extra
  - Madre
  - Controles RN
  - Controles CRED
  - Tamizaje
  - Vacunas
  - Visitas
  - Recién Nacido (CNV)
- Validar formato de datos antes de importar
- Validar relaciones entre datos (ej: id_niño debe existir)
- Procesar la hoja "Niños" primero (obligatoria)
- Manejar IDs personalizados del Excel
- Actualizar registros existentes si ya existen
- Crear nuevos registros si no existen
- Mostrar reporte de importación (creados, actualizados, errores)
- Manejar errores de importación de forma controlada

**Prioridad:** ALTA

---

### **RF-11: Dashboard y Estadísticas**

**Descripción:** El sistema debe proporcionar un dashboard con estadísticas y resúmenes.

**Funcionalidades:**
- Mostrar total de niños registrados
- Mostrar total de alertas activas
- Mostrar últimos 10 controles CRED registrados
- Mostrar estadísticas de cumplimiento de controles
- Mostrar gráficos y visualizaciones de datos
- Filtrar estadísticas por establecimiento, fecha, etc.

**Prioridad:** MEDIA

---

### **RF-12: Autenticación y Autorización**

**Descripción:** El sistema debe controlar el acceso mediante autenticación y autorización.

**Funcionalidades:**
- Iniciar sesión con credenciales de usuario
- Cerrar sesión
- Proteger rutas que requieren autenticación
- Diferenciar roles de usuario (si aplica)
- Validar permisos para acciones específicas

**Prioridad:** ALTA

---

## 🔧 REQUERIMIENTOS NO FUNCIONALES

### **RNF-01: Rendimiento**

**Descripción:** El sistema debe responder en tiempos aceptables.

**Especificaciones:**
- Tiempo de carga de páginas: < 3 segundos
- Tiempo de respuesta de API: < 1 segundo
- Tiempo de importación de archivo Excel (1000 registros): < 30 segundos
- Tiempo de cálculo de alertas: < 5 segundos

**Prioridad:** ALTA

---

### **RNF-02: Escalabilidad**

**Descripción:** El sistema debe poder manejar un crecimiento en la cantidad de datos.

**Especificaciones:**
- Soportar al menos 10,000 registros de niños
- Soportar al menos 100,000 registros de controles
- Manejar importaciones de archivos Excel de hasta 5,000 filas
- Optimizar consultas a la base de datos

**Prioridad:** MEDIA

---

### **RNF-03: Disponibilidad**

**Descripción:** El sistema debe estar disponible para su uso.

**Especificaciones:**
- Disponibilidad del 95% del tiempo
- Tolerancia a fallos menores sin pérdida de datos
- Recuperación automática de errores de conexión a base de datos

**Prioridad:** MEDIA

---

### **RNF-04: Usabilidad**

**Descripción:** El sistema debe ser fácil de usar para los usuarios finales.

**Especificaciones:**
- Interfaz intuitiva y clara
- Mensajes de error descriptivos
- Validaciones en tiempo real
- Confirmaciones para acciones destructivas
- Ayuda contextual y tooltips
- Diseño responsive (adaptable a diferentes tamaños de pantalla)

**Prioridad:** ALTA

---

### **RNF-05: Seguridad**

**Descripción:** El sistema debe proteger los datos y el acceso.

**Especificaciones:**
- Autenticación segura (hash de contraseñas)
- Protección CSRF en formularios
- Validación de entrada de datos
- Sanitización de datos de salida
- Protección contra inyección SQL (usar Eloquent ORM)
- Protección contra XSS (Cross-Site Scripting)
- Logs de auditoría para acciones críticas

**Prioridad:** ALTA

---

### **RNF-06: Mantenibilidad**

**Descripción:** El código debe ser fácil de mantener y extender.

**Especificaciones:**
- Código bien estructurado y documentado
- Separación de responsabilidades (MVC)
- Uso de patrones de diseño apropiados
- Comentarios en código complejo
- Nombres de variables y funciones descriptivos
- Reutilización de código (traits, helpers)

**Prioridad:** MEDIA

---

### **RNF-07: Compatibilidad**

**Descripción:** El sistema debe funcionar en diferentes entornos.

**Especificaciones:**
- Compatible con navegadores modernos (Chrome, Firefox, Edge, Safari)
- Compatible con PHP 8.0 o superior
- Compatible con MySQL 5.7 o superior
- Compatible con servidores web (Apache, Nginx)

**Prioridad:** MEDIA

---

### **RNF-08: Integridad de Datos**

**Descripción:** El sistema debe garantizar la integridad de los datos.

**Especificaciones:**
- Validación de datos antes de guardar
- Transacciones de base de datos para operaciones críticas
- Relaciones de integridad referencial (foreign keys)
- Validación de rangos de edad y fechas
- Prevención de duplicados
- Validación de formatos de datos (fechas, números, etc.)

**Prioridad:** ALTA

---

### **RNF-09: Confiabilidad**

**Descripción:** El sistema debe ser confiable y preciso en sus cálculos.

**Especificaciones:**
- Cálculos de edad precisos (considerando años bisiestos)
- Validación de rangos de edad correcta
- Detección de alertas precisa
- Manejo de errores sin pérdida de datos
- Logs de errores para depuración

**Prioridad:** ALTA

---

### **RNF-10: Portabilidad**

**Descripción:** El sistema debe poder ejecutarse en diferentes plataformas.

**Especificaciones:**
- Compatible con Windows, Linux, macOS
- Uso de tecnologías estándar (Laravel, MySQL, JavaScript)
- Configuración mediante archivos de entorno (.env)
- Sin dependencias de plataforma específica

**Prioridad:** BAJA

---

## 📊 RESUMEN DE PRIORIDADES

### **Requerimientos Funcionales de ALTA Prioridad:**
- RF-01: Gestión de Niños
- RF-02: Gestión de Controles CRED Mensuales
- RF-03: Gestión de Controles de Recién Nacido
- RF-08: Sistema de Alertas Automático
- RF-09: Cálculo Automático de Estados
- RF-10: Importación Masiva de Datos
- RF-12: Autenticación y Autorización

### **Requerimientos No Funcionales de ALTA Prioridad:**
- RNF-01: Rendimiento
- RNF-04: Usabilidad
- RNF-05: Seguridad
- RNF-08: Integridad de Datos
- RNF-09: Confiabilidad

---

*Documento generado para el Sistema SISCADIT - Versión 1.0*
