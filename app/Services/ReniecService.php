<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReniecService
{
    protected $apiUrl;
    protected $apiToken;

    public function __construct()
    {
        // Leer directamente desde env si config no funciona (fallback)
        $this->apiUrl = config('services.reniec.api_url') ?: env('RENIEC_API_URL', 'https://apiperu.dev/api/dni');
        $this->apiToken = config('services.reniec.api_token') ?: env('RENIEC_API_TOKEN', '');
    }

    /**
     * Consultar DNI en RENIEC
     */
    public function consultarDNI($dni)
    {
        try {
            // Preparar headers
            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];

            // Construir la URL base
            $baseUrl = rtrim($this->apiUrl, '/');
            
            // Construir la petición HTTP
            $httpRequest = Http::withHeaders($headers)->timeout(30);

            // Construir la URL - apiperu.dev formato: https://apiperu.dev/api/dni/{dni}
            $url = $baseUrl . '/' . $dni;
            
            // apiperu.dev requiere el token como header Authorization
            // Formato: Authorization: Bearer {token}
            if (!empty($this->apiToken)) {
                $httpRequest = $httpRequest->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                ]);
            }

            // Ocultar token en logs para seguridad
            $urlLog = !empty($this->apiToken) ? str_replace($this->apiToken, '***TOKEN***', $url) : $url;
            
            Log::info('Consultando RENIEC', [
                'url' => $urlLog,
                'dni' => $dni,
                'has_token' => !empty($this->apiToken),
                'api_url' => $this->apiUrl,
                'token_length' => !empty($this->apiToken) ? strlen($this->apiToken) : 0
            ]);

            // Realizar la petición HTTP
            $response = $httpRequest->get($url);

            Log::info('Respuesta RENIEC', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers()
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Manejar diferentes formatos de respuesta de apiperu.dev
                // Formato 1: {"success": true, "data": {...}}
                if (isset($data['success']) && $data['success'] === true && isset($data['data'])) {
                    return [
                        'success' => true,
                        'data' => $this->formatearDatos($data['data'])
                    ];
                }
                
                // Formato 2: {"data": {...}} (sin campo success)
                if (isset($data['data']) && is_array($data['data'])) {
                    return [
                        'success' => true,
                        'data' => $this->formatearDatos($data['data'])
                    ];
                }
                
                // Formato 3: Datos directos con campos de RENIEC
                if (isset($data['numeroDocumento']) || isset($data['dni']) || isset($data['nombres']) || isset($data['apellidoPaterno'])) {
                    return [
                        'success' => true,
                        'data' => $this->formatearDatos($data)
                    ];
                }
                
                // Formato 4: Error en la respuesta
                if (isset($data['success']) && $data['success'] === false) {
                    return [
                        'success' => false,
                        'message' => $data['message'] ?? 'No se encontraron datos para el DNI proporcionado',
                        'error' => $data['error'] ?? null
                    ];
                }

                // Si no se reconoce el formato, devolver error con los datos raw para debugging
                Log::warning('Formato de respuesta RENIEC no reconocido', ['data' => $data]);
                return [
                    'success' => false,
                    'message' => 'Formato de respuesta no reconocido de la API',
                    'raw_data' => $data
                ];
            } else {
                // Manejar errores HTTP
                $statusCode = $response->status();
                $errorData = $response->json();
                
                $errorMessage = 'Error al consultar RENIEC';
                if (isset($errorData['message'])) {
                    $errorMessage = $errorData['message'];
                } elseif ($statusCode === 404) {
                    $errorMessage = 'No se encontraron datos para el DNI proporcionado';
                } elseif ($statusCode === 401 || $statusCode === 403) {
                    $errorMessage = 'Error de autenticación. Verifique su token de API';
                } elseif ($statusCode === 429) {
                    $errorMessage = 'Demasiadas solicitudes. Por favor, intente más tarde';
                } elseif ($statusCode >= 500) {
                    $errorMessage = 'Error del servidor de la API. Por favor, intente más tarde';
                }

                Log::warning('Error en respuesta RENIEC', [
                    'status' => $statusCode,
                    'error' => $errorData,
                    'body' => $response->body()
                ]);

                return [
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => $errorData['error'] ?? null,
                    'status' => $statusCode
                ];
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Error de conexión RENIEC', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error de conexión con el servicio RENIEC. Verifique su conexión a internet.'
            ];
        } catch (\Exception $e) {
            Log::error('Error en consulta RENIEC', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Error al conectar con el servicio RENIEC: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Formatear los datos de RENIEC a un formato estándar
     */
    protected function formatearDatos($data)
    {
        if (!is_array($data)) {
            Log::warning('Datos de RENIEC no son un array', ['data' => $data]);
            return [];
        }

        // Normalizar los nombres de campos según diferentes APIs (apiperu.dev, etc.)
        // apiperu.dev puede devolver: nombres, apellidoPaterno, apellidoMaterno
        // O también: nombre, paterno, materno
        
        $nombres = $data['nombres'] ?? $data['nombre'] ?? $data['nombres_completos'] ?? '';
        $apellidoPaterno = $data['apellidoPaterno'] ?? $data['apellido_paterno'] ?? $data['paterno'] ?? $data['apellidoP'] ?? '';
        $apellidoMaterno = $data['apellidoMaterno'] ?? $data['apellido_materno'] ?? $data['materno'] ?? $data['apellidoM'] ?? '';
        
        // Limpiar espacios extra
        $nombres = trim($nombres);
        $apellidoPaterno = trim($apellidoPaterno);
        $apellidoMaterno = trim($apellidoMaterno);
        
        // Construir nombre completo si no existe
        $nombreCompleto = $data['nombreCompleto'] ?? $data['nombre_completo'] ?? $data['nombreCompleto'] ?? '';
        if (empty($nombreCompleto)) {
            $nombreCompleto = trim($nombres . ' ' . $apellidoPaterno . ' ' . $apellidoMaterno);
        }
        
        // Obtener número de documento
        $numeroDocumento = $data['numeroDocumento'] ?? $data['dni'] ?? $data['numero_documento'] ?? $data['documento'] ?? '';
        
        // Devolver tanto camelCase como snake_case para compatibilidad
        $formatted = [
            // camelCase
            'numeroDocumento' => $numeroDocumento,
            'nombres' => $nombres,
            'apellidoPaterno' => $apellidoPaterno,
            'apellidoMaterno' => $apellidoMaterno,
            'nombreCompleto' => $nombreCompleto,
            'codigoVerificacion' => $data['codigoVerificacion'] ?? $data['codigo_verificacion'] ?? $data['codVerificacion'] ?? '',
            // snake_case (para compatibilidad con JavaScript)
            'numero_documento' => $numeroDocumento,
            'nombres_completos' => $nombreCompleto,
            'apellido_paterno' => $apellidoPaterno,
            'apellido_materno' => $apellidoMaterno,
        ];

        Log::info('Datos formateados de RENIEC', [
            'nombres' => $nombres,
            'apellido_paterno' => $apellidoPaterno,
            'apellido_materno' => $apellidoMaterno,
            'nombre_completo' => $nombreCompleto
        ]);

        return $formatted;
    }

    /**
     * Generar username basado en los datos de RENIEC
     */
    public function generarUsername($data)
    {
        $nombres = strtolower($data['nombres'] ?? '');
        $apellidoPaterno = strtolower($data['apellidoPaterno'] ?? '');
        $apellidoMaterno = strtolower($data['apellidoMaterno'] ?? '');
        
        // Tomar primera letra del primer nombre y apellidos
        $inicialNombre = substr($nombres, 0, 1);
        $apellidoPaterno = preg_replace('/[^a-z]/', '', $apellidoPaterno);
        $apellidoMaterno = preg_replace('/[^a-z]/', '', $apellidoMaterno);
        
        // Generar username: primera letra del nombre + apellido paterno + primera letra del apellido materno
        $username = $inicialNombre . $apellidoPaterno . substr($apellidoMaterno, 0, 1);
        
        return strtolower($username);
    }
}
