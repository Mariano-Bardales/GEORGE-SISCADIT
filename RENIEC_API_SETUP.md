# Configuración de API RENIEC (apiperu.dev)

## Pasos para configurar la API de RENIEC

### 1. Obtener tu token de API

1. Visita [https://apiperu.dev](https://apiperu.dev)
2. Regístrate o inicia sesión
3. Obtén tu token de API desde el panel de control

### 2. Configurar en el archivo .env

Abre el archivo `.env` en la raíz del proyecto y agrega o actualiza estas líneas:

```env
# API RENIEC - API Peru (apiperu.dev)
RENIEC_API_URL=https://apiperu.dev/api/dni
RENIEC_API_TOKEN=tu_token_aqui
```

**Importante:** 
- Reemplaza `tu_token_aqui` con tu token real de apiperu.dev
- No dejes espacios alrededor del signo `=`
- No uses comillas alrededor del token

### 3. Limpiar caché de configuración

Después de actualizar el `.env`, ejecuta estos comandos:

```bash
php artisan config:clear
php artisan cache:clear
```

### 4. Verificar la configuración

Puedes verificar que la configuración esté correcta ejecutando:

```bash
php artisan tinker
```

Y luego dentro de tinker:

```php
config('services.reniec.api_url')
config('services.reniec.api_token')
```

### 5. Probar la API

1. Abre el modal de crear usuario
2. Ingresa un DNI válido (8 dígitos)
3. Haz clic en "Buscar"
4. Deberías ver los datos de RENIEC

## Formato de la API

La API de apiperu.dev espera:
- **URL:** `https://apiperu.dev/api/dni/{dni}?token={token}`
- **Método:** GET
- **Headers:** 
  - `Accept: application/json`
  - `Authorization: Bearer {token}` (opcional, también funciona como query parameter)

## Solución de problemas

### Error: "Token NO configurado"
- Verifica que el archivo `.env` tenga la línea `RENIEC_API_TOKEN=tu_token`
- Ejecuta `php artisan config:clear`
- Reinicia el servidor si es necesario

### Error: "Error de autenticación"
- Verifica que el token sea correcto
- Asegúrate de que no haya espacios extra en el token
- Verifica que tu cuenta en apiperu.dev esté activa

### Error: "No se encontraron datos"
- Verifica que el DNI sea válido (8 dígitos)
- Algunos DNIs pueden no estar disponibles en la base de datos de RENIEC

### Ver logs

Los logs de las consultas RENIEC se guardan en:
```
storage/logs/laravel.log
```

Busca las líneas que contengan "Consultando RENIEC" o "Respuesta RENIEC" para ver detalles.

## Nota importante

Los datos de ejemplo se generan automáticamente si no hay datos reales en la base de datos. Los datos reales de RENIEC solo se obtienen cuando:
1. El token está configurado correctamente
2. La API responde exitosamente
3. El DNI existe en la base de datos de RENIEC




