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

        // Excluir usuarios admin principales por seguridad
        $query->whereNotIn('email', ['diresa@siscadit.com', 'admin@siscadit.com']);

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

        // Ordenar por más recientes primero (usando ID en lugar de created_at eliminado)
        $query->orderBy('id', 'desc');

        // Aplicar filtros según el rol del usuario (red/microred)
        $user = auth()->user();
        if ($user) {
            $role = strtolower($user->role);
            if ($role === 'jefe_red' || $role === 'jefedered') {
                $codigoRed = $this->getUserRed();
                if ($codigoRed) {
                    $query->whereHas('solicitud', function($q) use ($codigoRed) {
                        $q->where('codigo_red', $codigoRed);
                    });
                }
            } elseif ($role === 'coordinador_microred' || $role === 'coordinadordemicrored') {
                $codigoMicrored = $this->getUserMicrored();
                if ($codigoMicrored) {
                    $query->whereHas('solicitud', function($q) use ($codigoMicrored) {
                        $q->where('codigo_microred', $codigoMicrored);
                    });
                }
            }
        }
        
        // Cargar relación con solicitud
        $query->with('solicitud');

        // Paginación
        $perPage = $request->get('per_page', 15);
        $usuarios = $query->paginate($perPage);

        // Estadísticas de usuarios (excluyendo admin principales)
        $estadisticas = [
            'total' => User::whereNotIn('email', ['diresa@siscadit.com', 'admin@siscadit.com'])->count(),
            'admin' => User::where('role', 'admin')
                          ->whereNotIn('email', ['diresa@siscadit.com', 'admin@siscadit.com'])
                          ->count(),
            'jefe_red' => User::where('role', 'jefe_red')->count(),
            'coordinador_microred' => User::where('role', 'coordinador_microred')->count(),
            'usuario' => User::where('role', 'usuario')->count(),
        ];

        // Cargar solicitudes para la pestaña
        $querySolicitudes = Solicitud::query();
        
        // Aplicar filtros según el rol del usuario (red/microred)
        $user = auth()->user();
        if ($user) {
            $role = strtolower($user->role);
            if ($role === 'jefe_red' || $role === 'jefedered') {
                $codigoRed = $this->getUserRed();
                if ($codigoRed) {
                    $querySolicitudes->where('codigo_red', $codigoRed);
                }
            } elseif ($role === 'coordinador_microred' || $role === 'coordinadordemicrored') {
                $codigoMicrored = $this->getUserMicrored();
                if ($codigoMicrored) {
                    $querySolicitudes->where('codigo_microred', $codigoMicrored);
                }
            }
        }
        
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

        $querySolicitudes->with('usuario')->orderBy('id', 'desc'); // Ordenar por ID en lugar de created_at eliminado
        $solicitudes = $querySolicitudes->paginate(15, ['*'], 'solicitudes_page');

        // Estadísticas de solicitudes
        $estadisticasSolicitudes = [
            'total' => Solicitud::count(),
            'pendientes' => Solicitud::where('estado', 'pendiente')->count(),
            'aprobadas' => Solicitud::where('estado', 'aprobada')->count(),
            'rechazadas' => Solicitud::where('estado', 'rechazada')->count(),
        ];

        // Si es una petición AJAX o API, devolver JSON con datos mapeados
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            // Mapear códigos a nombres
            $tiposDoc = [
                1 => 'DNI',
                2 => 'CE',
                3 => 'PASS',
                4 => 'DIE',
                5 => 'S/ DOCUMENTO',
                6 => 'CNV'
            ];

            $redes = [
                1 => 'AGUAYTIA',
                2 => 'ATALAYA',
                3 => 'BAP-CURARAY',
                4 => 'CORONEL PORTILLO',
                5 => 'ESSALUD',
                6 => 'FEDERICO BASADRE - YARINACOCHA',
                7 => 'HOSPITAL AMAZONICO - YARINACOCHA',
                8 => 'HOSPITAL REGIONAL DE PUCALLPA',
                9 => 'NO PERTENECE A NINGUNA RED'
            ];

            // Convertir a array para asegurar serialización correcta
            $usuariosArray = $usuarios->map(function($usuario) use ($tiposDoc, $redes) {
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
                    // Datos de la solicitud
                    'tipo_documento' => $solicitud ? ($tiposDoc[$solicitud->id_tipo_documento] ?? 'N/A') : null,
                    'id_tipo_documento' => $solicitud ? ($solicitud->id_tipo_documento ?? null) : null,
                    'numero_documento' => $solicitud ? ($solicitud->numero_documento ?? null) : null,
                    'red' => $solicitud ? ($redes[$solicitud->codigo_red] ?? 'N/A') : null,
                    'codigo_red' => $solicitud ? ($solicitud->codigo_red ?? null) : null,
                    'microred' => $solicitud ? ($solicitud->codigo_microred ?? null) : null,
                    'codigo_microred' => $solicitud ? ($solicitud->codigo_microred ?? null) : null,
                    'establecimiento' => $solicitud ? ($solicitud->id_establecimiento ?? null) : null,
                    'id_establecimiento' => $solicitud ? ($solicitud->id_establecimiento ?? null) : null,
                    'correo' => $solicitud ? ($solicitud->correo ?? null) : null,
                    'cargo' => $solicitud ? ($solicitud->cargo ?? null) : null,
                    'celular' => $solicitud ? ($solicitud->celular ?? null) : null,
                    'motivo' => $solicitud ? ($solicitud->motivo ?? null) : null,
                    'solicitud_id' => $solicitud ? ($solicitud->id ?? null) : null,
                    // created_at y updated_at eliminados - campos no existen en la base de datos
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

        return view('dashboard.usuarios', compact('usuarios', 'estadisticas', 'solicitudes', 'estadisticasSolicitudes'));
    }

    /**
     * API: Obtener todos los usuarios (para AJAX)
     */
    public function apiIndex(Request $request)
    {
        $query = User::query();

        // Excluir usuarios admin principales por seguridad
        $query->whereNotIn('email', ['diresa@siscadit.com', 'admin@siscadit.com']);

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

        // Aplicar filtros según el rol del usuario (red/microred)
        $user = auth()->user();
        if ($user) {
            $role = strtolower($user->role);
            if ($role === 'jefe_red' || $role === 'jefedered') {
                $codigoRed = $this->getUserRed();
                if ($codigoRed) {
                    $query->whereHas('solicitud', function($q) use ($codigoRed) {
                        $q->where('codigo_red', $codigoRed);
                    });
                }
            } elseif ($role === 'coordinador_microred' || $role === 'coordinadordemicrored') {
                $codigoMicrored = $this->getUserMicrored();
                if ($codigoMicrored) {
                    $query->whereHas('solicitud', function($q) use ($codigoMicrored) {
                        $q->where('codigo_microred', $codigoMicrored);
                    });
                }
            }
        }

        // Ordenar por más recientes primero (usando ID en lugar de created_at eliminado)
        $query->orderBy('id', 'desc');

        // Cargar relación con solicitud
        $query->with('solicitud');

        // Paginación
        $perPage = $request->get('per_page', 10);
        $usuarios = $query->paginate($perPage);

        // Estadísticas de usuarios (excluyendo admin principales)
        $estadisticas = [
            'total' => User::whereNotIn('email', ['diresa@siscadit.com', 'admin@siscadit.com'])->count(),
            'admin' => User::where('role', 'admin')
                          ->whereNotIn('email', ['diresa@siscadit.com', 'admin@siscadit.com'])
                          ->count(),
            'jefe_red' => User::where('role', 'jefe_red')->count(),
            'jefe_microred' => User::where('role', 'jefe_microred')->count(),
            'coordinador_microred' => User::where('role', 'coordinador_microred')->count(),
            'coordinador_red' => User::where('role', 'coordinador_red')->count(),
            'usuario' => User::where('role', 'usuario')->count(),
            // Totales combinados para el footer
            'total_jefes_red' => User::whereIn('role', ['jefe_red', 'jefe_microred'])->count(),
            'total_coordinadores' => User::whereIn('role', ['coordinador_microred', 'coordinador_red'])->count(),
        ];

        // Mapear códigos a nombres
        $tiposDoc = [
            1 => 'DNI',
            2 => 'CE',
            3 => 'PASS',
            4 => 'DIE',
            5 => 'S/ DOCUMENTO',
            6 => 'CNV'
        ];

        $redes = [
            1 => 'AGUAYTIA',
            2 => 'ATALAYA',
            3 => 'BAP-CURARAY',
            4 => 'CORONEL PORTILLO',
            5 => 'ESSALUD',
            6 => 'FEDERICO BASADRE - YARINACOCHA',
            7 => 'HOSPITAL AMAZONICO - YARINACOCHA',
            8 => 'HOSPITAL REGIONAL DE PUCALLPA',
            9 => 'NO PERTENECE A NINGUNA RED'
        ];

        // Convertir a array para asegurar serialización correcta
        $usuariosArray = $usuarios->map(function($usuario) use ($tiposDoc, $redes) {
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
                    // Datos de la solicitud
                    'tipo_documento' => $solicitud ? ($tiposDoc[$solicitud->id_tipo_documento] ?? 'N/A') : null,
                    'id_tipo_documento' => $solicitud ? ($solicitud->id_tipo_documento ?? null) : null,
                    'numero_documento' => $solicitud ? ($solicitud->numero_documento ?? null) : null,
                    'red' => $solicitud ? ($redes[$solicitud->codigo_red] ?? 'N/A') : null,
                    'codigo_red' => $solicitud ? ($solicitud->codigo_red ?? null) : null,
                    'microred' => $solicitud ? ($solicitud->codigo_microred ?? null) : null,
                    'codigo_microred' => $solicitud ? ($solicitud->codigo_microred ?? null) : null,
                    'establecimiento' => $solicitud ? ($solicitud->id_establecimiento ?? null) : null,
                    'id_establecimiento' => $solicitud ? ($solicitud->id_establecimiento ?? null) : null,
                    'correo' => $solicitud ? ($solicitud->correo ?? null) : null,
                    'cargo' => $solicitud ? ($solicitud->cargo ?? null) : null,
                    'celular' => $solicitud ? ($solicitud->celular ?? null) : null,
                    'motivo' => $solicitud ? ($solicitud->motivo ?? null) : null,
                    'solicitud_id' => $solicitud ? ($solicitud->id ?? null) : null,
                    // created_at y updated_at eliminados - campos no existen en la base de datos
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
                // created_at eliminado - campo no existe en la base de datos
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
        
        // Proteger usuarios admin principales de ser modificados
        if (in_array($usuario->email, ['diresa@siscadit.com', 'admin@siscadit.com'])) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede modificar el usuario administrador principal por seguridad'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,jefe_red,coordinador_microred,usuario,jefe_microred,coordinador_red',
            // Campos de solicitud (opcionales)
            'solicitud_id' => 'nullable|integer|exists:solicitudes,id',
            'id_tipo_documento' => 'nullable|integer|between:1,6',
            'numero_documento' => 'nullable|string|max:20',
            'codigo_red' => 'nullable|integer|between:1,9',
            'codigo_microred' => 'nullable|string|max:255',
            'id_establecimiento' => 'nullable|string|max:255',
            'motivo' => 'nullable|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'celular' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
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

        // Actualizar solicitud si se proporciona solicitud_id
        if ($request->filled('solicitud_id')) {
            $solicitud = Solicitud::find($request->solicitud_id);
            if ($solicitud && $solicitud->user_id == $usuario->id) {
                $solicitud->id_tipo_documento = $request->input('id_tipo_documento', $solicitud->id_tipo_documento);
                $solicitud->numero_documento = $request->input('numero_documento', $solicitud->numero_documento);
                $solicitud->codigo_red = $request->input('codigo_red', $solicitud->codigo_red);
                $solicitud->codigo_microred = $request->input('codigo_microred', $solicitud->codigo_microred);
                $solicitud->id_establecimiento = $request->input('id_establecimiento', $solicitud->id_establecimiento);
                $solicitud->motivo = $request->input('motivo', $solicitud->motivo);
                $solicitud->cargo = $request->input('cargo', $solicitud->cargo);
                $solicitud->celular = $request->input('celular', $solicitud->celular);
                $solicitud->correo = $request->input('correo', $solicitud->correo);
                $solicitud->save();
            }
        }

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
        try {
            $usuario = User::findOrFail($id);
            
            // Proteger usuarios admin principales de ser eliminados
            if (in_array($usuario->email, ['diresa@siscadit.com', 'admin@siscadit.com'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el usuario administrador principal por seguridad'
                ], 403);
            }
            
            // No permitir eliminar el propio usuario
            if ($usuario->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes eliminar tu propio usuario'
                ], 403);
            }

            // Eliminar el usuario (las solicitudes relacionadas se actualizarán automáticamente por la foreign key)
            $usuario->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
            ], 500);
        }
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
