# 📋 Instrucciones para Importar Controles

Este documento explica las dos formas de importar controles:
1. **Desde Excel** (recomendado) - Sube un archivo Excel desde la interfaz web
2. **Desde Seeder** - Usa el seeder `ControlesSeeder` para poblar con datos de prueba

---

## 📊 Opción 1: Importar desde Excel (Recomendado)

### 🚀 Cómo usar

1. **Accede a la página de importación:**
   - Ve a "Importar Controles" en el menú (solo para administradores)
   - O visita: `/importar-controles`

2. **Descarga el template:**
   - Haz clic en "Descargar Template" para obtener un archivo Excel de ejemplo
   - El template incluye ejemplos de todos los tipos de controles

3. **Prepara tu archivo Excel:**
   - Abre el template descargado
   - Completa los datos según el formato indicado
   - Guarda el archivo como .xlsx

4. **Sube el archivo:**
   - Selecciona tu archivo Excel
   - Haz clic en "Importar Controles"
   - Espera a que se procese la importación

### 📝 Formato del Excel

**Columnas principales:**
- `ID_NINO` - ID del niño en la base de datos (obligatorio)
- `TIPO_CONTROL` - Tipo: CRED, CRN, VACUNA, TAMIZAJE, VISITA, DATOS_EXTRA, RECIEN_NACIDO
- `NUMERO_CONTROL` - Número de control (1-4 para CRN, 1-11 para CRED)
- `FECHA` - Fecha del control (formato: YYYY-MM-DD)
- `ESTADO` - Estado del control

**Tipos de control y sus campos específicos:**

| Tipo | Campos Requeridos |
|------|-------------------|
| **CRED** | NUMERO_CONTROL (1-11), FECHA, ESTADO, ESTADO_CRED_ONCE, ESTADO_CRED_FINAL |
| **CRN** | NUMERO_CONTROL (1-4), FECHA, ESTADO |
| **VACUNA** | FECHA_BCG, ESTADO_BCG, FECHA_HVB, ESTADO_HVB |
| **TAMIZAJE** | FECHA_TAMIZAJE |
| **VISITA** | FECHA_VISITA, GRUPO_VISITA |
| **DATOS_EXTRA** | RED, MICRORED, DISTRITO, PROVINCIA, DEPARTAMENTO, SEGURO, PROGRAMA |
| **RECIEN_NACIDO** | PESO, EDAD_GESTACIONAL, CLASIFICACION |

### ✅ Ventajas de importar desde Excel

- ✅ Interfaz visual y fácil de usar
- ✅ Puedes ver los resultados inmediatamente
- ✅ Manejo de errores detallado
- ✅ Estadísticas de importación
- ✅ Relación automática con el ID del niño

---

## 📊 Opción 2: Importar desde Seeder

Este método es útil para poblar la base de datos con datos de prueba.

## 📊 Tablas que se poblarán

El seeder creará datos en las siguientes tablas relacionadas con los niños:

1. **`recien_nacido`** - Datos del recién nacido (peso, edad gestacional, clasificación)
2. **`controles_rn`** - Controles de recién nacido (CRN 1-4) para niños de 0-28 días
3. **`controles_menor1`** - Controles CRED mensual (1-11) para niños de 29-359 días
4. **`tamizaje_neonatal`** - Tamizaje neonatal para niños de 1-29 días
5. **`vacuna_rn`** - Vacunas del recién nacido (BCG, HVB) para niños de 0-30 días
6. **`visitas_domiciliarias`** - Visitas domiciliarias para niños menores de 1 año
7. **`datos_extra`** - Datos adicionales (red, microred, distrito, seguro, etc.)

## ⚠️ Requisitos Previos

**IMPORTANTE:** Antes de ejecutar el seeder, asegúrate de que:

1. ✅ Existan niños en la tabla `niños`
2. ✅ Los niños tengan una `fecha_nacimiento` válida
3. ✅ La base de datos esté configurada correctamente

## 🚀 Cómo Ejecutar el Seeder

### Opción 1: Ejecutar solo el seeder de controles

```bash
php artisan db:seed --class=ControlesSeeder
```

### Opción 2: Ejecutar todos los seeders (incluyendo controles)

1. Primero, descomenta la línea en `database/seeders/DatabaseSeeder.php`:
   ```php
   $this->call([
       RolSeeder::class,
       UserSeeder::class,
       ControlesSeeder::class, // Descomentar esta línea
   ]);
   ```

2. Luego ejecuta:
   ```bash
   php artisan db:seed
   ```

## 📝 Qué hace el Seeder

El seeder:

- ✅ **Lee todos los niños** de la tabla `niños`
- ✅ **Calcula la edad** de cada niño en días
- ✅ **Crea controles según la edad**:
  - Recién nacido (0-28 días): CRN 1-4, tamizaje, vacunas
  - Menor de 1 año (29-359 días): CRED mensual 1-11, visitas
- ✅ **Evita duplicados**: No crea registros si ya existen
- ✅ **Genera datos realistas**: Fechas, edades y estados coherentes

## 🔄 Limpiar Datos Existentes (Opcional)

Si quieres borrar todos los controles existentes antes de importar, descomenta estas líneas en `ControlesSeeder.php`:

```php
DB::table('controles_menor1')->truncate();
DB::table('controles_rn')->truncate();
DB::table('tamizaje_neonatal')->truncate();
DB::table('vacuna_rn')->truncate();
DB::table('visitas_domiciliarias')->truncate();
DB::table('datos_extra')->truncate();
DB::table('recien_nacido')->truncate();
```

**⚠️ ADVERTENCIA:** Esto borrará TODOS los datos de controles existentes.

## 📊 Verificar los Datos

Después de ejecutar el seeder, puedes verificar los datos:

1. **En la aplicación web:**
   - Ve a la página de "Controles CRED"
   - Deberías ver los niños con sus controles
   - Haz clic en "Ver Controles" para ver los detalles

2. **En la base de datos:**
   ```sql
   -- Ver controles CRED mensual
   SELECT * FROM controles_menor1 LIMIT 10;
   
   -- Ver controles recién nacido
   SELECT * FROM controles_rn LIMIT 10;
   
   -- Ver tamizajes
   SELECT * FROM tamizaje_neonatal LIMIT 10;
   
   -- Ver vacunas
   SELECT * FROM vacuna_rn LIMIT 10;
   ```

## 🐛 Solución de Problemas

### Error: "No hay niños en la base de datos"
- **Solución:** Crea algunos niños primero usando el formulario de la aplicación o directamente en la base de datos.

### Error: "Table doesn't exist"
- **Solución:** Ejecuta las migraciones primero:
  ```bash
  php artisan migrate
  ```

### Los datos no aparecen en la aplicación
- **Solución:** 
  1. Verifica que los nombres de las tablas coincidan con los modelos
  2. Revisa la consola del navegador (F12) para ver errores
  3. Verifica que las rutas API estén funcionando

## 📞 Soporte

Si tienes problemas, revisa:
- Los logs de Laravel: `storage/logs/laravel.log`
- La consola del navegador (F12)
- Los mensajes del seeder al ejecutarlo

