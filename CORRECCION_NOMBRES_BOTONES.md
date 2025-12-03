# 🔧 CORRECCIÓN DE NOMBRES DE BOTONES EN TABLAS

## Problema Identificado

En las tablas de controles, los botones dicen "Registrar" pero algunos no registran directamente, sino que abren modales. Esto puede confundir a los usuarios.

---

## Análisis de Botones "Registrar"

### **1. Tabla de Controles Recién Nacido (RN)**
- **Ubicación**: Línea ~5716
- **Texto actual**: "Registrar"
- **Función**: `abrirModalRegistro(numeroControl, rangoMin, rangoMax)`
- **Acción real**: Abre modal para registrar control
- **Estado**: ✅ Funciona correctamente (abre modal)

### **2. Tabla de Controles CRED Mensual**
- **Ubicación**: Línea ~7050
- **Texto actual**: "Editar" (cuando hay control) / "Registrar" (cuando no hay)
- **Función**: `abrirModalCredMensual(mes, controlId)`
- **Acción real**: Abre modal para registrar/editar control
- **Estado**: ⚠️ El texto puede ser confuso

---

## Correcciones Necesarias

### **Cambio 1: Botones en Tabla RN**
- **Antes**: "Registrar"
- **Después**: "Registrar Control" o "Abrir Formulario"
- **Razón**: Más descriptivo sobre la acción

### **Cambio 2: Botones en Tabla CRED**
- **Antes**: "Registrar" / "Editar"
- **Después**: "Registrar Control" / "Editar Control"
- **Razón**: Más claro y consistente

### **Cambio 3: Verificar que todos los botones funcionen**
- Verificar que `abrirModalRegistro()` existe y funciona
- Verificar que `abrirModalCredMensual()` existe y funciona
- Verificar que los modales se abren correctamente

---

## Archivos a Modificar

1. `resources/views/dashboard/controles-cred.blade.php`
   - Línea ~5716: Cambiar texto del botón RN
   - Línea ~7050: Cambiar texto del botón CRED
   - Verificar funciones de apertura de modales

---

## Verificación de Funciones

### **Función `abrirModalRegistro()`**
- ✅ Existe en línea 5503
- ✅ Abre modal de registro de control RN
- ✅ Funciona correctamente

### **Función `abrirModalCredMensual()`**
- ⚠️ Necesita verificación
- Debe abrir modal de registro de control CRED

---

**Fecha**: Diciembre 2024

