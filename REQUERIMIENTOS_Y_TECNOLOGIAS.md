# 📋 Requerimientos Funcionales y No Funcionales del Sistema SISCADIT

## 🎯 REQUERIMIENTOS FUNCIONALES

### RF-01: Gestión de Niños (ALTA)
**Descripción**: Permite administrar los datos de los niños y su madre.

**Funcionalidades**:
- ✅ **Registrar niño**: Crear nuevo registro con datos completos
- ✅ **Consultar niños**: Visualizar lista de niños con búsqueda y filtros
- ✅ **Eliminar niño**: Eliminar registro de niño (solo admin)
- ❌ **Editar niño**: Los datos del niño NO pueden ser editados una vez registrados
- ✅ **Datos del niño**: nombre, DNI, fecha de nacimiento, género, establecimiento
- ✅ **Datos adicionales**: red, microred, distrito, provincia, departamento, seguro, programa
- ✅ **Datos de la madre**: DNI, nombre, celular, domicilio
- ✅ **Validaciones de integridad**: Validación de documentos, fechas, etc.

**Nota**: Si es necesario corregir información, se debe eliminar y volver a registrar, o usar la importación masiva que actualiza datos existentes.

---

### RF-02: Gestión de Controles CRED (ALTA)
**Descripción**: Permite registrar y gestionar controles de crecimiento y desarrollo mensuales.

**Funcionalidades**:
- ✅ **Registrar control CRED mensual**: Control 1 al 11
- ✅ **Editar control CRED**: Modificar controles existentes
- ✅ **Visualizar controles**: Ver todos los controles de un niño
- ✅ **Cálculo automático de edad en días**: Basado en fecha de control y fecha de nacimiento
- ✅ **Validación de rangos de edad**: Verificar que el control esté dentro del rango permitido
- ✅ **Campos**: `id_niño`, `nro_control` (1-11), `fecha_control`

---

### RF-03: Gestión de Controles Recién Nacido (ALTA)
**Descripción**: Permite registrar controles del recién nacido.

**Funcionalidades**:
- ✅ **Registrar control RN**: Control del recién nacido
- ✅ **Editar control RN**: Modificar controles existentes
- ✅ **Visualizar controles**: Ver controles RN de un niño
- ✅ **Campos**: `id_niño`, `numero_control`, `fecha_control`, `peso`, `talla`, `perimetro_cefalico`

---

### RF-04: Gestión de Tamizaje Neonatal (MEDIA)
**Descripción**: Permite registrar y gestionar tamizajes neonatales.

**Funcionalidades**:
- ✅ **Registrar tamizaje**: Fecha de tamizaje neonatal y fecha de tamizaje FEO
- ✅ **Editar tamizaje**: Modificar tamizajes existentes
- ✅ **Visualizar tamizajes**: Ver tamizajes de un niño
- ✅ **Campos**: `id_niño`, `numero_control`, `fecha_tam_neo`, `galen_fecha_tam_feo`

---

### RF-05: Gestión de Vacunas RN (MEDIA)
**Descripción**: Permite registrar y gestionar vacunas del recién nacido.

**Funcionalidades**:
- ✅ **Registrar vacunas**: BCG y HVB
- ✅ **Editar vacunas**: Modificar vacunas existentes
- ✅ **Visualizar vacunas**: Ver vacunas de un niño
- ✅ **Campos**: `id_niño`, `numero_control`, `fecha_bcg`, `fecha_hvb`

---

### RF-06: Gestión de Visitas Domiciliarias (MEDIA)
**Descripción**: Permite registrar y gestionar visitas domiciliarias.

**Funcionalidades**:
- ✅ **Registrar visita**: Visita domiciliaria con número de control
- ✅ **Editar visita**: Modificar visitas existentes
- ✅ **Visualizar visitas**: Ver visitas de un niño
- ✅ **Cálculo automático de grupo de visita**: Basado en la edad del niño
- ✅ **Campos**: `id_niño`, `numero_control`, `fecha_visita`

---

### RF-07: Gestión de Recién Nacido (CNV) (MEDIA)
**Descripción**: Permite registrar datos del recién nacido.

**Funcionalidades**:
- ✅ **Registrar CNV**: Datos del recién nacido
- ✅ **Editar CNV**: Modificar datos existentes
- ✅ **Visualizar CNV**: Ver datos de recién nacido
- ✅ **Campos**: `id_niño`, `peso` (en gramos), `talla`, `perimetro_cefalico`, `apgar`, etc.

---

### RF-08: Sistema de Alertas Automático (ALTA)
**Descripción**: Detecta automáticamente anomalías en los controles basándose en rangos de edad.

**Funcionalidades**:
- ✅ **Detección automática**: Calcula si un control cumple o no cumple según rangos CRED
- ✅ **Estado de control**: 
  - **CUMPLE**: Control dentro del rango permitido
  - **NO CUMPLE**: Control fuera del rango permitido
  - **SEGUIMIENTO**: Control aún no registrado pero dentro del rango válido
- ✅ **Cálculo de edad en días**: Fecha de control - Fecha de nacimiento
- ✅ **Validación de rangos**: Compara edad en días con rangos permitidos por control
- ✅ **Visualización de alertas**: Dashboard muestra alertas detectadas
- ✅ **Alertas por tipo**: Alertas específicas para cada tipo de control

**Lógica**:
- El sistema calcula automáticamente la edad en días del niño al momento del control
- Compara esta edad con los rangos permitidos para cada número de control (1-11)
- Si la edad está fuera del rango, marca como "NO CUMPLE" y genera alerta
- Si el control no está registrado pero aún está en rango, marca como "SEGUIMIENTO"

---

### RF-09: Importación Masiva de Datos (ALTA)
**Descripción**: Permite importar datos desde archivos Excel o CSV.

**Funcionalidades**:
- ✅ **Importar desde Excel**: Archivos .xlsx y .xls
- ✅ **Importar desde CSV**: Archivos .csv
- ✅ **Múltiples hojas**: Soporta archivos Excel con múltiples hojas
- ✅ **Hojas soportadas**:
  - Niños (obligatoria)
  - Datos Extra
  - Madre
  - Controles RN
  - Controles CRED
  - Tamizaje Neonatal
  - Vacunas RN
  - Visitas Domiciliarias
  - Recién Nacido (CNV)
- ✅ **Actualización de datos**: Si el registro existe, lo actualiza; si no, lo crea
- ✅ **Validación de datos**: Valida formato y tipos de datos antes de importar
- ✅ **Reporte de importación**: Muestra estadísticas de registros creados/actualizados
- ✅ **Manejo de errores**: Identifica y reporta errores durante la importación
- ✅ **Reorganización de IDs**: Reorganiza IDs después de la importación

---

### RF-10: Gestión de Usuarios y Roles (MEDIA)
**Descripción**: Permite gestionar usuarios del sistema con diferentes niveles de acceso.

**Funcionalidades**:
- ✅ **Autenticación**: Login y logout
- ✅ **Roles de usuario**:
  - **Admin (DIRESA)**: Acceso completo al sistema
  - **Jefe de Red**: Acceso limitado
  - **Coordinador de Microred**: Acceso limitado
- ✅ **Gestión de solicitudes**: Los usuarios pueden solicitar acceso al sistema
- ✅ **Aprobación de solicitudes**: Los administradores pueden aprobar/rechazar solicitudes
- ✅ **CRUD de usuarios**: Crear, leer, actualizar y eliminar usuarios (solo admin)

---

### RF-11: Dashboard y Estadísticas (MEDIA)
**Descripción**: Proporciona una vista general del sistema con estadísticas y gráficos.

**Funcionalidades**:
- ✅ **Total de niños registrados**: Contador de niños en el sistema
- ✅ **Total de usuarios activos**: Contador de usuarios
- ✅ **Total de alertas activas**: Contador de alertas detectadas
- ✅ **Últimos 10 controles CRED**: Tabla con los últimos controles registrados
- ✅ **Gráficos**: Distribución por género
- ❌ **Estadísticas de cumplimiento**: NO implementado
- ❌ **Filtros por fecha y establecimiento**: NO implementado

---

### RF-12: Búsqueda y Filtrado (MEDIA)
**Descripción**: Permite buscar y filtrar registros en el sistema.

**Funcionalidades**:
- ✅ **Búsqueda por nombre**: Buscar niños por nombre o apellidos
- ✅ **Búsqueda por documento**: Buscar por número de documento
- ✅ **Filtro por género**: Filtrar por género (Masculino/Femenino/Todos)
- ✅ **Paginación**: Navegación por páginas de resultados
- ✅ **Registros por página**: Configurable (10, 15, 25, 50, 100)

---

## 🔒 REQUERIMIENTOS NO FUNCIONALES

### RNF-01: Rendimiento (ALTA)
- ✅ **Tiempo de respuesta**: Las consultas deben responder en menos de 2 segundos
- ✅ **Carga de datos**: Paginación para manejar grandes volúmenes de datos
- ✅ **Optimización de consultas**: Uso de índices en base de datos
- ✅ **Caché**: Uso de caché para datos frecuentemente consultados

---

### RNF-02: Seguridad (ALTA)
- ✅ **Autenticación**: Sistema de login con credenciales
- ✅ **Autorización**: Control de acceso basado en roles
- ✅ **Protección CSRF**: Tokens CSRF en formularios
- ✅ **Validación de datos**: Validación en frontend y backend
- ✅ **Sanitización**: Limpieza de datos de entrada
- ✅ **Contraseñas encriptadas**: Hash de contraseñas (bcrypt)
- ✅ **Sesiones seguras**: Manejo seguro de sesiones

---

### RNF-03: Usabilidad (MEDIA)
- ✅ **Interfaz intuitiva**: Diseño claro y fácil de usar
- ✅ **Navegación clara**: Menú lateral con rutas definidas
- ✅ **Feedback visual**: Mensajes de éxito/error claros
- ✅ **Responsive**: Adaptable a diferentes tamaños de pantalla
- ✅ **Accesibilidad**: Uso de etiquetas semánticas y ARIA

---

### RNF-04: Mantenibilidad (MEDIA)
- ✅ **Código organizado**: Estructura MVC de Laravel
- ✅ **Documentación**: Comentarios en código crítico
- ✅ **Versionado**: Control de versiones con Git
- ✅ **Separación de responsabilidades**: Controllers, Models, Services

---

### RNF-05: Escalabilidad (MEDIA)
- ✅ **Arquitectura modular**: Separación en módulos
- ✅ **Base de datos normalizada**: Estructura relacional optimizada
- ✅ **Servicios reutilizables**: Lógica de negocio en Services
- ✅ **Repositorios**: Abstracción de acceso a datos

---

### RNF-06: Compatibilidad (BAJA)
- ✅ **Navegadores**: Compatible con Chrome, Firefox, Edge (últimas versiones)
- ✅ **PHP**: Requiere PHP 8.1 o superior
- ✅ **Base de datos**: MySQL 5.7+ o MariaDB 10.3+

---

### RNF-07: Confiabilidad (ALTA)
- ✅ **Manejo de errores**: Try-catch en operaciones críticas
- ✅ **Transacciones**: Uso de transacciones de base de datos
- ✅ **Validación robusta**: Validación exhaustiva de datos
- ✅ **Logs**: Registro de errores y operaciones importantes

---

### RNF-08: Portabilidad (MEDIA)
- ✅ **Independencia de plataforma**: Funciona en Windows, Linux, macOS
- ✅ **Configuración flexible**: Variables de entorno (.env)
- ✅ **Migraciones**: Sistema de migraciones de base de datos

---

# 🎨 TECNOLOGÍAS DEL FRONTEND

## 📦 Stack Tecnológico Frontend

### 1. **Blade Templates (Laravel)**
- **Versión**: Laravel 10 (incluido)
- **Uso**: Motor de plantillas del servidor para renderizar HTML
- **Archivos**: `resources/views/**/*.blade.php`
- **Características**:
  - Componentes Blade (`<x-sidebar-main>`)
  - Directivas (`@if`, `@foreach`, `@auth`)
  - Inyección de datos desde controladores
  - Layouts y secciones

---

### 2. **JavaScript Vanilla (ES6+)**
- **Versión**: JavaScript moderno (ES6+)
- **Uso**: Lógica del lado del cliente, interacciones dinámicas
- **Archivos**: `public/JS/*.js`
- **Características**:
  - **Fetch API**: Para peticiones AJAX asíncronas
  - **Async/Await**: Manejo de operaciones asíncronas
  - **Event Listeners**: Manejo de eventos del DOM
  - **LocalStorage**: Almacenamiento local del navegador
  - **DOM Manipulation**: Manipulación directa del DOM

**Archivos principales**:
- `dashbord.js`: Lógica del dashboard, carga de datos, gráficos
- `modal-importar-controles.js`: Lógica de importación
- `Envio-de-solicitud.js`: Envío de formularios
- `formulario-selec-de-EESS.js`: Selección de establecimientos
- `login-Contraseña.js`: Lógica de login

**Ejemplo de uso**:
```javascript
// Fetch API para peticiones AJAX
fetch(window.dashboardRoutes.stats, {
  method: 'GET',
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': getCsrfToken()
  }
})
.then(response => response.json())
.then(data => {
  // Procesar datos
});
```

---

### 3. **Chart.js**
- **Versión**: 4.4.0
- **Uso**: Generación de gráficos y visualizaciones
- **CDN**: `https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js`
- **Características**:
  - Gráficos de barras
  - Gráficos de pastel
  - Gráficos de líneas
  - Responsive y animados

**Uso en el sistema**:
- Gráfico de distribución por género en el dashboard
- Visualización de estadísticas

---

### 4. **Tailwind CSS (Utility-First CSS)**
- **Versión**: Incluido en Laravel (vía CDN o compilado)
- **Uso**: Framework CSS para diseño rápido y responsive
- **Características**:
  - Clases utilitarias (`flex`, `grid`, `bg-white`, `rounded-xl`)
  - Diseño responsive (`md:`, `lg:`, `sm:`)
  - Sistema de colores (`slate-50`, `purple-600`, `green-500`)
  - Espaciado consistente

**Ejemplos en el código**:
```html
<div class="flex h-screen bg-slate-50 relative">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
```

---

### 5. **CSS Personalizado**
- **Ubicación**: `public/Css/*.css`
- **Uso**: Estilos específicos del sistema
- **Archivos principales**:
  - `variables.css`: Variables CSS personalizadas
  - `dashbord.css`: Estilos del dashboard
  - `sidebar.css`: Estilos del menú lateral
  - `dashboard-main.css`: Estilos principales
  - `modal-*.css`: Estilos de modales específicos
  - `Login.css`: Estilos de login
  - `Formulario.css`: Estilos de formularios

**Características**:
- Variables CSS para colores y espaciado
- Estilos personalizados para componentes
- Animaciones y transiciones
- Diseño responsive

---

### 6. **SVG Icons (Lucide Icons)**
- **Uso**: Iconos vectoriales inline
- **Características**:
  - Iconos SVG embebidos en HTML
  - Estilizables con CSS
  - Escalables sin pérdida de calidad
  - Librería: Lucide Icons (similar a Feather Icons)

**Ejemplo**:
```html
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
  <path d="M12 5v14"></path>
  <path d="M5 12h14"></path>
</svg>
```

---

### 7. **Vite (Build Tool)**
- **Versión**: 5.0.0
- **Uso**: Herramienta de construcción para assets frontend
- **Archivo**: `package.json`
- **Características**:
  - Compilación rápida
  - Hot Module Replacement (HMR)
  - Optimización de assets
  - Soporte para TypeScript, Sass, etc.

**Dependencias**:
- `vite`: ^5.0.0
- `laravel-vite-plugin`: ^1.0.0
- `axios`: ^1.6.4 (para peticiones HTTP)

---

### 8. **Axios (HTTP Client)**
- **Versión**: 1.6.4
- **Uso**: Cliente HTTP para peticiones AJAX (alternativa a Fetch API)
- **Características**:
  - Interceptores de peticiones/respuestas
  - Manejo automático de CSRF
  - Transformación de datos
  - Cancelación de peticiones

**Nota**: Aunque está instalado, el sistema principalmente usa **Fetch API** nativo de JavaScript.

---

## 🏗️ Arquitectura Frontend

### Estructura de Archivos:
```
resources/views/
├── dashboard/
│   ├── index.blade.php          # Dashboard principal
│   ├── controles-cred.blade.php # Página de controles CRED
│   └── alertas-cred.blade.php   # Página de alertas
├── components/
│   └── sidebar-main.blade.php  # Componente de menú lateral
└── ...

public/
├── JS/
│   ├── dashbord.js              # Lógica del dashboard
│   ├── modal-importar-controles.js
│   └── ...
└── Css/
    ├── variables.css
    ├── dashbord.css
    └── ...
```

---

## 🔄 Flujo de Datos Frontend

1. **Usuario interactúa** → Evento JavaScript
2. **JavaScript hace petición** → Fetch API a endpoint Laravel
3. **Laravel procesa** → Controller → Model → Base de datos
4. **Laravel responde** → JSON con datos
5. **JavaScript actualiza DOM** → Renderiza datos en la interfaz

---

## 📊 Características del Frontend

### ✅ Implementado:
- ✅ Diseño responsive (Tailwind CSS)
- ✅ Peticiones AJAX asíncronas (Fetch API)
- ✅ Gráficos interactivos (Chart.js)
- ✅ Modales dinámicos
- ✅ Búsqueda y filtrado en tiempo real
- ✅ Paginación de tablas
- ✅ Validación de formularios
- ✅ Feedback visual (mensajes de éxito/error)
- ✅ Actualización automática de datos (polling cada 30s)

### ❌ No Implementado:
- ❌ Framework JavaScript (React, Vue, Angular)
- ❌ State Management (Redux, Vuex)
- ❌ Routing del lado del cliente (React Router, Vue Router)
- ❌ TypeScript
- ❌ Preprocesadores CSS (Sass, Less)

---

## 📝 Resumen de Tecnologías Frontend

| Tecnología | Versión | Uso Principal |
|------------|---------|---------------|
| **Blade Templates** | Laravel 10 | Renderizado de HTML |
| **JavaScript ES6+** | Nativo | Lógica del cliente |
| **Fetch API** | Nativo | Peticiones AJAX |
| **Chart.js** | 4.4.0 | Gráficos |
| **Tailwind CSS** | Latest | Estilos y diseño |
| **CSS Personalizado** | - | Estilos específicos |
| **SVG Icons** | Lucide | Iconos |
| **Vite** | 5.0.0 | Build tool |
| **Axios** | 1.6.4 | HTTP client (opcional) |

---

**Última actualización**: Diciembre 2024


