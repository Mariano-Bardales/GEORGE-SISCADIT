<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Solicitud;
use App\Services\ReniecService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    /**
     * Mostrar todos los usuarios
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtro por rol
        if ($request->has('rol') && $request->rol !== '') {
            $query->where('role', $request->rol);
        }

        // Búsqueda por nombre o email
        if ($request->has('buscar') && $request->buscar !== '') {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        // Ordenar por más recientes primero
        $query->orderBy('created_at', 'desc');

        // Cargar relación con solicitud
        $query->with('solicitud');

        // Paginación
        $perPage = $request->get('per_page', 15);
        $usuarios = $query->paginate($perPage);

        // Estadísticas de usuarios
        $estadisticas = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'jefe_red' => User::where('role', 'jefe_red')->count(),
            'coordinador_microred' => User::where('role', 'coordinador_microred')->count(),
            'usuario' => User::where('role', 'usuario')->count(),
        ];

        // Cargar solicitudes para la pestaña
        $querySolicitudes = Solicitud::query();
        
        // Filtro por estado
        if ($request->has('estado') && $request->estado !== '') {
            $querySolicitudes->where('estado', $request->estado);
        }

        // Búsqueda por documento, correo o motivo
        if ($request->has('buscar_solicitud') && $request->buscar_solicitud !== '') {
            $buscar = $request->buscar_solicitud;
            $querySolicitudes->where(function($q) use ($buscar) {
                $q->where('numero_documento', 'like', "%{$buscar}%")
                  ->orWhere('correo', 'like', "%{$buscar}%")
                  ->orWhere('motivo', 'like', "%{$buscar}%");
            });
        }

        $querySolicitudes->with('usuario')->orderBy('created_at', 'desc');
        $solicitudes = $querySolicitudes->paginate(15, ['*'], 'solicitudes_page');

        // Estadísticas de solicitudes
        $estadisticasSolicitudes = [
            'total' => Solicitud::count(),
            'pendientes' => Solicitud::where('estado', 'pendiente')->count(),
            'aprobadas' => Solicitud::where('estado', 'aprobada')->count(),
            'rechazadas' => Solicitud::where('estado', 'rechazada')->count(),
        ];

        // Si es una petición AJAX o API, devolver JSON
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $usuarios->items(),
                'pagination' => [
                    'current_page' => $usuarios->currentPage(),
                    'last_page' => $usuarios->lastPage(),
                    'per_page' => $usuarios->perPage(),
                    'total' => $usuarios->total(),
                ],
                'estadisticas' => $estadisticas
            ]);
        }

        return view('dashboard.usuarios', compact('usuarios', 'estadisticas', 'solicitudes', 'estadisticasSolicitudes'));
    }

    /**
     * API: Obtener todos los usuarios (para AJAX)
     */
    public function apiIndex(Request $request)
    {
        $query = User::query();

        // Filtro por rol
        if ($request->has('rol') && $request->rol !== '') {
            $query->where('role', $request->rol);
        }

        // Búsqueda por nombre o email
        if ($request->has('buscar') && $request->buscar !== '') {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        // Ordenar por más recientes primero
        $query->orderBy('created_at', 'desc');

        // Cargar relación con solicitud
        $query->with('solicitud');

        // Paginación
        $perPage = $request->get('per_page', 10);
        $usuarios = $query->paginate($perPage);

        // Estadísticas de usuarios (incluyendo todos los roles)
        $estadisticas = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'jefe_red' => User::where('role', 'jefe_red')->count(),
            'jefe_microred' => User::where('role', 'jefe_microred')->count(),
            'coordinador_microred' => User::where('role', 'coordinador_microred')->count(),
            'coordinador_red' => User::where('role', 'coordinador_red')->count(),
            'usuario' => User::where('role', 'usuario')->count(),
            // Totales combinados para el footer
            'total_jefes_red' => User::whereIn('role', ['jefe_red', 'jefe_microred'])->count(),
            'total_coordinadores' => User::whereIn('role', ['coordinador_microred', 'coordinador_red'])->count(),
        ];

        // Convertir a array para asegurar serialización correcta
        $usuariosArray = $usuarios->map(function($usuario) {
                // Obtener la solicitud relacionada con el usuario
                $solicitud = $usuario->solicitud;
                
                // Si no hay solicitud directa, buscar por user_id en solicitudes
                if (!$solicitud) {
                    $solicitud = \App\Models\Solicitud::where('user_id', $usuario->id)->first();
                }
                
                return [
                    'id' => $usuario->id,
                    'name' => $usuario->name,
                    'email' => $usuario->email,
                    'role' => $usuario->role,
                    'celular' => $solicitud ? ($solicitud->celular ?? null) : null,
                    'cargo' => $solicitud ? ($solicitud->cargo ?? null) : null,
                    'created_at' => $usuario->created_at ? $usuario->created_at->toDateTimeString() : null,
                    'updated_at' => $usuario->updated_at ? $usuario->updated_at->toDateTimeString() : null,
                ];
        })->toArray();

        return response()->json([
            'success' => true,
            'data' => $usuariosArray,
            'pagination' => [
                'current_page' => $usuarios->currentPage(),
                'last_page' => $usuarios->lastPage(),
                'per_page' => $usuarios->perPage(),
                'total' => $usuarios->total(),
                'from' => $usuarios->firstItem(),
                'to' => $usuarios->lastItem(),
            ],
            'estadisticas' => $estadisticas
        ]);
    }

    /**
     * Obtener un usuario específico (API)
     */
    public function show($id)
    {
        $usuario = User::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $usuario->id,
                'name' => $usuario->name,
                'email' => $usuario->email,
                'role' => $usuario->role,
                'created_at' => $usuario->created_at->format('d/m/Y'),
            ]
        ]);
    }

    /**
     * Crear un nuevo usuario
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,jefe_red,coordinador_microred,usuario,jefe_microred,coordinador_red',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $usuario = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado correctamente',
            'data' => $usuario
        ]);
    }

    /**
     * Actualizar un usuario
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,jefe_red,coordinador_microred,usuario,jefe_microred,coordinador_red',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->role = $request->role;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente',
            'data' => $usuario
        ]);
    }

    /**
     * Eliminar un usuario
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        
        // No permitir eliminar el propio usuario
        if ($usuario->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar tu propio usuario'
            ], 403);
        }

        $usuario->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente'
        ]);
    }

    /**
     * Consultar RENIEC por DNI
     */
    public function consultarReniec(Request $request)
    {
        // Aceptar tanto POST como GET
        $dni = $request->input('dni') ?? $request->query('numero_documento');
        $tipoDoc = $request->input('tipo_documento') ?? $request->query('tipo_documento');
        
        // Log para debugging
        \Log::info('Consulta RENIEC recibida', [
            'dni' => $dni,
            'tipo_documento' => $tipoDoc,
            'method' => $request->method(),
            'all_params' => $request->all()
        ]);
        
        // Validar que sea DNI (tipo 1) para consultar RENIEC
        if ($tipoDoc && $tipoDoc != '1') {
            \Log::warning('Tipo de documento no válido para RENIEC', ['tipo' => $tipoDoc]);
            return response()->json([
                'success' => false,
                'message' => 'Solo se puede consultar RENIEC con DNI (Tipo de documento: 1)'
            ], 422);
        }
        
        // Validar que el DNI no esté vacío
        if (empty($dni)) {
            \Log::warning('DNI vacío en consulta RENIEC');
            return response()->json([
                'success' => false,
                'message' => 'El número de documento es requerido'
            ], 422);
        }
        
        $validator = Validator::make(['dni' => $dni], [
            'dni' => 'required|string|size:8|regex:/^\d{8}$/',
        ]);

        if ($validator->fails()) {
            \Log::warning('Validación de DNI falló', ['errors' => $validator->errors(), 'dni' => $dni]);
            return response()->json([
                'success' => false,
                'message' => 'El DNI debe tener 8 dígitos numéricos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $reniecService = new ReniecService();
            $resultado = $reniecService->consultarDNI($dni);
            
            \Log::info('Resultado de consulta RENIEC', [
                'success' => $resultado['success'] ?? false,
                'has_data' => isset($resultado['data'])
            ]);

            if ($resultado['success'] && isset($resultado['data'])) {
                // Generar username basado en los datos de RENIEC
                $username = $reniecService->generarUsername($resultado['data']);
                $resultado['data']['username'] = $username;
            }

            return response()->json($resultado);
        } catch (\Exception $e) {
            \Log::error('Error al consultar RENIEC', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al consultar RENIEC: ' . $e->getMessage()
            ], 500);
        }
    }
}
