# 🚀 Guía: Ejecutar el Proyecto SISCADIT con `php artisan serve`

## ⚠️ **PROBLEMA ACTUAL**

Tu sistema tiene **PHP 8.0.30** pero el proyecto requiere **PHP 8.1 o superior**.

**Versión actual:** PHP 8.0.30 ❌  
**Versión requerida:** PHP 8.1+ ✅

---

## 🔧 **SOLUCIÓN: Actualizar PHP**

### **Opción 1: Actualizar XAMPP (Recomendado)**

1. **Descargar nueva versión de XAMPP**
   - Ve a: https://www.apachefriends.org/
   - Descarga XAMPP con PHP 8.1 o superior
   - Instálalo en una nueva carpeta (ej: `C:\xampp82`)

2. **Actualizar rutas**
   - Usa la nueva instalación de XAMPP
   - O actualiza las rutas en tu sistema

### **Opción 2: Actualizar solo PHP en XAMPP**

1. **Descargar PHP 8.1+**
   - Ve a: https://windows.php.net/download/
   - Descarga PHP 8.1 o 8.2 (Thread Safe, VS16 x64)
   - Extrae en una carpeta temporal

2. **Reemplazar PHP en XAMPP**
   ```bash
   # Hacer backup de la carpeta actual
   mv C:\xampp1\php C:\xampp1\php_backup
   
   # Copiar nueva versión de PHP
   # (Copia los archivos descargados a C:\xampp1\php)
   ```

---

## ✅ **PASOS PARA EJECUTAR EL PROYECTO**

Una vez que tengas PHP 8.1+, sigue estos pasos:

### **1. Verificar PHP**
```bash
php -v
# Debe mostrar PHP 8.1 o superior
```

### **2. Verificar Composer**
```bash
composer --version
# O si usas composer.phar:
php composer.phar --version
```

### **3. Instalar/Actualizar dependencias**
```bash
# Si las dependencias no están instaladas:
composer install

# O si usas composer.phar:
php composer.phar install
```

### **4. Verificar archivo .env**
Asegúrate de que el archivo `.env` tenga la configuración correcta:

```env
APP_NAME="GEORGE-SISCADIT"
APP_ENV=local
APP_KEY=base64:... (debe estar generado)
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=george_siscadit
DB_USERNAME=root
DB_PASSWORD=
```

### **5. Generar Key de Laravel (si no existe)**
```bash
php artisan key:generate
```

### **6. Verificar Base de Datos**
- Asegúrate de que MySQL esté corriendo en XAMPP
- Verifica que la base de datos `george_siscadit` exista
- Si no existe, créala en phpMyAdmin

### **7. Ejecutar Migraciones (si es necesario)**
```bash
php artisan migrate
```

### **8. Compilar Assets Frontend (si es necesario)**
```bash
npm install
npm run dev
# O para producción:
npm run build
```

### **9. Ejecutar el Servidor**
```bash
php artisan serve
```

Esto iniciará el servidor en: **http://localhost:8000**

---

## 🎯 **COMANDO COMPLETO PARA EJECUTAR**

Si todo está configurado correctamente:

```bash
# 1. Navegar al proyecto
cd C:\xampp1\htdocs\GEORGE-SISCADIT

# 2. Ejecutar servidor
php artisan serve
```

O si necesitas especificar el puerto:

```bash
php artisan serve --port=8000
```

O si necesitas especificar el host:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

---

## 🔍 **VERIFICAR QUE TODO FUNCIONA**

Una vez que el servidor esté corriendo:

1. Abre tu navegador
2. Ve a: `http://localhost:8000`
3. Deberías ver la página de inicio del sistema

---

## ❌ **ERRORES COMUNES Y SOLUCIONES**

### **Error: "PHP version does not satisfy requirement"**
**Solución:** Actualiza PHP a 8.1 o superior (ver arriba)

### **Error: "No application encryption key"**
**Solución:**
```bash
php artisan key:generate
```

### **Error: "SQLSTATE[HY000] [1045] Access denied"**
**Solución:** Verifica las credenciales en `.env`:
- `DB_USERNAME=root`
- `DB_PASSWORD=` (vacío si no tiene contraseña)

### **Error: "SQLSTATE[HY000] [2002] No connection"**
**Solución:** 
- Inicia MySQL en XAMPP Control Panel
- Verifica que MySQL esté corriendo

### **Error: "Vite manifest not found"**
**Solución:**
```bash
npm install
npm run dev
```

---

## 📝 **NOTAS IMPORTANTES**

1. **PHP 8.1+ es obligatorio** - El proyecto no funcionará con PHP 8.0
2. **MySQL debe estar corriendo** - Inicia MySQL en XAMPP antes de ejecutar
3. **Base de datos debe existir** - Crea `george_siscadit` si no existe
4. **Puerto 8000** - Asegúrate de que no esté en uso por otra aplicación

---

## 🎉 **¡LISTO!**

Una vez que tengas PHP 8.1+ y sigas estos pasos, podrás ejecutar:

```bash
php artisan serve
```

Y acceder al sistema en: **http://localhost:8000**

---

**Última actualización:** Diciembre 2024

