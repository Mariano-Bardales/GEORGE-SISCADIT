# 🗑️ Instrucciones para Borrar Todos los Datos de Niños

## ⚠️ ADVERTENCIA

Este proceso borrará **TODOS** los datos de niños y sus registros relacionados:
- ✅ Niños
- ✅ Datos Extra
- ✅ Madres
- ✅ Controles RN
- ✅ Controles CRED
- ✅ Tamizajes
- ✅ Vacunas
- ✅ Recién Nacidos (CNV)
- ✅ Visitas Domiciliarias

**Esta acción NO se puede deshacer.**

---

## 🔧 Método 1: Usar el Script PHP (Recomendado)

### Pasos:

1. **Abre la terminal** en la carpeta del proyecto
2. **Ejecuta el script:**
   ```bash
   php borrar_datos_ninos.php
   ```
3. **Confirma** escribiendo `SI` cuando se te solicite
4. **Espera** a que se complete el borrado

### Ejemplo:

```bash
$ php borrar_datos_ninos.php

⚠️  ADVERTENCIA: Este script borrará TODOS los datos de niños...
¿Estás seguro de que quieres borrar TODOS los datos? (escribe 'SI' para confirmar): SI

🔄 Iniciando borrado de datos...

📊 Registros encontrados:
   - ninos: 5
   - datos_extra: 5
   - madres: 5
   - controles_rn: 0
   - controles_cred: 25
   ...

🗑️  Borrando registros relacionados...
   ✅ Controles CRED borrados: 25
   ✅ Controles RN borrados: 0
   ...
   ✅ Niños borrados: 5

✅ ¡Borrado completado exitosamente!
```

---

## 🔧 Método 2: Usar SQL Directamente

### Opción A: Borrar desde MySQL/MariaDB

1. **Abre tu cliente MySQL** (phpMyAdmin, MySQL Workbench, etc.)
2. **Conecta a la base de datos** `siscadit2`
3. **Ejecuta estos comandos en orden:**

```sql
-- Desactivar verificación de claves foráneas temporalmente
SET FOREIGN_KEY_CHECKS = 0;

-- Borrar registros relacionados primero
DELETE FROM controles_menor1;
DELETE FROM controles_rn;
DELETE FROM tamizaje_neonatal;
DELETE FROM vacunas_rn;
DELETE FROM recien_nacidos;
DELETE FROM visitas_domiciliarias;
DELETE FROM datos_extra;
DELETE FROM madres;

-- Finalmente, borrar niños
DELETE FROM niños;

-- Reactivar verificación de claves foráneas
SET FOREIGN_KEY_CHECKS = 1;
```

### Opción B: Truncar Tablas (Más Rápido)

```sql
-- Desactivar verificación de claves foráneas
SET FOREIGN_KEY_CHECKS = 0;

-- Truncar todas las tablas (más rápido que DELETE)
TRUNCATE TABLE controles_menor1;
TRUNCATE TABLE controles_rn;
TRUNCATE TABLE tamizaje_neonatal;
TRUNCATE TABLE vacunas_rn;
TRUNCATE TABLE recien_nacidos;
TRUNCATE TABLE visitas_domiciliarias;
TRUNCATE TABLE datos_extra;
TRUNCATE TABLE madres;
TRUNCATE TABLE niños;

-- Reactivar verificación de claves foráneas
SET FOREIGN_KEY_CHECKS = 1;
```

---

## 🔧 Método 3: Usar Artisan Tinker

```bash
php artisan tinker
```

Luego ejecuta:

```php
// Borrar todos los datos
DB::table('controles_menor1')->delete();
DB::table('controles_rn')->delete();
DB::table('tamizaje_neonatal')->delete();
DB::table('vacunas_rn')->delete();
DB::table('recien_nacidos')->delete();
DB::table('visitas_domiciliarias')->delete();
DB::table('datos_extra')->delete();
DB::table('madres')->delete();
DB::table('niños')->delete();

echo "Datos borrados exitosamente";
```

---

## 📊 Orden de Borrado

El script borra en este orden para evitar errores de claves foráneas:

1. ✅ Controles CRED
2. ✅ Controles RN
3. ✅ Tamizajes
4. ✅ Vacunas
5. ✅ Recién Nacidos (CNV)
6. ✅ Visitas Domiciliarias
7. ✅ Datos Extra
8. ✅ Madres
9. ✅ Niños (último)

---

## ✅ Verificación Después de Borrar

Después de borrar, verifica que las tablas estén vacías:

```sql
SELECT 
    (SELECT COUNT(*) FROM niños) as ninos,
    (SELECT COUNT(*) FROM datos_extra) as datos_extra,
    (SELECT COUNT(*) FROM madres) as madres,
    (SELECT COUNT(*) FROM controles_rn) as controles_rn,
    (SELECT COUNT(*) FROM controles_menor1) as controles_cred,
    (SELECT COUNT(*) FROM tamizaje_neonatal) as tamizajes,
    (SELECT COUNT(*) FROM vacunas_rn) as vacunas,
    (SELECT COUNT(*) FROM recien_nacidos) as recien_nacidos,
    (SELECT COUNT(*) FROM visitas_domiciliarias) as visitas;
```

Todos los valores deben ser `0`.

---

## 🔒 Seguridad

El script incluye:
- ✅ **Transacciones**: Si algo falla, se revierte todo
- ✅ **Confirmación**: Pide confirmación antes de borrar
- ✅ **Orden correcto**: Borra primero los relacionados
- ✅ **Mensajes claros**: Muestra qué se está borrando

---

## 💡 Recomendación

**Usa el Método 1 (Script PHP)** porque:
- ✅ Es más seguro (usa transacciones)
- ✅ Muestra un resumen de lo borrado
- ✅ Maneja errores automáticamente
- ✅ Confirma antes de borrar

---

**Última actualización:** Diciembre 2024

