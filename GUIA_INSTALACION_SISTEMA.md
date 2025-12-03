# 📦 Guía Completa: Instalación del Sistema en Otra PC

Esta guía te ayudará a instalar y configurar el sistema **GEORGE-SISCADIT** en una nueva computadora desde cero.

---

## 📋 ÍNDICE

1. [Requisitos Previos](#1-requisitos-previos)
2. [Paso 1: Instalar Software Necesario](#2-paso-1-instalar-software-necesario)
3. [Paso 2: Clonar el Repositorio](#3-paso-2-clonar-el-repositorio)
4. [Paso 3: Instalar Dependencias PHP](#4-paso-3-instalar-dependencias-php)
5. [Paso 4: Instalar Dependencias Node.js](#5-paso-4-instalar-dependencias-nodejs)
6. [Paso 5: Configurar Base de Datos](#6-paso-5-configurar-base-de-datos)
7. [Paso 6: Configurar Archivo .env](#7-paso-6-configurar-archivo-env)
8. [Paso 7: Ejecutar Migraciones](#8-paso-7-ejecutar-migraciones)
9. [Paso 8: Configurar Permisos](#9-paso-8-configurar-permisos)
10. [Paso 9: Generar Key de Laravel](#10-paso-9-generar-key-de-laravel)
11. [Paso 10: Compilar Assets Frontend](#11-paso-10-compilar-assets-frontend)
12. [Paso 11: Verificar Instalación](#12-paso-11-verificar-instalación)

---

## 1. REQUISITOS PREVIOS

### Software Necesario:

- ✅ **XAMPP** (versión 8.1 o superior) - Incluye PHP, MySQL, Apache
- ✅ **Composer** (gestor de dependencias PHP)
- ✅ **Node.js** (versión 16 o superior) - Incluye NPM
- ✅ **Git** (para clonar el repositorio)

### Versiones Requeridas:

- **PHP**: 8.1 o superior
- **MySQL**: 5.7 o superior (o MariaDB 10.3+)
- **Node.js**: 16.x o superior
- **Composer**: Última versión estable

---

## 2. PASO 1: INSTALAR SOFTWARE NECESARIO

### 2.1. Instalar XAMPP

1. Descargar XAMPP desde: https://www.apachefriends.org/
2. Instalar en la ruta recomendada: `C:\xampp`
3. Durante la instalación, asegúrate de instalar:
   - ✅ Apache
   - ✅ MySQL
   - ✅ PHP
   - ✅ phpMyAdmin (opcional pero recomendado)

### 2.2. Instalar Composer

1. Descargar desde: https://getcomposer.org/download/
2. Ejecutar el instalador
3. Verificar instalación:
   ```bash
   composer --version
   ```

### 2.3. Instalar Node.js

1. Descargar desde: https://nodejs.org/
2. Instalar la versión LTS (Long Term Support)
3. Verificar instalación:
   ```bash
   node --version
   npm --version
   ```

### 2.4. Instalar Git

1. Descargar desde: https://git-scm.com/download/win
2. Durante la instalación, seleccionar "Git Bash" como terminal
3. Verificar instalación:
   ```bash
   git --version
   ```

---

## 3. PASO 2: CLONAR EL REPOSITORIO

### 3.1. Abrir Git Bash

Abre **Git Bash** en cualquier ubicación.

### 3.2. Navegar a la carpeta de XAMPP

```bash
cd /c/xampp/htdocs
```

### 3.3. Clonar el repositorio

```bash
git clone https://github.com/Mariano-Bardales/GEORGE-SISCADIT.git
```

### 3.4. Entrar a la carpeta del proyecto

```bash
cd GEORGE-SISCADIT
```

---

## 4. PASO 3: INSTALAR DEPENDENCIAS PHP

### 4.1. Instalar dependencias con Composer

```bash
composer install
```

**⏱️ Tiempo estimado:** 2-5 minutos (depende de la velocidad de internet)

### 4.2. Verificar instalación

Si todo salió bien, deberías ver:
```
Package manifest generated successfully.
```

---

## 5. PASO 4: INSTALAR DEPENDENCIAS NODE.JS

### 5.1. Instalar dependencias con NPM

```bash
npm install
```

**⏱️ Tiempo estimado:** 1-3 minutos

### 5.2. Verificar instalación

Si todo salió bien, deberías ver:
```
added X packages
```

---

## 6. PASO 5: CONFIGURAR BASE DE DATOS

### 6.1. Iniciar XAMPP

1. Abrir **XAMPP Control Panel**
2. Iniciar **Apache**
3. Iniciar **MySQL**

### 6.2. Crear la base de datos

#### Opción A: Usando phpMyAdmin (Recomendado)

1. Abrir navegador: `http://localhost/phpmyadmin`
2. Click en **"Nueva"** o **"New"** (izquierda)
3. Nombre de la base de datos: `george_siscadit`
4. Cotejamiento: `utf8mb4_unicode_ci`
5. Click en **"Crear"**

#### Opción B: Usando línea de comandos

```bash
# Conectar a MySQL
mysql -u root -p

# Crear base de datos
CREATE DATABASE george_siscadit CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Salir
exit;
```

---

## 7. PASO 6: CONFIGURAR ARCHIVO .env

### 7.1. Crear archivo .env

```bash
# En Git Bash, dentro de la carpeta del proyecto
cp .env.example .env
```

**⚠️ NOTA:** Si no existe `.env.example`, crea el archivo `.env` manualmente.

### 7.2. Editar archivo .env

Abre el archivo `.env` con un editor de texto y configura lo siguiente:

```env
APP_NAME="GEORGE-SISCADIT"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=george_siscadit
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 7.3. Configuraciones Importantes:

- **DB_DATABASE**: Nombre de la base de datos que creaste (`george_siscadit`)
- **DB_USERNAME**: Usuario de MySQL (generalmente `root`)
- **DB_PASSWORD**: Contraseña de MySQL (dejar vacío si no tiene contraseña)
- **APP_URL**: URL del proyecto (generalmente `http://localhost`)

---

## 8. PASO 7: EJECUTAR MIGRACIONES

### 8.1. Generar Key de Laravel

```bash
php artisan key:generate
```

### 8.2. Ejecutar migraciones

```bash
php artisan migrate
```

**⏱️ Tiempo estimado:** 30 segundos - 1 minuto

### 8.3. Verificar migraciones

Si todo salió bien, deberías ver:
```
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table (XX.XXms)
...
```

---

## 9. PASO 8: CONFIGURAR PERMISOS

### 9.1. Crear carpetas necesarias

```bash
# Crear carpeta de storage si no existe
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
```

### 9.2. Configurar permisos (Windows)

En Windows, generalmente no es necesario configurar permisos especiales, pero asegúrate de que las carpetas `storage` y `bootstrap/cache` sean escribibles.

**Si usas Linux/Mac:**

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## 10. PASO 9: GENERAR KEY DE LARAVEL

### 10.1. Generar Application Key

```bash
php artisan key:generate
```

**⚠️ IMPORTANTE:** Este comando genera una clave única para tu aplicación. Si ya la ejecutaste en el paso 7, puedes saltar este paso.

---

## 11. PASO 10: COMPILAR ASSETS FRONTEND

### 11.1. Compilar assets para desarrollo

```bash
npm run dev
```

**⏱️ Tiempo estimado:** 1-2 minutos

**⚠️ NOTA:** Este comando debe estar ejecutándose mientras desarrollas. Déjalo corriendo en una terminal.

### 11.2. O compilar para producción

```bash
npm run build
```

**⏱️ Tiempo estimado:** 1-2 minutos

---

## 12. PASO 11: VERIFICAR INSTALACIÓN

### 12.1. Iniciar servidor de desarrollo

```bash
php artisan serve
```

### 12.2. Abrir en el navegador

Abre tu navegador y ve a:
```
http://localhost:8000
```

O si usas XAMPP con Apache:
```
http://localhost/GEORGE-SISCADIT/public
```

### 12.3. Verificar que funciona

Deberías ver la página de inicio del sistema. Si ves errores, revisa la sección de **Solución de Problemas**.

---

## 📝 RESUMEN RÁPIDO (Comandos en Orden)

```bash
# 1. Clonar repositorio
cd /c/xampp/htdocs
git clone https://github.com/Mariano-Bardales/GEORGE-SISCADIT.git
cd GEORGE-SISCADIT

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar .env
cp .env.example .env
# Editar .env con tus datos de base de datos

# 4. Generar key
php artisan key:generate

# 5. Ejecutar migraciones
php artisan migrate

# 6. Compilar assets
npm run dev

# 7. Iniciar servidor
php artisan serve
```

---

## ✅ CHECKLIST DE INSTALACIÓN

- [ ] XAMPP instalado y corriendo (Apache + MySQL)
- [ ] Composer instalado
- [ ] Node.js instalado
- [ ] Git instalado
- [ ] Repositorio clonado
- [ ] Dependencias PHP instaladas (`composer install`)
- [ ] Dependencias Node.js instaladas (`npm install`)
- [ ] Base de datos creada
- [ ] Archivo `.env` configurado
- [ ] Key de Laravel generada
- [ ] Migraciones ejecutadas
- [ ] Assets compilados
- [ ] Sistema funcionando en el navegador

---

## 🔧 SOLUCIÓN DE PROBLEMAS

### ❌ Error: "Class 'PDO' not found"

**Solución:**
1. Abrir `php.ini` en XAMPP
2. Buscar `;extension=pdo_mysql`
3. Quitar el `;` al inicio: `extension=pdo_mysql`
4. Reiniciar Apache

### ❌ Error: "SQLSTATE[HY000] [1045] Access denied"

**Solución:**
1. Verificar credenciales en `.env`
2. Verificar que MySQL esté corriendo en XAMPP
3. Verificar usuario y contraseña de MySQL

### ❌ Error: "No application encryption key has been specified"

**Solución:**
```bash
php artisan key:generate
```

### ❌ Error: "The stream or file could not be opened"

**Solución:**
1. Verificar que existan las carpetas `storage/logs` y `storage/framework`
2. Crear las carpetas si no existen:
   ```bash
   mkdir -p storage/logs
   mkdir -p storage/framework/cache
   mkdir -p storage/framework/sessions
   mkdir -p storage/framework/views
   ```

### ❌ Error: "npm: command not found"

**Solución:**
1. Verificar que Node.js esté instalado: `node --version`
2. Si no está instalado, instalar desde: https://nodejs.org/
3. Reiniciar Git Bash después de instalar

### ❌ Error: "composer: command not found"

**Solución:**
1. Verificar que Composer esté instalado: `composer --version`
2. Si no está instalado, instalar desde: https://getcomposer.org/
3. Reiniciar Git Bash después de instalar

### ❌ Error: "Port 8000 is already in use"

**Solución:**
```bash
# Usar otro puerto
php artisan serve --port=8001
```

O detener el proceso que está usando el puerto 8000.

### ❌ Error: "Migration table not found"

**Solución:**
```bash
# Ejecutar migraciones nuevamente
php artisan migrate
```

### ❌ Error: "Vite manifest not found"

**Solución:**
```bash
# Compilar assets
npm run dev
# O para producción
npm run build
```

---

## 🎉 ¡INSTALACIÓN COMPLETA!

Una vez completados todos los pasos, tu sistema debería estar funcionando correctamente.

**URLs importantes:**
- Sistema: `http://localhost:8000` o `http://localhost/GEORGE-SISCADIT/public`
- phpMyAdmin: `http://localhost/phpmyadmin`

---

**Última actualización:** Diciembre 2024
**Versión del sistema:** Laravel 10.x

