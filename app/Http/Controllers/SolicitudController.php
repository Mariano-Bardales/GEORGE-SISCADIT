<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SolicitudController extends Controller
{
    /**
     * Mostrar todas las solicitudes
     */
    public function index(Request $request)
    {
        $query = Solicitud::query();

        // Filtro por user_id (si se busca por user_id, no aplicar filtro de estado por defecto)
        if ($request->has('user_id') && $request->user_id !== '') {
            $query->where('user_id', $request->user_id);
        } else {
            // Filtro por estado - por defecto solo mostrar pendientes (solo si no se busca por user_id)
            $estadoFiltro = $request->has('estado') && $request->estado !== '' && $request->estado !== 'all' 
                ? $request->estado 
                : 'pendiente';
            $query->where('estado', $estadoFiltro);
        }
        
        // Si se especifica estado explícitamente, aplicarlo incluso con user_id
        if ($request->has('estado') && $request->estado !== '' && $request->estado !== 'all') {
            $query->where('estado', $request->estado);
        }

        // Búsqueda por documento, correo o motivo
        if ($request->has('buscar') && $request->buscar !== '') {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('numero_documento', 'like', "%{$buscar}%")
                  ->orWhere('correo', 'like', "%{$buscar}%")
                  ->orWhere('motivo', 'like', "%{$buscar}%");
            });
        }

        // Ordenar por más recientes primero
        $query->orderBy('created_at', 'desc');

        // Cargar relación con usuario
        $query->with('usuario');

        // Paginación
        $perPage = $request->get('per_page', 15);
        $solicitudes = $query->paginate($perPage);

        // Estadísticas
        $estadisticas = [
            'total' => Solicitud::count(),
            'pendientes' => Solicitud::where('estado', 'pendiente')->count(),
            'aprobadas' => Solicitud::where('estado', 'aprobada')->count(),
            'rechazadas' => Solicitud::where('estado', 'rechazada')->count(),
        ];

        // Si es una petición AJAX o API, devolver JSON
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

            $solicitudesData = $solicitudes->map(function($solicitud) use ($tiposDoc, $redes) {
                return [
                    'id' => $solicitud->id,
                    // Campos originales (necesarios para el JavaScript)
                    'id_tipo_documento' => $solicitud->id_tipo_documento,
                    'codigo_red' => $solicitud->codigo_red,
                    'codigo_microred' => $solicitud->codigo_microred,
                    'id_establecimiento' => $solicitud->id_establecimiento,
                    // Campos mapeados (para mostrar nombres)
                    'tipo_documento' => $tiposDoc[$solicitud->id_tipo_documento] ?? 'Desconocido',
                    'numero_documento' => $solicitud->numero_documento,
                    'red' => $redes[$solicitud->codigo_red] ?? 'Desconocida',
                    'microred' => $solicitud->codigo_microred,
                    'establecimiento' => $solicitud->id_establecimiento,
                    // Resto de campos
                    'motivo' => $solicitud->motivo,
                    'cargo' => $solicitud->cargo,
                    'celular' => $solicitud->celular,
                    'correo' => $solicitud->correo,
                    'estado' => $solicitud->estado,
                    'created_at' => $solicitud->created_at->toISOString(),
                    'fecha_solicitud' => $solicitud->created_at->format('d/m/Y H:i'),
                    'user_id' => $solicitud->user_id,
                    'usuario_creado' => $solicitud->usuario ? [
                        'id' => $solicitud->usuario->id,
                        'name' => $solicitud->usuario->name,
                        'email' => $solicitud->usuario->email,
                    ] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $solicitudesData,
                'pagination' => [
                    'current_page' => $solicitudes->currentPage(),
                    'last_page' => $solicitudes->lastPage(),
                    'per_page' => $solicitudes->perPage(),
                    'total' => $solicitudes->total(),
                ],
                'estadisticas' => $estadisticas
            ]);
        }

        return view('dashboard.solicitudes', compact('solicitudes', 'estadisticas'));
    }

    /**
     * Actualizar el estado de una solicitud
     */
    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,aprobada,rechazada'
        ]);

        $solicitud = Solicitud::findOrFail($id);
        $solicitud->estado = $request->estado;
        $solicitud->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado de la solicitud actualizado correctamente'
        ]);
    }

    /**
     * Crear una nueva solicitud
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_tipo_documento' => 'required|integer|between:1,6',
            'numero_documento' => 'required|string|max:20',
            'codigo_red' => 'required|integer|between:1,9',
            'codigo_microred' => 'required|string|max:255',
            'id_establecimiento' => 'required|string|max:255',
            'motivo' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'celular' => 'required|string|max:20',
            'correo' => 'required|email|max:255',
        ]);

        try {
            $solicitud = Solicitud::create([
                'id_tipo_documento' => $request->id_tipo_documento,
                'numero_documento' => $request->numero_documento,
                'codigo_red' => $request->codigo_red,
                'codigo_microred' => $request->codigo_microred,
                'id_establecimiento' => $request->id_establecimiento,
                'motivo' => $request->motivo,
                'cargo' => $request->cargo,
                'celular' => $request->celular,
                'correo' => $request->correo,
                'accept_terms' => true,
                'estado' => 'pendiente',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Solicitud creada correctamente',
                'data' => $solicitud
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar una solicitud
     */
    public function update(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);

        $request->validate([
            'id_tipo_documento' => 'required|integer|between:1,6',
            'numero_documento' => 'required|string|max:20',
            'codigo_red' => 'required|integer|between:1,9',
            'codigo_microred' => 'required|string|max:255',
            'id_establecimiento' => 'required|string|max:255',
            'motivo' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'celular' => 'required|string|max:20',
            'correo' => 'required|email|max:255',
        ]);

        try {
            $solicitud->update([
                'id_tipo_documento' => $request->id_tipo_documento,
                'numero_documento' => $request->numero_documento,
                'codigo_red' => $request->codigo_red,
                'codigo_microred' => $request->codigo_microred,
                'id_establecimiento' => $request->id_establecimiento,
                'motivo' => $request->motivo,
                'cargo' => $request->cargo,
                'celular' => $request->celular,
                'correo' => $request->correo,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Solicitud actualizada correctamente',
                'data' => $solicitud
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una solicitud
     */
    public function destroy($id)
    {
        try {
            $solicitud = Solicitud::findOrFail($id);
            $solicitud->delete();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear usuario desde una solicitud (API - acepta ID desde body)
     */
    public function crearUsuarioDesdeSolicitud(Request $request)
    {
        $request->validate([
            'solicitud_id' => 'required|integer|exists:solicitudes,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,jefe_red,coordinador_microred,usuario,jefe_microred,coordinador_red'
        ]);

        $solicitud = Solicitud::findOrFail($request->solicitud_id);

        // Verificar si ya existe un usuario con ese correo
        $usuarioExistente = User::where('email', $request->email)->first();
        if ($usuarioExistente) {
            // Vincular la solicitud con el usuario existente
            $solicitud->user_id = $usuarioExistente->id;
            $solicitud->estado = 'aprobada';
            $solicitud->save();

            return response()->json([
                'success' => true,
                'message' => 'La solicitud ha sido vinculada con un usuario existente',
                'usuario_id' => $usuarioExistente->id
            ]);
        }

        // Crear el usuario con los datos proporcionados
        $usuario = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        // Vincular la solicitud con el usuario creado y marcar como aprobada
        $solicitud->user_id = $usuario->id;
        $solicitud->estado = 'aprobada';
        $solicitud->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado correctamente',
            'usuario_id' => $usuario->id,
            'email' => $usuario->email
        ]);
    }

    /**
     * Crear usuario desde una solicitud (ruta con parámetro)
     */
    public function crearUsuario(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);

        // Verificar si ya existe un usuario con ese correo
        $usuarioExistente = User::where('email', $solicitud->correo)->first();
        if ($usuarioExistente) {
            // Vincular la solicitud con el usuario existente
            $solicitud->user_id = $usuarioExistente->id;
            $solicitud->estado = 'aprobada';
            $solicitud->save();

            return response()->json([
                'success' => true,
                'message' => 'La solicitud ha sido vinculada con un usuario existente',
                'usuario_id' => $usuarioExistente->id
            ]);
        }

        // Determinar el rol según el cargo
        $rol = $this->determinarRol($solicitud->cargo);

        // Generar contraseña temporal
        $passwordTemporal = $this->generarPasswordTemporal();

        // Crear el usuario
        $usuario = User::create([
            'name' => $solicitud->cargo . ' - ' . $solicitud->numero_documento,
            'email' => $solicitud->correo,
            'password' => Hash::make($passwordTemporal),
            'role' => $rol,
            'email_verified_at' => now(),
        ]);

        // Vincular la solicitud con el usuario creado y marcar como aprobada
        $solicitud->user_id = $usuario->id;
        $solicitud->estado = 'aprobada';
        $solicitud->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado correctamente',
            'usuario_id' => $usuario->id,
            'password_temporal' => $passwordTemporal,
            'email' => $solicitud->correo
        ]);
    }

    /**
     * Determinar el rol según el cargo
     */
    private function determinarRol($cargo)
    {
        $cargo = strtolower($cargo);
        
        if (strpos($cargo, 'jefe') !== false || strpos($cargo, 'director') !== false) {
            return 'jefe_red';
        } elseif (strpos($cargo, 'coordinador') !== false) {
            return 'coordinador_microred';
        } else {
            return 'usuario';
        }
    }

    /**
     * Generar contraseña temporal
     */
    private function generarPasswordTemporal()
    {
        return substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789'), 0, 8);
    }

    /**
     * Obtener detalles de una solicitud (API)
     */
    public function show($id)
    {
        $solicitud = Solicitud::findOrFail($id);
        
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

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $solicitud->id,
                'tipo_documento' => $tiposDoc[$solicitud->id_tipo_documento] ?? 'Desconocido',
                'numero_documento' => $solicitud->numero_documento,
                'red' => $redes[$solicitud->codigo_red] ?? 'Desconocida',
                'microred' => $solicitud->codigo_microred,
                'establecimiento' => $solicitud->id_establecimiento,
                'motivo' => $solicitud->motivo,
                'cargo' => $solicitud->cargo,
                'celular' => $solicitud->celular,
                'correo' => $solicitud->correo,
                'estado' => $solicitud->estado,
                'fecha_solicitud' => $solicitud->created_at->format('d/m/Y H:i'),
                'user_id' => $solicitud->user_id,
                'usuario_creado' => $solicitud->usuario ? [
                    'id' => $solicitud->usuario->id,
                    'name' => $solicitud->usuario->name,
                    'email' => $solicitud->usuario->email,
                ] : null,
            ]
        ]);
    }
}

