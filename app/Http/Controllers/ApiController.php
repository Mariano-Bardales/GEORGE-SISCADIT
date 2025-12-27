<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nino;
use App\Models\ControlRn;
use App\Models\ControlMenor1;
use App\Models\TamizajeNeonatal;
use App\Models\VacunaRn;
use App\Models\User;
use App\Models\DatosExtra;
use App\Models\Madre;
use App\Models\RecienNacido;
use App\Models\VisitaDomiciliaria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ApiController extends Controller
{
    /**
     * Helper para obtener el ID correcto del niño (id)
     */
    private function getNinoId($nino)
    {
        return $nino->id ?? null;
    }
    
    /**
     * Helper para buscar un niño por ID
     */
    private function findNino($id)
    {
        return Nino::findOrFail($id);
    }
    
    /**
     * Helper para obtener la madre de un niño (maneja ambas relaciones)
     */
    private function obtenerMadreDelNino($nino)
    {
        $madre = null;
        
        // Primero intentar por id_madre (si existe)
        if ($nino->id_madre) {
            $madre = Madre::find($nino->id_madre);
        }
        
        // Si no existe, buscar por id_niño en la tabla madres
        if (!$madre) {
            $madre = Madre::where('id_niño', $nino->id)->first();
        }
        
        // Si encontramos la madre, retornar sus datos
        if ($madre) {
            return [
                'dni' => $madre->dni,
                'apellidos_nombres' => $madre->apellidos_nombres,
                'celular' => $madre->celular,
                'domicilio' => $madre->domicilio,
                'referencia_direccion' => $madre->referencia_direccion,
            ];
        }
        
        return null;
    }
    
    public function dashboardStats()
    {
        // Aplicar filtros según el rol del usuario
        $queryNinos = Nino::query();
        $queryNinos = $this->applyRedMicroredFilter($queryNinos, 'datosExtra');
        $totalNinos = $queryNinos->count();
        
        // Para controles, necesitamos filtrar por los niños filtrados
        $ninosIds = $queryNinos->pluck('id')->toArray();
        $totalControles = ControlRn::whereIn('id_niño', $ninosIds)->count() + 
                         ControlMenor1::whereIn('id_niño', $ninosIds)->count();
        
        // Para usuarios, aplicar filtros según rol
        $queryUsuarios = User::query();
        $user = auth()->user();
        if ($user) {
            $role = strtolower($user->role);
            if ($role === 'jefe_red' || $role === 'jefedered') {
                $codigoRed = $this->getUserRed();
                if ($codigoRed) {
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
                    $nombreRed = $redes[$codigoRed] ?? null;
                    if ($nombreRed) {
                        $queryUsuarios->whereHas('solicitud', function($q) use ($codigoRed) {
                            $q->where('codigo_red', $codigoRed);
                        });
                    }
                }
            } elseif ($role === 'coordinador_microred' || $role === 'coordinadordemicrored') {
                $codigoMicrored = $this->getUserMicrored();
                if ($codigoMicrored) {
                    $queryUsuarios->whereHas('solicitud', function($q) use ($codigoMicrored) {
                        $q->where('codigo_microred', $codigoMicrored);
                    });
                }
            }
        }
        $totalUsuarios = $queryUsuarios->count();
        
        // Calcular alertas reales
        $totalAlertas = 0;
        $hoy = Carbon::now();
        
        $ninos = $queryNinos->get();
        
        foreach ($ninos as $nino) {
            // Validar que el niño tenga fecha de nacimiento
            if (!$nino->fecha_nacimiento) {
                continue; // Saltar este niño si no tiene fecha de nacimiento
            }
            
            try {
                $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                $edadDias = $fechaNacimiento->diffInDays($hoy);
            } catch (\Exception $e) {
                continue; // Saltar este niño si hay error al parsear la fecha
            }
            
            // Alertas de controles recién nacido
            if ($edadDias <= 28) {
                $ninoId = $this->getNinoId($nino);
                // Usar relación del modelo para evitar problemas de codificación
                $controlesRn = $nino->controlesRn()->count();
                $controlesEsperados = 0;
                
                $rangosRN = [
                    1 => ['min' => 2, 'max' => 6],
                    2 => ['min' => 7, 'max' => 13],
                    3 => ['min' => 14, 'max' => 20],
                    4 => ['min' => 21, 'max' => 28]
                ];
                
                foreach ($rangosRN as $num => $rango) {
                    if ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) {
                        $controlesEsperados++;
                    } else if ($edadDias > $rango['max']) {
                        $controlesEsperados++;
                    }
                }
                
                if ($controlesRn < $controlesEsperados) {
                    $totalAlertas += ($controlesEsperados - $controlesRn);
                }
            }
            
            // Alertas de CRED mensual
            if ($edadDias >= 29 && $edadDias <= 359) {
                $ninoId = $this->getNinoId($nino);
                // Usar relación del modelo para evitar problemas de codificación
                $controlesCred = $nino->controlesMenor1()->count();
                $controlesEsperados = 0;
                
                $rangosCred = [
                    1 => ['min' => 29, 'max' => 59],
                    2 => ['min' => 60, 'max' => 89],
                    3 => ['min' => 90, 'max' => 119],
                    4 => ['min' => 120, 'max' => 149],
                    5 => ['min' => 150, 'max' => 179],
                    6 => ['min' => 180, 'max' => 209],
                    7 => ['min' => 210, 'max' => 239],
                    8 => ['min' => 240, 'max' => 269],
                    9 => ['min' => 270, 'max' => 299],
                    10 => ['min' => 300, 'max' => 329],
                    11 => ['min' => 330, 'max' => 359]
                ];
                
                foreach ($rangosCred as $num => $rango) {
                    if ($edadDias > $rango['max']) {
                        $controlesEsperados++;
                    } else if ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) {
                        $controlesEsperados++;
                    }
                }
                
                if ($controlesCred < $controlesEsperados) {
                    $totalAlertas += ($controlesEsperados - $controlesCred);
                }
            }
            
            // Alertas de tamizaje
            if ($edadDias >= 1 && $edadDias <= 29) {
                $ninoId = $this->getNinoId($nino);
                // Usar relación del modelo para evitar problemas de codificación
                $tamizaje = $nino->tamizajeNeonatal;
                if (!$tamizaje) {
                    $totalAlertas++;
                }
            }
            
            // Alertas de vacunas
            if ($edadDias <= 30) {
                $ninoId = $this->getNinoId($nino);
                // Usar relación del modelo para evitar problemas de codificación
                $vacunas = $nino->vacunaRn;
                if (!$vacunas || !$vacunas->fecha_bcg || !$vacunas->fecha_hvb) {
                    $totalAlertas++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_ninos' => $totalNinos,
                'total_controles' => $totalControles,
                'total_usuarios' => $totalUsuarios,
                'total_alertas' => $totalAlertas,
            ]
        ]);
    }

    public function reportesEstadisticas()
    {
        // Aplicar filtros según el rol del usuario
        $queryMasculino = Nino::where('genero', 'M');
        $queryMasculino = $this->applyRedMicroredFilter($queryMasculino, 'datosExtra');
        $masculino = $queryMasculino->count();
        
        $queryFemenino = Nino::where('genero', 'F');
        $queryFemenino = $this->applyRedMicroredFilter($queryFemenino, 'datosExtra');
        $femenino = $queryFemenino->count();

        return response()->json([
            'success' => true,
            'data' => [
                'genero' => [
                    'masculino' => $masculino,
                    'femenino' => $femenino,
                ]
            ]
        ]);
    }

    public function ninos(Request $request)
    {
        try {
            // Cargar relaciones - para madre usamos un método personalizado
            $query = Nino::with(['datosExtra']);
            
            // Aplicar filtros según el rol del usuario (red/microred)
            $query = $this->applyRedMicroredFilter($query, 'datosExtra');
            
            // Filtro por género
            if ($request->has('genero') && $request->genero !== '') {
                $query->where('genero', $request->genero);
            }
            
            // Búsqueda por nombre o documento
            if ($request->has('buscar') && $request->buscar !== '') {
                $buscar = $request->buscar;
                $query->where(function($q) use ($buscar) {
                    $q->where('apellidos_nombres', 'like', "%{$buscar}%")
                      ->orWhere('numero_doc', 'like', "%{$buscar}%");
                });
            }
            
            // Ordenar por id (más recientes primero)
            $query->orderBy('id', 'desc');
            
            // Paginación
            $perPage = $request->get('per_page', 25);
            $ninos = $query->paginate($perPage);
            
            // Log para debugging
            \Log::info('API ninos - Total encontrados: ' . $ninos->total());
            
            // Formatear datos para el frontend
            $ninosFormateados = $ninos->map(function($nino) {
                // Mapear tipo_doc a id_tipo_documento para compatibilidad
                $tiposDoc = [
                    'DNI' => 1,
                    'CE' => 2,
                    'PASS' => 3,
                    'DIE' => 4,
                    'S/ DOCUMENTO' => 5,
                    'CNV' => 6
                ];
                $idTipoDoc = $tiposDoc[$nino->tipo_doc] ?? null;
                
                return [
                    'id' => $this->getNinoId($nino),
                    'id_niño' => $this->getNinoId($nino), // Para compatibilidad con el frontend
                    'establecimiento' => $nino->establecimiento ?? '-',
                    'tipo_doc' => $nino->tipo_doc ?? '-',
                    'id_tipo_documento' => $idTipoDoc, // Para compatibilidad
                    'numero_doc' => $nino->numero_doc ?? '-',
                    'numero_documento' => $nino->numero_doc ?? '-', // Para compatibilidad
                    'apellidos_nombres' => $nino->apellidos_nombres ?? '-',
                    'fecha_nacimiento' => $nino->fecha_nacimiento ? $nino->fecha_nacimiento->format('Y-m-d') : null,
                    'genero' => $nino->genero ?? 'M',
                    'edad_meses' => $nino->edad_meses ?? null,
                    'edad_dias' => $nino->edad_dias ?? null,
                    'datos_extras' => $nino->datosExtra ? [
                        'red' => $nino->datosExtra->red,
                        'microred' => $nino->datosExtra->microred,
                        'eess_nacimiento' => $nino->datosExtra->eess_nacimiento,
                        'distrito' => $nino->datosExtra->distrito,
                        'provincia' => $nino->datosExtra->provincia,
                        'departamento' => $nino->datosExtra->departamento,
                        'seguro' => $nino->datosExtra->seguro,
                        'programa' => $nino->datosExtra->programa,
                    ] : null,
                    'madre' => $this->obtenerMadreDelNino($nino),
                    // created_at y updated_at eliminados - campos no existen en la base de datos
                ];
            });
            
            return response()->json([
                'success' => true, 
                'data' => $ninosFormateados,
                'pagination' => [
                    'current_page' => $ninos->currentPage(),
                    'last_page' => $ninos->lastPage(),
                    'per_page' => $ninos->perPage(),
                    'total' => $ninos->total(),
                    'from' => $ninos->firstItem(),
                    'to' => $ninos->lastItem(),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en API ninos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function controlesRecienNacido(Request $request)
    {
        $ninoId = $request->query('nino_id');
        $controlId = $request->query('control_id');
        
        if ($controlId) {
            // Obtener un control específico
            $control = ControlRn::find($controlId);
            if (!$control) {
                return response()->json(['success' => false, 'message' => 'Control no encontrado'], 404);
            }
            return response()->json(['success' => true, 'data' => ['control' => $control]]);
        }
        
        if ($ninoId) {
            try {
                // Buscar el niño primero para obtener el id_niño correcto
                $nino = $this->findNino($ninoId);
                $ninoIdReal = $this->getNinoId($nino);
                
                // Buscar controles usando el id_niño real
                $controles = ControlRn::where('id_niño', $ninoIdReal)->orderBy('numero_control', 'asc')->get();
                
                // Solo generar datos de ejemplo si NO hay controles reales
                // Los controles reales siempre tienen prioridad
                if ($controles->isEmpty()) {
                    $controles = $this->generarDatosEjemploRecienNacido($nino, $ninoIdReal);
                } else {
                    // Asegurar que los controles reales tengan el formato correcto
                    $controles = $controles->map(function($control) use ($nino) {
                        // Calcular edad y estado dinámicamente
                        $edadDias = null;
                        $estado = 'SEGUIMIENTO';
                        
                        if ($nino && $nino->fecha_nacimiento && $control->fecha) {
                            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                            $fechaControl = Carbon::parse($control->fecha);
                            $edadDias = $fechaNacimiento->diffInDays($fechaControl);
                            
                            // Calcular estado usando RangosCredService
                            $validacion = \App\Services\RangosCredService::validarControl(
                                $control->numero_control, 
                                $edadDias, 
                                'recien_nacido'
                            );
                            $estado = $validacion['estado'];
                        }
                        
                        return [
                            'id' => $control->id,
                            'id_niño' => $control->id_niño,
                            'numero_control' => $control->numero_control,
                            'fecha' => $control->fecha ? $control->fecha->format('Y-m-d') : null,
                            'edad' => $edadDias,
                            'estado' => $estado,
                            'es_ejemplo' => false, // Marcar como control real
                        ];
                    });
                }
            } catch (\Exception $e) {
                // Si no se encuentra el niño, devolver vacío
                $controles = collect([]);
            }
        } else {
            $controles = ControlRn::all();
        }
        
        return response()->json(['success' => true, 'data' => ['controles' => $controles]]);
    }
    
    /**
     * Generar datos de ejemplo para controles recién nacido
     */
    private function generarDatosEjemploRecienNacido($nino, $ninoIdReal)
    {
        $fechaNacimiento = $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento) : Carbon::now()->subDays(15);
        $hoy = Carbon::now();
        $edadDias = $fechaNacimiento->diffInDays($hoy);
        
        // Solo generar si el niño tiene 28 días o menos
        if ($edadDias > 28) {
            return collect([]);
        }
        
        $controlesEjemplo = collect();
        $rangos = [
            1 => ['min' => 2, 'max' => 6, 'peso' => 3.3, 'talla' => 49.5, 'pc' => 34.5],
            2 => ['min' => 7, 'max' => 13, 'peso' => 3.4, 'talla' => 50, 'pc' => 35],
            3 => ['min' => 14, 'max' => 20, 'peso' => 3.5, 'talla' => 50.5, 'pc' => 35.5],
            4 => ['min' => 21, 'max' => 28, 'peso' => 3.6, 'talla' => 51, 'pc' => 36],
        ];
        
        $seed = $ninoIdReal % 100;
        
        foreach ($rangos as $numeroControl => $rango) {
            if ($edadDias >= $rango['min']) {
                $diasDesdeNacimiento = $rango['min'] + (($rango['max'] - $rango['min']) / 2);
                $fechaControl = $fechaNacimiento->copy()->addDays($diasDesdeNacimiento);
                
                $variacionPeso = (($seed + $numeroControl) % 20 - 10) / 100;
                $variacionTalla = (($seed + $numeroControl * 2) % 10 - 5) / 10;
                $variacionPC = (($seed + $numeroControl * 3) % 6 - 3) / 10;
                
                $controlesEjemplo->push([
                    'id' => null,
                    'id_niño' => $ninoIdReal,
                    'numero_control' => $numeroControl,
                    'fecha' => $fechaControl->format('Y-m-d'),
                    'edad' => (int)$diasDesdeNacimiento,
                    'peso' => round($rango['peso'] + $variacionPeso, 2),
                    'talla' => round($rango['talla'] + $variacionTalla, 1),
                    'perimetro_cefalico' => round($rango['pc'] + $variacionPC, 1),
                    'estado' => ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) ? 'cumple' : 'cumple',
                    'es_ejemplo' => true,
                ]);
            }
        }
        
        return $controlesEjemplo;
    }
    
    public function registrarControlRecienNacido(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Validamos que venga un ID y dejamos que findNino resuelva si es id_niño o id
            'nino_id' => 'required|integer',
            'numero_control' => 'required|integer|between:1,4',
            'fecha_control' => 'required|date',
            'peso' => 'nullable|numeric|min:0',
            'talla' => 'nullable|numeric|min:0',
            'perimetro_cefalico' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Buscar al niño usando helper que soporta id_niño o id
            $nino = $this->findNino($request->nino_id);
            $ninoIdReal = $this->getNinoId($nino);
            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
            $fechaControl = Carbon::parse($request->fecha_control);
            $edadDias = $fechaNacimiento->diffInDays($fechaControl);

            // Determinar estado basado en rangos
            $rangos = [
                1 => ['min' => 2, 'max' => 6],
                2 => ['min' => 7, 'max' => 13],
                3 => ['min' => 14, 'max' => 20],
                4 => ['min' => 21, 'max' => 28],
            ];
            
            $rango = $rangos[$request->numero_control] ?? ['min' => 2, 'max' => 28];
            $estado = ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) ? 'cumple' : 'no_cumple';
            
            $ninoId = $this->getNinoId($nino);
            $control = ControlRn::create([
                'id_niño' => $ninoId,
                'numero_control' => $request->numero_control,
                'fecha' => $request->fecha_control,
                'edad' => $edadDias,
                'estado' => $estado,
                'peso' => $request->peso ?? null,
                'talla' => $request->talla ?? null,
                'perimetro_cefalico' => $request->perimetro_cefalico ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Control registrado exitosamente',
                'data' => $control
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar control: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function actualizarControlRecienNacido(Request $request, $id)
    {
        $control = ControlRn::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'fecha_control' => 'required|date',
            'peso' => 'nullable|numeric|min:0',
            'talla' => 'nullable|numeric|min:0',
            'perimetro_cefalico' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $nino = $control->nino;
            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
            $fechaControl = Carbon::parse($request->fecha_control);
            $edadDias = $fechaNacimiento->diffInDays($fechaControl);

            $rangos = [
                1 => ['min' => 2, 'max' => 6],
                2 => ['min' => 7, 'max' => 13],
                3 => ['min' => 14, 'max' => 20],
                4 => ['min' => 21, 'max' => 28],
            ];
            
            $rango = $rangos[$control->numero_control] ?? ['min' => 2, 'max' => 28];
            $estado = ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) ? 'cumple' : 'no_cumple';

            $control->update([
                'fecha' => $request->fecha_control,
                'edad' => $edadDias,
                'estado' => $estado,
                'peso' => $request->peso ?? $control->peso,
                'talla' => $request->talla ?? $control->talla,
                'perimetro_cefalico' => $request->perimetro_cefalico ?? $control->perimetro_cefalico,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Control actualizado exitosamente',
                'data' => $control
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar control: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function eliminarControlRecienNacido($id)
    {
        try {
            $control = ControlRn::findOrFail($id);
            $control->delete();

            return response()->json([
                'success' => true,
                'message' => 'Control eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar control: ' . $e->getMessage()
            ], 500);
        }
    }

    public function eliminarControlCredMensual($id)
    {
        try {
            $control = ControlMenor1::findOrFail($id);
            $control->delete();

            return response()->json([
                'success' => true,
                'message' => 'Control CRED mensual eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar control CRED mensual: ' . $e->getMessage()
            ], 500);
        }
    }

    public function controlesCredMensual(Request $request)
    {
        $ninoId = $request->query('nino_id');
        $mes = $request->query('mes');
        
        if ($ninoId) {
            try {
                $nino = $this->findNino($ninoId);
                $ninoIdReal = $this->getNinoId($nino);
                
                $query = ControlMenor1::where('id_niño', $ninoIdReal);
                if ($mes) {
                    $query->where('numero_control', $mes);
                }
                $controles = $query->orderBy('numero_control', 'asc')->get();
                
                // Mapear controles reales al formato esperado por la vista
                $controlesFormateados = $controles->map(function($control) use ($nino) {
                    // Calcular edad en días desde la fecha de nacimiento y la fecha del control
                    $edadDias = null;
                    $estadoRecalculado = 'SEGUIMIENTO'; // Por defecto
                    
                    if ($nino->fecha_nacimiento && $control->fecha) {
                        $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                        $fechaControl = Carbon::parse($control->fecha);
                        $edadDias = $fechaNacimiento->diffInDays($fechaControl);
                        
                        // Rangos CRED mensual
                        $rangosCRED = [
                            1 => ['min' => 29, 'max' => 59],
                            2 => ['min' => 60, 'max' => 89],
                            3 => ['min' => 90, 'max' => 119],
                            4 => ['min' => 120, 'max' => 149],
                            5 => ['min' => 150, 'max' => 179],
                            6 => ['min' => 180, 'max' => 209],
                            7 => ['min' => 210, 'max' => 239],
                            8 => ['min' => 240, 'max' => 269],
                            9 => ['min' => 270, 'max' => 299],
                            10 => ['min' => 300, 'max' => 329],
                            11 => ['min' => 330, 'max' => 359],
                        ];
                        
                        $numeroControl = $control->numero_control;
                        $rango = $rangosCRED[$numeroControl] ?? ['min' => 0, 'max' => 365];
                        
                        // Si hay control registrado, verificar si está dentro del rango
                        if ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) {
                            $estadoRecalculado = 'CUMPLE';
                        } elseif ($edadDias > $rango['max']) {
                            // Control registrado pero fuera del rango
                            $estadoRecalculado = 'NO CUMPLE';
                        } else {
                            // Control registrado pero antes del rango mínimo (raro, pero posible)
                            $estadoRecalculado = 'NO CUMPLE';
                        }
                    }
                    
                    return [
                        'id' => $control->id_cred ?? $control->id,
                        'id_niño' => $control->id_niño,
                        'numero_control' => $control->numero_control,
                        'fecha' => $control->fecha ? ($control->fecha instanceof \Carbon\Carbon ? $control->fecha->format('Y-m-d') : $control->fecha) : null,
                        'edad' => $edadDias, // Calcular edad en días desde fecha de nacimiento y fecha del control
                        'edad_dias' => $edadDias, // Alias para compatibilidad
                        'estado' => $estadoRecalculado,
                        // estado_cred_once y estado_cred_final eliminados - campos innecesarios
                        'es_ejemplo' => false, // Marcar como dato real de la base de datos
                    ];
                });
                
                // Solo generar datos de ejemplo si NO hay controles reales
                // Los controles reales siempre tienen prioridad
                if ($controlesFormateados->isEmpty()) {
                    $controlesFormateados = $this->generarDatosEjemploCredMensual($nino, $ninoIdReal);
                }
                
                return response()->json([
                    'success' => true, 
                    'data' => [
                        'controles' => $controlesFormateados,
                        'fecha_nacimiento' => $nino->fecha_nacimiento ? $nino->fecha_nacimiento->format('Y-m-d') : null
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al obtener controles: ' . $e->getMessage(),
                    'data' => [
                        'controles' => [],
                        'fecha_nacimiento' => null
                    ]
                ], 500);
            }
        } else {
            // Si no hay nino_id, devolver todos los controles formateados
            $controles = ControlMenor1::all();
            // Si no hay nino_id, necesitamos obtener la fecha de nacimiento de cada niño
            $controlesFormateados = $controles->map(function($control) {
                $nino = $control->nino;
                $edadDias = null;
                
                // Calcular edad en días si tenemos fecha de nacimiento y fecha del control
                $estado = 'SEGUIMIENTO';
                if ($nino && $nino->fecha_nacimiento && $control->fecha) {
                    $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                    $fechaControl = Carbon::parse($control->fecha);
                    $edadDias = $fechaNacimiento->diffInDays($fechaControl);
                    
                    // Calcular estado dinámicamente usando RangosCredService
                    $validacion = \App\Services\RangosCredService::validarControl(
                        $control->numero_control, 
                        $edadDias, 
                        'cred'
                    );
                    $estado = $validacion['estado'];
                }
                
                return [
                    'id' => $control->id_cred ?? $control->id,
                    'id_niño' => $control->id_niño,
                    'numero_control' => $control->numero_control,
                    'fecha' => $control->fecha ? ($control->fecha instanceof \Carbon\Carbon ? $control->fecha->format('Y-m-d') : $control->fecha) : null,
                    'edad' => $edadDias, // Calcular edad en días
                    'edad_dias' => $edadDias, // Alias para compatibilidad
                    'estado' => $estado, // Calculado dinámicamente
                    // estado_cred_once y estado_cred_final eliminados - campos innecesarios
                    'es_ejemplo' => false,
                ];
            });
            return response()->json(['success' => true, 'data' => ['controles' => $controlesFormateados]]);
        }
    }
    
    /**
     * Generar datos de ejemplo realistas para controles CRED mensual basados en el ID del niño
     */
    private function generarDatosEjemploCredMensual($nino, $ninoIdReal)
    {
        $fechaNacimiento = $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento) : Carbon::now()->subMonths(6);
        $hoy = Carbon::now();
        $edadDias = $fechaNacimiento->diffInDays($hoy);
        
        // Solo generar controles si el niño está en el rango de edad (29-359 días)
        if ($edadDias < 29 || $edadDias > 359) {
            return collect([]);
        }
        
        $controlesEjemplo = collect();
        $rangos = [
            1 => ['min' => 29, 'max' => 59],
            2 => ['min' => 60, 'max' => 89],
            3 => ['min' => 90, 'max' => 119],
            4 => ['min' => 120, 'max' => 149],
            5 => ['min' => 150, 'max' => 179],
            6 => ['min' => 180, 'max' => 209],
            7 => ['min' => 210, 'max' => 239],
            8 => ['min' => 240, 'max' => 269],
            9 => ['min' => 270, 'max' => 299],
            10 => ['min' => 300, 'max' => 329],
            11 => ['min' => 330, 'max' => 359],
        ];
        
        foreach ($rangos as $numeroControl => $rango) {
            // Solo generar controles que ya deberían haberse realizado (edad actual >= min del rango)
            if ($edadDias >= $rango['min']) {
                // Calcular fecha del control (aproximadamente en el medio del rango)
                $diasDesdeNacimiento = $rango['min'] + (($rango['max'] - $rango['min']) / 2);
                $fechaControl = $fechaNacimiento->copy()->addDays($diasDesdeNacimiento);
                
                // Determinar estado basándose en la edad del control (diasDesdeNacimiento) y el rango permitido
                $estado = 'SEGUIMIENTO'; // Por defecto
                if ($diasDesdeNacimiento >= $rango['min'] && $diasDesdeNacimiento <= $rango['max']) {
                    $estado = 'CUMPLE';
                } elseif ($diasDesdeNacimiento > $rango['max']) {
                    $estado = 'NO CUMPLE'; // Control fuera del rango
                } elseif ($diasDesdeNacimiento < $rango['min']) {
                    $estado = 'SEGUIMIENTO'; // Aún no llega al rango
                }
                
                $controlesEjemplo->push([
                    'id' => null, // No es un registro real
                    'id_niño' => $ninoIdReal,
                    'numero_control' => $numeroControl,
                    'fecha' => $fechaControl->format('Y-m-d'),
                    'edad' => (int)$diasDesdeNacimiento,
                    'estado' => $estado,
                    'es_ejemplo' => true, // Marcar como dato de ejemplo
                ]);
            }
        }
        
        return $controlesEjemplo;
    }
    
    public function registrarCredMensual(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'nino_id' => 'required|integer',
            'mes' => 'required|integer|between:1,11',
            'fecha_control' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $nino = $this->findNino($request->nino_id);
            $ninoIdReal = $this->getNinoId($nino);
            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
            $fechaControl = Carbon::parse($request->fecha_control);
            $edadDias = $fechaNacimiento->diffInDays($fechaControl);
            $edadMeses = $fechaNacimiento->diffInMonths($fechaControl);

            // Rangos de edad para cada mes (aproximado)
            $rangos = [
                1 => ['min' => 30, 'max' => 60],
                2 => ['min' => 60, 'max' => 90],
                3 => ['min' => 90, 'max' => 120],
                4 => ['min' => 120, 'max' => 150],
                5 => ['min' => 150, 'max' => 180],
                6 => ['min' => 180, 'max' => 210],
                7 => ['min' => 210, 'max' => 240],
                8 => ['min' => 240, 'max' => 270],
                9 => ['min' => 270, 'max' => 300],
                10 => ['min' => 300, 'max' => 330],
                11 => ['min' => 330, 'max' => 365],
            ];
            
            $rango = $rangos[$request->mes] ?? ['min' => 0, 'max' => 365];
            $estado = ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) ? 'cumple' : 'no_cumple';

            if ($id) {
                // Actualizar control existente
                $control = ControlMenor1::findOrFail($id);
                $control->update([
                    'fecha' => $request->fecha_control,
                    'edad' => $edadDias,
                    'estado' => $estado,
                ]);
            } else {
                // Crear nuevo control
                $control = ControlMenor1::create([
                    'id_niño' => $ninoIdReal,
                    'numero_control' => $request->mes,
                    'fecha' => $request->fecha_control,
                    'edad' => $edadDias,
                    'estado' => $estado,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $id ? 'Control actualizado exitosamente' : 'Control registrado exitosamente',
                'data' => $control
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar control: ' . $e->getMessage()
            ], 500);
        }
    }

    public function tamizaje(Request $request)
    {
        $ninoId = $request->query('nino_id');
        if ($ninoId) {
            $tamizaje = TamizajeNeonatal::where('id_niño', $ninoId)->first();
            
            // Si no hay tamizaje real, generar datos de ejemplo
            if (!$tamizaje) {
                try {
                    $nino = $this->findNino($ninoId);
                    $ninoIdReal = $this->getNinoId($nino);
                    $tamizaje = $this->generarDatosEjemploTamizaje($nino, $ninoIdReal);
                    return response()->json([
                        'success' => true, 
                        'data' => $tamizaje ? [$tamizaje] : []
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => true, 
                        'data' => []
                    ]);
                }
            }
            
            // Devolver como array para compatibilidad con el frontend
            return response()->json([
                'success' => true, 
                'data' => $tamizaje ? [$tamizaje] : []
            ]);
        } else {
            $tamizaje = TamizajeNeonatal::all();
            return response()->json(['success' => true, 'data' => $tamizaje]);
        }
    }
    
    /**
     * Generar datos de ejemplo para tamizaje neonatal
     */
    private function generarDatosEjemploTamizaje($nino, $ninoIdReal)
    {
        $fechaNacimiento = $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento) : Carbon::now()->subDays(5);
        $hoy = Carbon::now();
        $edadDias = $fechaNacimiento->diffInDays($hoy);
        
        // Solo generar si el niño tiene 28 días o menos
        if ($edadDias > 28) {
            return null;
        }
        
        $seed = $ninoIdReal % 100;
        $fechaTamizaje = $fechaNacimiento->copy()->addDays(3 + ($seed % 3)); // Entre 3-5 días
        
        return (object)[
            'id' => null,
            'id_niño' => $ninoIdReal,
            'fecha_tamizaje' => $fechaTamizaje->format('Y-m-d'),
            'tipo_tamizaje' => ($seed % 2 == 0) ? 'Tamizaje Neonatal' : 'Tamizaje Galen',
            'resultado' => ($seed % 3 == 0) ? 'Normal' : (($seed % 3 == 1) ? 'Pendiente' : 'Anormal'),
            'estado' => ($seed % 3 == 0) ? 'cumple' : 'seguimiento',
            'es_ejemplo' => true,
        ];
    }
    
    public function registrarTamizaje(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nino_id' => 'required|integer',
            'fecha_tam_neo' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $nino = $this->findNino($request->nino_id);
            $ninoIdReal = $this->getNinoId($nino);
            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
            $fechaTamizaje = Carbon::parse($request->fecha_tam_neo);
            $edadDias = $fechaNacimiento->diffInDays($fechaTamizaje);
            
            $cumple = ($edadDias >= 1 && $edadDias <= 30) ? 'SI' : 'NO';

            $tamizaje = TamizajeNeonatal::updateOrCreate(
                ['id_niño' => $ninoIdReal],
                [
                    'fecha_tam_neo' => $request->fecha_tam_neo,
                    'edad_tam_neo' => $edadDias,
                    'cumple_tam_neo' => $cumple,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Tamizaje registrado exitosamente',
                'data' => $tamizaje
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar tamizaje: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function cnv(Request $request)
    {
        $ninoId = $request->query('nino_id');
        if ($ninoId) {
            // Buscar el niño primero para obtener el id correcto
            try {
                $nino = $this->findNino($ninoId);
                $ninoIdReal = $this->getNinoId($nino);
                $cnv = RecienNacido::where('id_niño', $ninoIdReal)->first();
            } catch (\Exception $e) {
                $cnv = null;
            }
            
            // Si no hay CNV real, generar datos de ejemplo
            if (!$cnv) {
                try {
                    $nino = $this->findNino($ninoId);
                    $ninoIdReal = $this->getNinoId($nino);
                    $cnv = $this->generarDatosEjemploCNV($nino, $ninoIdReal);
                } catch (\Exception $e) {
                    $cnv = null;
                }
            }
        } else {
            $cnv = RecienNacido::all();
        }
        return response()->json(['success' => true, 'data' => ['cnv' => $cnv]]);
    }
    
    /**
     * Generar datos de ejemplo para CNV
     */
    private function generarDatosEjemploCNV($nino, $ninoIdReal)
    {
        $seed = $ninoIdReal % 100;
        
        // Peso al nacer basado en el seed (entre 2.5 y 4.5 kg)
        $peso = round(2.5 + (($seed % 20) / 10), 2);
        
        // Edad gestacional (entre 36 y 40 semanas)
        $edadGestacional = 36 + ($seed % 5);
        
        // Clasificación: Normal o Bajo Peso al Nacer y/o Prematuro
        // Bajo peso: < 2.5 kg, Prematuro: < 37 semanas
        if ($peso < 2.5 || $edadGestacional < 37) {
            $clasificacion = 'Bajo Peso al Nacer y/o Prematuro';
        } else {
            $clasificacion = 'Normal';
        }
        
        return (object)[
            'id' => null,
            'id_niño' => $ninoIdReal,
            'peso' => $peso,
            'edad_gestacional' => $edadGestacional,
            'clasificacion' => $clasificacion,
            'es_ejemplo' => true,
        ];
    }
    
    public function registrarCNV(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nino_id' => 'required|integer',
            'peso_nacer' => 'required|numeric|min:0',
            'edad_gestacional' => 'required|numeric|min:20|max:45',
            'clasificacion' => 'required|string|in:Normal,Bajo Peso al Nacer y/o Prematuro',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: Todos los campos del CNV son requeridos (Peso al Nacer, Edad Gestacional, Clasificación)',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $nino = $this->findNino($request->nino_id);
            $ninoIdReal = $this->getNinoId($nino);
            
            $dataToUpdate = [
                'peso' => $request->peso_nacer,
                'edad_gestacional' => $request->edad_gestacional,
                'clasificacion' => $request->clasificacion,
            ];
            
            $cnv = RecienNacido::updateOrCreate(
                ['id_niño' => $ninoIdReal],
                $dataToUpdate
            );

            return response()->json([
                'success' => true,
                'message' => 'CNV registrado exitosamente',
                'data' => $cnv
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar CNV: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function visitas(Request $request)
    {
        $ninoId = $request->query('nino_id');
        if ($ninoId) {
            $visitas = VisitaDomiciliaria::where('id_niño', $ninoId)->orderBy('fecha_visita', 'desc')->get();
            
            // Si no hay visitas reales, generar datos de ejemplo
            if ($visitas->isEmpty()) {
                try {
                    $nino = $this->findNino($ninoId);
                    $ninoIdReal = $this->getNinoId($nino);
                    $visitas = $this->generarDatosEjemploVisitas($nino, $ninoIdReal);
                } catch (\Exception $e) {
                    $visitas = collect([]);
                }
            } else {
                // Formatear visitas para asegurar que tienen control_de_visita
                $visitas = $visitas->map(function($visita) {
                    return [
                        'id' => $visita->id_visita ?? $visita->id,
                        'id_niño' => $visita->id_niño,
                        'control_de_visita' => $visita->control_de_visita ?? $visita->numero_control ?? $visita->numero_visitas ?? 1,
                        'numero_control' => $visita->control_de_visita ?? $visita->numero_control ?? $visita->numero_visitas ?? 1,
                        'numero_visitas' => $visita->control_de_visita ?? $visita->numero_control ?? $visita->numero_visitas ?? 1,
                        'fecha_visita' => $visita->fecha_visita ? Carbon::parse($visita->fecha_visita)->format('Y-m-d') : null,
                    ];
                });
            }
        } else {
            $visitas = VisitaDomiciliaria::all();
        }
        return response()->json(['success' => true, 'data' => ['visitas' => $visitas]]);
    }
    
    /**
     * Generar datos de ejemplo para visitas domiciliarias
     */
    private function generarDatosEjemploVisitas($nino, $ninoIdReal)
    {
        $fechaNacimiento = $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento) : Carbon::now()->subDays(60);
        $hoy = Carbon::now();
        $edadDias = $fechaNacimiento->diffInDays($hoy);
        
        $visitasEjemplo = collect();
        // Controles de visita con sus rangos (1-4)
        $controlesVisita = [
            1 => ['dias' => 28, 'min' => 28, 'max' => 30, 'descripcion' => 'Visita domiciliaria Control 1 (28-30 días)'],
            2 => ['dias' => 105, 'min' => 60, 'max' => 150, 'descripcion' => 'Visita domiciliaria Control 2 (60-150 días)'],
            3 => ['dias' => 210, 'min' => 180, 'max' => 240, 'descripcion' => 'Visita domiciliaria Control 3 (180-240 días)'],
            4 => ['dias' => 300, 'min' => 270, 'max' => 330, 'descripcion' => 'Visita domiciliaria Control 4 (270-330 días)'],
        ];
        
        $seed = $ninoIdReal % 100;
        
        foreach ($controlesVisita as $controlNumero => $info) {
            if ($edadDias >= $info['min'] - 15) { // Generar si ya pasó o está cerca
                $diasEnRango = rand($info['min'], min($info['max'], $edadDias));
                $fechaVisita = $fechaNacimiento->copy()->addDays($diasEnRango + (($seed + $controlNumero) % 7) - 3);
                
                $visitasEjemplo->push([
                    'id' => null,
                    'id_niño' => $ninoIdReal,
                    'control_de_visita' => $controlNumero,
                    'numero_control' => $controlNumero,
                    'numero_visitas' => $controlNumero,
                    'fecha_visita' => $fechaVisita->format('Y-m-d'),
                    'estado' => ($edadDias >= $info['min'] && $edadDias <= $info['max']) ? 'cumple' : 'pendiente',
                    'es_ejemplo' => true,
                ]);
            }
        }
        
        return $visitasEjemplo;
    }
    
    public function registrarVisita(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nino_id' => 'required|integer',
            'fecha_visita' => 'required|date',
            'periodo' => 'required|string',
            'tipo_visita' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $nino = $this->findNino($request->nino_id);
            $ninoIdReal = $this->getNinoId($nino);
            
            // Contar visitas existentes para este niño
            $numeroVisitas = VisitaDomiciliaria::where('id_niño', $ninoIdReal)->count() + 1;

            $visita = VisitaDomiciliaria::create([
                'id_niño' => $ninoIdReal,
                'fecha_visita' => $request->fecha_visita,
                'numero_control' => $numeroVisitas,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Visita registrada exitosamente',
                'data' => $visita
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar visita: ' . $e->getMessage()
            ], 500);
        }
    }

    public function vacunas(Request $request)
    {
        $ninoId = $request->query('nino_id');
        if ($ninoId) {
            $vacunas = VacunaRn::where('id_niño', $ninoId)->first();
            
            // Si no hay vacunas reales, generar datos de ejemplo
            if (!$vacunas) {
                try {
                    $nino = $this->findNino($ninoId);
                    $ninoIdReal = $this->getNinoId($nino);
                    $vacunas = $this->generarDatosEjemploVacunas($nino, $ninoIdReal);
                } catch (\Exception $e) {
                    $vacunas = null;
                }
            }
            
            // Devolver como array para compatibilidad con el frontend
            return response()->json([
                'success' => true, 
                'data' => $vacunas ? [$vacunas] : []
            ]);
        } else {
            $vacunas = VacunaRn::all();
            return response()->json(['success' => true, 'data' => $vacunas]);
        }
    }
    
    /**
     * Generar datos de ejemplo para vacunas del recién nacido
     */
    private function generarDatosEjemploVacunas($nino, $ninoIdReal)
    {
        $fechaNacimiento = $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento) : Carbon::now()->subDays(5);
        $hoy = Carbon::now();
        $edadDias = $fechaNacimiento->diffInDays($hoy);
        
        $seed = $ninoIdReal % 100;
        
        // BCG: al nacer (0-7 días)
        $fechaBCG = $fechaNacimiento->copy()->addDays(1 + ($seed % 3)); // Entre 1-3 días
        $bcgAplicada = $edadDias >= 1;
        
        // HVB: al nacer (0-24 horas idealmente, pero hasta 7 días)
        $fechaHVB = $fechaNacimiento->copy()->addDays(($seed % 2)); // 0-1 días
        $hvbAplicada = $edadDias >= 0;
        
        return (object)[
            'id' => null,
            'id_niño' => $ninoIdReal,
            'bcg_fecha' => $bcgAplicada ? $fechaBCG->format('Y-m-d') : null,
            'bcg_aplicada' => $bcgAplicada ? 'SI' : 'NO',
            'hvb_fecha' => $hvbAplicada ? $fechaHVB->format('Y-m-d') : null,
            'hvb_aplicada' => $hvbAplicada ? 'SI' : 'NO',
            'es_ejemplo' => true,
        ];
    }
    
    public function registrarVacuna(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nino_id' => 'required|integer',
            'tipo_vacuna' => 'required|string|in:BCG,HVB',
            'fecha_aplicacion' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $nino = $this->findNino($request->nino_id);
            $ninoIdReal = $this->getNinoId($nino);
            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
            $fechaAplicacion = Carbon::parse($request->fecha_aplicacion);
            $edadDias = $fechaNacimiento->diffInDays($fechaAplicacion);
            
            // El estado se calcula dinámicamente: si la fecha existe y está en el rango 0-2 días = aplicada
            // No se almacena en BD, se calcula cuando se necesita
            
            $vacunaData = [];
            if ($request->tipo_vacuna === 'BCG') {
                $vacunaData['fecha_bcg'] = $request->fecha_aplicacion;
                // edad_bcg y estado_bcg se calculan dinámicamente - no se almacenan
            } else {
                $vacunaData['fecha_hvb'] = $request->fecha_aplicacion;
                // edad_hvb y estado_hvb se calculan dinámicamente - no se almacenan
            }
            
            $vacuna = VacunaRn::updateOrCreate(
                ['id_niño' => $ninoIdReal],
                $vacunaData
            );
            
            // cumple_BCG_HVB se calcula dinámicamente: ambas fechas existen y están en rango 0-2 días

            return response()->json([
                'success' => true,
                'message' => 'Vacuna registrada exitosamente',
                'data' => $vacuna
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar vacuna: ' . $e->getMessage()
            ], 500);
        }
    }


    public function totalAlertas()
    {
        // Usar la misma lógica que obtenerAlertas para asegurar consistencia
        // Esto garantiza que el total coincida con el número real de alertas mostradas
        $request = new \Illuminate\Http\Request();
        $response = $this->obtenerAlertas($request);
        $data = json_decode($response->getContent(), true);
        
        return response()->json([
            'success' => true,
            'total' => $data['total'] ?? 0
        ]);
    }

    /**
     * Obtener todas las alertas detalladas del sistema
     */
    public function obtenerAlertas(Request $request)
    {
        $hoy = Carbon::now();
        $alertas = [];
        
        // Obtener niños aplicando filtros según el rol del usuario
        $query = Nino::query();
        $query = $this->applyRedMicroredFilter($query, 'datosExtra');
        $ninos = $query->get();
        
        foreach ($ninos as $nino) {
            $ninoId = $this->getNinoId($nino);
            
            // ========== ALERTAS DE DATOS FALTANTES DEL NIÑO ==========
            $camposFaltantesNino = $this->detectarDatosFaltantesNino($nino);
            if (!empty($camposFaltantesNino)) {
                $alertas[] = [
                    'tipo' => 'datos_faltantes_nino',
                    'nino_id' => $ninoId,
                    'nino_nombre' => $nino->apellidos_nombres ?? 'Sin nombre',
                    'nino_dni' => $nino->numero_doc ?? 'Sin DNI',
                    'establecimiento' => $nino->establecimiento ?? 'No registrado',
                    'control' => 'Datos del Niño',
                    'edad_dias' => $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento)->diffInDays($hoy) : null,
                    'prioridad' => 'alta',
                    'fecha_nacimiento' => $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento)->format('Y-m-d') : null,
                    'mensaje' => 'Faltan datos del niño: ' . implode(', ', $camposFaltantesNino),
                    'campos_faltantes' => $camposFaltantesNino,
                ];
            }
            
            // ========== ALERTAS DE DATOS FALTANTES DE LA MADRE ==========
            $camposFaltantesMadre = $this->detectarDatosFaltantesMadre($nino);
            if (!empty($camposFaltantesMadre)) {
                $alertas[] = [
                    'tipo' => 'datos_faltantes_madre',
                    'nino_id' => $ninoId,
                    'nino_nombre' => $nino->apellidos_nombres ?? 'Sin nombre',
                    'nino_dni' => $nino->numero_doc ?? 'Sin DNI',
                    'establecimiento' => $nino->establecimiento ?? 'No registrado',
                    'control' => 'Datos de la Madre',
                    'edad_dias' => $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento)->diffInDays($hoy) : null,
                    'prioridad' => 'alta',
                    'fecha_nacimiento' => $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento)->format('Y-m-d') : null,
                    'mensaje' => 'Faltan datos de la madre: ' . implode(', ', $camposFaltantesMadre),
                    'campos_faltantes' => $camposFaltantesMadre,
                ];
            }
            
            // ========== ALERTAS DE DATOS FALTANTES EXTRAS ==========
            $camposFaltantesExtras = $this->detectarDatosFaltantesExtras($nino);
            if (!empty($camposFaltantesExtras)) {
                $alertas[] = [
                    'tipo' => 'datos_faltantes_extras',
                    'nino_id' => $ninoId,
                    'nino_nombre' => $nino->apellidos_nombres ?? 'Sin nombre',
                    'nino_dni' => $nino->numero_doc ?? 'Sin DNI',
                    'establecimiento' => $nino->establecimiento ?? 'No registrado',
                    'control' => 'Datos Extras',
                    'edad_dias' => $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento)->diffInDays($hoy) : null,
                    'prioridad' => 'media',
                    'fecha_nacimiento' => $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento)->format('Y-m-d') : null,
                    'mensaje' => 'Faltan datos extras: ' . implode(', ', $camposFaltantesExtras),
                    'campos_faltantes' => $camposFaltantesExtras,
                ];
            }
            
            // Continuar solo si tiene fecha de nacimiento para las demás alertas
            if (!$nino->fecha_nacimiento) continue;
            
            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
            $edadDias = $fechaNacimiento->diffInDays($hoy);
            
            // Alertas de controles recién nacido (0-28 días) - CONSOLIDADAS
            // Se generan alertas incluso si el niño tiene más de 28 días (alertas históricas)
            if ($edadDias >= 0) {
            $controlesRn = ControlRn::where('id_niño', $ninoId)->get();
            $controlesRegistrados = $controlesRn->pluck('numero_control')->toArray();
            $controlesRegistradosMap = [];
            foreach ($controlesRn as $control) {
                $controlesRegistradosMap[$control->numero_control] = $control;
            }
            
            $rangosRN = [
                1 => ['min' => 2, 'max' => 6, 'nombre' => 'Control 1'],
                2 => ['min' => 7, 'max' => 13, 'nombre' => 'Control 2'],
                3 => ['min' => 14, 'max' => 20, 'nombre' => 'Control 3'],
                4 => ['min' => 21, 'max' => 28, 'nombre' => 'Control 4']
            ];
            
            $controlesFaltantes = [];
            $controlesFueraRango = [];
            $controlesAnterioresFaltantes = [];
            
            // Encontrar el control más alto registrado con fecha
            $controlMasAltoConFecha = null;
            $numeroControlMasAlto = 0;
            foreach ($controlesRegistradosMap as $num => $control) {
                if ($control && $control->fecha && $num > $numeroControlMasAlto) {
                    $numeroControlMasAlto = $num;
                    $controlMasAltoConFecha = $control;
                }
            }
            
            // Si hay un control registrado con fecha, verificar controles anteriores faltantes
            if ($controlMasAltoConFecha) {
                for ($numAnterior = 1; $numAnterior < $numeroControlMasAlto; $numAnterior++) {
                    if (!isset($controlesRegistradosMap[$numAnterior]) || 
                        !$controlesRegistradosMap[$numAnterior] || 
                        !$controlesRegistradosMap[$numAnterior]->fecha) {
                        // Control anterior faltante
                        $rangoAnterior = $rangosRN[$numAnterior];
                        $fechaControlAlto = Carbon::parse($controlMasAltoConFecha->fecha);
                        $edadDiasControlAlto = $fechaNacimiento->diffInDays($fechaControlAlto);
                        
                        // Calcular días fuera del rango
                        $diasFuera = $edadDiasControlAlto > $rangoAnterior['max'] 
                            ? ($edadDiasControlAlto - $rangoAnterior['max']) 
                            : 0;
                        
                        $controlesAnterioresFaltantes[] = [
                            'nombre' => $rangoAnterior['nombre'],
                            'dias_fuera' => $diasFuera,
                            'rango' => $rangoAnterior,
                            'control_referencia' => $rangosRN[$numeroControlMasAlto]['nombre']
                        ];
                    }
                }
            }
            
            foreach ($rangosRN as $num => $rango) {
                $debeTener = false;
                    if ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) {
                    $debeTener = true;
                    } else if ($edadDias > $rango['max']) {
                    $debeTener = true;
                }
                    
                    if ($debeTener && !in_array($num, $controlesRegistrados)) {
                        // Verificar si ya fue detectado como control anterior faltante
                        $yaDetectado = false;
                        foreach ($controlesAnterioresFaltantes as $controlAnterior) {
                            if ($controlAnterior['nombre'] == $rango['nombre']) {
                                $yaDetectado = true;
                                break;
                            }
                        }
                        
                        if (!$yaDetectado) {
                            // Control faltante
                            $diasFuera = $edadDias > $rango['max'] ? ($edadDias - $rango['max']) : 0;
                            $controlesFaltantes[] = [
                                'nombre' => $rango['nombre'],
                                'dias_fuera' => $diasFuera,
                                'rango' => $rango
                            ];
                        }
                    } else if (in_array($num, $controlesRegistrados)) {
                        // Verificar si está fuera de rango
                        $control = $controlesRn->where('numero_control', $num)->first();
                        if ($control && $control->fecha) {
                            $fechaControl = Carbon::parse($control->fecha);
                            $edadDiasControl = $fechaNacimiento->diffInDays($fechaControl);
                            
                            if ($edadDiasControl < $rango['min'] || $edadDiasControl > $rango['max']) {
                                $diasFuera = $edadDiasControl > $rango['max'] ? ($edadDiasControl - $rango['max']) : ($rango['min'] - $edadDiasControl);
                                $controlesFueraRango[] = [
                                    'nombre' => $rango['nombre'],
                                    'dias_fuera' => $diasFuera,
                                    'edad_dias_control' => $edadDiasControl,
                                    'rango' => $rango
                                ];
                            }
                        }
                    }
            }
            
                // Consolidar todas las alertas de controles RN en una sola alerta
                // Prioridad: 1) Controles fuera de rango, 2) Controles anteriores faltantes, 3) Controles faltantes
                
                // 1. Alertas de controles fuera de rango (prioridad más alta - se muestra primero)
                if (!empty($controlesFueraRango)) {
                    $nombresFueraRango = array_column($controlesFueraRango, 'nombre');
                    $totalFueraRango = count($controlesFueraRango);
                    
                    // Mensaje simplificado: solo indicar qué controles están fuera de rango
                    $mensaje = $totalFueraRango === 1
                        ? "El control " . $nombresFueraRango[0] . " fue realizado fuera del rango permitido."
                        : "Los controles " . implode(', ', $nombresFueraRango) . " fueron realizados fuera del rango permitido.";
                    
                    $alertas[] = [
                        'tipo' => 'control_recien_nacido',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Controles RN',
                        'edad_dias' => $edadDias,
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'controles_fuera_rango' => $nombresFueraRango,
                        'total_controles_fuera_rango' => $totalFueraRango,
                    ];
                }
                // 2. Alertas de controles anteriores faltantes (solo si NO hay controles fuera de rango)
                else if (!empty($controlesAnterioresFaltantes)) {
                    $nombresFaltantes = array_column($controlesAnterioresFaltantes, 'nombre');
                    $totalFaltantes = count($controlesAnterioresFaltantes);
                    $controlReferencia = $controlesAnterioresFaltantes[0]['control_referencia'] ?? 'un control posterior';
                    
                    // Mensaje simplificado: solo indicar qué controles faltan
                    $mensaje = $totalFaltantes === 1
                        ? "El niño tiene el {$controlReferencia} registrado, pero falta el {$nombresFaltantes[0]} que debió realizarse antes."
                        : "El niño tiene el {$controlReferencia} registrado, pero faltan los controles " . implode(', ', $nombresFaltantes) . " que debieron realizarse antes.";
                    
                    $alertas[] = [
                        'tipo' => 'control_recien_nacido',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Controles RN',
                        'edad_dias' => $edadDias,
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'controles_faltantes' => $nombresFaltantes,
                        'total_controles_faltantes' => $totalFaltantes,
                        'control_referencia' => $controlReferencia,
                    ];
                    }
                // 3. Alertas de controles faltantes (solo si NO hay controles fuera de rango NI controles anteriores faltantes)
                else if (!empty($controlesFaltantes)) {
                        $nombresFaltantes = array_column($controlesFaltantes, 'nombre');
                    $totalFaltantes = count($controlesFaltantes);
                    
                    // Mensaje simplificado: solo indicar qué controles faltan
                    $mensaje = $totalFaltantes === 1
                        ? "El control " . $nombresFaltantes[0] . " debió realizarse."
                        : "Los controles " . implode(', ', $nombresFaltantes) . " debieron realizarse.";
                    
                    $alertas[] = [
                        'tipo' => 'control_recien_nacido',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Controles RN',
                        'edad_dias' => $edadDias,
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'controles_faltantes' => $nombresFaltantes,
                        'total_controles_faltantes' => $totalFaltantes,
                    ];
                }
                
                // 4. Alertas de datos faltantes en controles RN (controles registrados sin fecha)
                // Solo si NO hay alertas de controles faltantes o fuera de rango para evitar duplicación
                $controlesRnIncompletos = [];
                foreach ($controlesRn as $control) {
                    if (empty($control->fecha)) {
                        $controlesRnIncompletos[] = "Control {$control->numero_control}";
                    }
                }
                
                if (!empty($controlesRnIncompletos) && empty($controlesFaltantes) && empty($controlesFueraRango) && empty($controlesAnterioresFaltantes)) {
                    $controlesStr = implode(', ', $controlesRnIncompletos);
                    $total = count($controlesRnIncompletos);
                    $mensaje = $total === 1 
                        ? "El control RN {$controlesStr} está incompleto. Falta la fecha del control."
                        : "Los controles RN {$controlesStr} están incompletos. Faltan las fechas de los controles ({$total} control" . ($total > 1 ? 'es' : '') . ").";
                    
                    $alertas[] = [
                        'tipo' => 'control_recien_nacido_datos_faltantes',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Controles RN',
                        'edad_dias' => $edadDias,
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'controles_faltantes' => $controlesRnIncompletos,
                        'total_controles_faltantes' => $total,
                    ];
                }
            }
            
            // Alertas de CRED mensual (29 días o más) - CONSOLIDADAS
            // También verifica si tiene controles registrados aunque tenga más de 359 días
            if ($edadDias >= 29) {
                $controlesCred = ControlMenor1::where('id_niño', $ninoId)->get();
                $controlesRegistradosMap = [];
                foreach ($controlesCred as $control) {
                    $controlesRegistradosMap[$control->numero_control] = $control;
                }
                
                $rangosCred = [
                    1 => ['min' => 29, 'max' => 59, 'nombre' => 'Control 1'],
                    2 => ['min' => 60, 'max' => 89, 'nombre' => 'Control 2'],
                    3 => ['min' => 90, 'max' => 119, 'nombre' => 'Control 3'],
                    4 => ['min' => 120, 'max' => 149, 'nombre' => 'Control 4'],
                    5 => ['min' => 150, 'max' => 179, 'nombre' => 'Control 5'],
                    6 => ['min' => 180, 'max' => 209, 'nombre' => 'Control 6'],
                    7 => ['min' => 210, 'max' => 239, 'nombre' => 'Control 7'],
                    8 => ['min' => 240, 'max' => 269, 'nombre' => 'Control 8'],
                    9 => ['min' => 270, 'max' => 299, 'nombre' => 'Control 9'],
                    10 => ['min' => 300, 'max' => 329, 'nombre' => 'Control 10'],
                    11 => ['min' => 330, 'max' => 359, 'nombre' => 'Control 11']
                ];
                
                $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                $controlesFaltantes = [];
                $controlesFueraRango = [];
                $controlesAnterioresFaltantes = [];
                
                // Encontrar el control más alto registrado con fecha
                $controlMasAltoConFecha = null;
                $numeroControlMasAlto = 0;
                foreach ($controlesRegistradosMap as $num => $control) {
                    if ($control && $control->fecha && $num > $numeroControlMasAlto) {
                        $numeroControlMasAlto = $num;
                        $controlMasAltoConFecha = $control;
                    }
                }
                
                // Si hay un control registrado con fecha, verificar controles anteriores faltantes
                if ($controlMasAltoConFecha) {
                    for ($mesAnterior = 1; $mesAnterior < $numeroControlMasAlto; $mesAnterior++) {
                        if (!isset($controlesRegistradosMap[$mesAnterior]) || 
                            !$controlesRegistradosMap[$mesAnterior] || 
                            !$controlesRegistradosMap[$mesAnterior]->fecha) {
                            // Control anterior faltante
                            $rangoAnterior = $rangosCred[$mesAnterior];
                            $fechaControlAlto = Carbon::parse($controlMasAltoConFecha->fecha);
                            $edadDiasControlAlto = $fechaNacimiento->diffInDays($fechaControlAlto);
                            
                            // Calcular días fuera del rango
                            $diasFuera = $edadDiasControlAlto > $rangoAnterior['max'] 
                                ? ($edadDiasControlAlto - $rangoAnterior['max']) 
                                : 0;
                            
                            $controlesAnterioresFaltantes[] = [
                                'nombre' => $rangoAnterior['nombre'],
                                'dias_fuera' => $diasFuera,
                                'rango' => $rangoAnterior,
                                'control_referencia' => $rangosCred[$numeroControlMasAlto]['nombre']
                            ];
                        }
                    }
                }
                
                foreach ($rangosCred as $mes => $rango) {
                    $debeTener = false;
                    if ($edadDias > $rango['max']) {
                        $debeTener = true;
                    } else if ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) {
                        $debeTener = true;
                    }
                    
                    $control = isset($controlesRegistradosMap[$mes]) ? $controlesRegistradosMap[$mes] : null;
                    
                    if ($control && $control->fecha) {
                        // Verificar si el control está fuera de rango
                        $fechaControl = Carbon::parse($control->fecha);
                        $edadDiasControl = $fechaNacimiento->diffInDays($fechaControl);
                        
                        if ($edadDiasControl < $rango['min'] || $edadDiasControl > $rango['max']) {
                            $diasFuera = $edadDiasControl > $rango['max'] ? ($edadDiasControl - $rango['max']) : ($rango['min'] - $edadDiasControl);
                            $controlesFueraRango[] = [
                                'nombre' => $rango['nombre'],
                                'dias_fuera' => $diasFuera,
                                'edad_dias_control' => $edadDiasControl,
                                'rango' => $rango
                            ];
                        }
                    } else if ($debeTener && !$control) {
                        // Verificar si ya fue detectado como control anterior faltante
                        $yaDetectado = false;
                        foreach ($controlesAnterioresFaltantes as $controlAnterior) {
                            if ($controlAnterior['nombre'] == $rango['nombre']) {
                                $yaDetectado = true;
                                break;
                            }
                        }
                        
                        if (!$yaDetectado) {
                            // Control faltante
                            $diasFuera = $edadDias > $rango['max'] ? ($edadDias - $rango['max']) : 0;
                            $controlesFaltantes[] = [
                                'nombre' => $rango['nombre'],
                                'dias_fuera' => $diasFuera,
                                'rango' => $rango
                            ];
                        }
                    }
                }
                
                // Consolidar todas las alertas de controles CRED en una sola alerta por tipo
                // 1. Alertas de controles anteriores faltantes (solo si hay controles posteriores registrados)
                    if (!empty($controlesAnterioresFaltantes)) {
                    $nombresFaltantes = array_column($controlesAnterioresFaltantes, 'nombre');
                    $totalFaltantes = count($controlesAnterioresFaltantes);
                    $controlReferencia = $controlesAnterioresFaltantes[0]['control_referencia'] ?? 'un control posterior';
                    
                    // Mensaje simplificado: solo indicar qué controles faltan
                    $mensaje = $totalFaltantes === 1
                        ? "El niño tiene el {$controlReferencia} registrado, pero falta el {$nombresFaltantes[0]} que debió realizarse antes."
                        : "El niño tiene el {$controlReferencia} registrado, pero faltan los controles " . implode(', ', $nombresFaltantes) . " que debieron realizarse antes.";
                    
                    $alertas[] = [
                        'tipo' => 'control_cred_mensual',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Controles CRED',
                        'edad_dias' => $edadDias,
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'controles_faltantes' => $nombresFaltantes,
                        'total_controles_faltantes' => $totalFaltantes,
                        'control_referencia' => $controlReferencia,
                    ];
                    }
                    
                // 2. Alertas de controles faltantes (solo si NO hay alerta de controles anteriores faltantes para evitar duplicación)
                if (!empty($controlesFaltantes) && empty($controlesAnterioresFaltantes)) {
                        $nombresFaltantes = array_column($controlesFaltantes, 'nombre');
                    $totalFaltantes = count($controlesFaltantes);
                    
                    // Mensaje simplificado: solo indicar qué controles faltan
                    $mensaje = $totalFaltantes === 1
                        ? "El control CRED " . $nombresFaltantes[0] . " debió realizarse."
                        : "Los controles CRED " . implode(', ', $nombresFaltantes) . " debieron realizarse.";
                    
                    $alertas[] = [
                        'tipo' => 'control_cred_mensual',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Controles CRED',
                        'edad_dias' => $edadDias,
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'controles_faltantes' => $nombresFaltantes,
                        'total_controles_faltantes' => $totalFaltantes,
                    ];
                    }
                    
                // 3. Alertas de controles fuera de rango (siempre se genera si hay controles fuera de rango)
                    if (!empty($controlesFueraRango)) {
                        $nombresFueraRango = array_column($controlesFueraRango, 'nombre');
                    $totalFueraRango = count($controlesFueraRango);
                    
                    // Mensaje simplificado: solo indicar qué controles están fuera de rango, sin mencionar días de diferencia
                    $mensaje = $totalFueraRango === 1
                        ? "El control CRED " . $nombresFueraRango[0] . " fue realizado fuera del rango permitido."
                        : "Los controles CRED " . implode(', ', $nombresFueraRango) . " fueron realizados fuera del rango permitido.";
                    
                    $alertas[] = [
                        'tipo' => 'control_cred_mensual_fuera_rango',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Controles CRED',
                        'edad_dias' => $edadDias,
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'controles_fuera_rango' => $nombresFueraRango,
                        'total_controles_fuera_rango' => $totalFueraRango,
                    ];
                }
                
                // 4. Alertas de datos faltantes en controles CRED (controles registrados sin fecha)
                // Solo si NO hay alertas de controles faltantes o fuera de rango para evitar duplicación
                $controlesCredIncompletos = [];
                foreach ($controlesCred as $control) {
                    if (empty($control->fecha)) {
                        $controlesCredIncompletos[] = "Control {$control->numero_control}";
                    }
                }
                
                if (!empty($controlesCredIncompletos) && empty($controlesFaltantes) && empty($controlesFueraRango) && empty($controlesAnterioresFaltantes)) {
                    $controlesStr = implode(', ', $controlesCredIncompletos);
                    $total = count($controlesCredIncompletos);
                    $mensaje = $total === 1 
                        ? "El control CRED {$controlesStr} está incompleto. Falta la fecha del control."
                        : "Los controles CRED {$controlesStr} están incompletos. Faltan las fechas de los controles ({$total} control" . ($total > 1 ? 'es' : '') . ").";
                    
                    $alertas[] = [
                        'tipo' => 'control_cred_datos_faltantes',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Controles CRED',
                        'edad_dias' => $edadDias,
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'controles_faltantes' => $controlesCredIncompletos,
                        'total_controles_faltantes' => $total,
                    ];
                }
            }
            
            // Alertas de CNV (Carné de Nacido Vivo) - TODOS LOS DATOS SON REQUERIDOS
            $cnv = RecienNacido::where('id_niño', $ninoId)->first();
            if (!$cnv || empty($cnv->peso) || empty($cnv->edad_gestacional) || empty($cnv->clasificacion)) {
                $camposFaltantes = [];
                if (!$cnv || empty($cnv->peso)) $camposFaltantes[] = 'Peso al Nacer';
                if (!$cnv || empty($cnv->edad_gestacional)) $camposFaltantes[] = 'Edad Gestacional';
                if (!$cnv || empty($cnv->clasificacion)) $camposFaltantes[] = 'Clasificación';
                
                $mensaje = "El CNV (Carné de Nacido Vivo) está incompleto. Faltan los siguientes datos: " . implode(', ', $camposFaltantes);
                
                $alertas[] = [
                    'tipo' => 'cnv',
                    'nino_id' => $ninoId,
                    'nino_nombre' => $nino->apellidos_nombres,
                    'nino_dni' => $nino->numero_doc,
                    'establecimiento' => $nino->establecimiento,
                    'control' => 'CNV (Carné de Nacido Vivo)',
                    'edad_dias' => $edadDias,
                    'prioridad' => 'alta',
                    'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                    'mensaje' => $mensaje,
                    'campos_faltantes' => $camposFaltantes,
                ];
            }
            
            // Alertas de visitas domiciliarias - CONSOLIDADAS
            if ($edadDias >= 28) {
                $visitas = VisitaDomiciliaria::where('id_niño', $ninoId)->get();
                $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                
                $rangosVisitas = [
                    1 => ['min' => 28, 'max' => 30, 'nombre' => 'Visita 1'],
                    2 => ['min' => 60, 'max' => 150, 'nombre' => 'Visita 2'],
                    3 => ['min' => 180, 'max' => 240, 'nombre' => 'Visita 3'],
                    4 => ['min' => 270, 'max' => 330, 'nombre' => 'Visita 4']
                ];
                
                $visitasCumplen = 0;
                $visitasFaltantes = [];
                $visitasFueraRango = [];
                $visitasIncompletas = [];
                
                // Primero verificar visitas incompletas (sin fecha o sin control_de_visita)
                foreach ($visitas as $visita) {
                    $camposFaltantes = [];
                    if (empty($visita->fecha_visita)) {
                        $camposFaltantes[] = 'Fecha de Visita';
                    }
                    if (empty($visita->control_de_visita)) {
                        $camposFaltantes[] = 'Control de Visita';
                    }
                    
                    if (!empty($camposFaltantes)) {
                        $controlNum = $visita->control_de_visita ?? 'N/A';
                        $visitasIncompletas[] = [
                            'numero' => $controlNum,
                            'campos' => $camposFaltantes
                        ];
                    }
                }
                
                // Si hay visitas incompletas, generar alerta
                if (!empty($visitasIncompletas)) {
                    $visitasStr = implode(', ', array_map(function($v) {
                        return "Visita " . ($v['numero'] !== 'N/A' ? $v['numero'] : 'sin número');
                    }, $visitasIncompletas));
                    $total = count($visitasIncompletas);
                    $camposStr = implode(', ', array_unique(array_merge(...array_column($visitasIncompletas, 'campos'))));
                    
                    $mensaje = $total === 1 
                        ? "La visita domiciliaria {$visitasStr} está incompleta. Faltan los siguientes datos: {$camposStr}."
                        : "Las visitas domiciliarias {$visitasStr} están incompletas. Faltan los siguientes datos: {$camposStr} ({$total} visita" . ($total > 1 ? 's' : '') . ").";
                    
                    $alertas[] = [
                        'tipo' => 'visita_datos_faltantes',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Visitas Domiciliarias',
                        'edad_dias' => $edadDias,
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'visitas_incompletas' => $visitasIncompletas,
                        'campos_faltantes' => array_unique(array_merge(...array_column($visitasIncompletas, 'campos'))),
                    ];
                }
                
                // Verificar visitas cumplidas, faltantes y fuera de rango
                foreach ($rangosVisitas as $controlNum => $rango) {
                    $visitasControl = $visitas->where('control_de_visita', $controlNum)->filter(function($v) {
                        return !empty($v->fecha_visita);
                    });
                    $tieneVisitaCumplida = false;
                    $tieneVisitaFueraRango = false;
                    
                    foreach ($visitasControl as $visita) {
                    if ($visita->fecha_visita) {
                        $fechaVisita = Carbon::parse($visita->fecha_visita);
                        $edadDiasVisita = $fechaNacimiento->diffInDays($fechaVisita);
                        
                            if ($edadDiasVisita >= $rango['min'] && $edadDiasVisita <= $rango['max']) {
                                $tieneVisitaCumplida = true;
                                $visitasCumplen++;
                                break;
                            } elseif ($edadDiasVisita > $rango['max']) {
                                // Visita fuera de rango (tarde)
                                $tieneVisitaFueraRango = true;
                                $diasFuera = $edadDiasVisita - $rango['max'];
                                // Verificar si ya existe en el array
                                $yaExiste = false;
                                foreach ($visitasFueraRango as $vfr) {
                                    if ($vfr['nombre'] === $rango['nombre']) {
                                        $yaExiste = true;
                                        break;
                                    }
                                }
                                if (!$yaExiste) {
                                $visitasFueraRango[] = [
                                    'nombre' => $rango['nombre'],
                                    'dias_fuera' => $diasFuera,
                                    'edad_dias_visita' => $edadDiasVisita,
                                    'rango' => $rango
                                ];
                            }
                        }
                    }
                }
                
                    // Si no tiene visita cumplida ni visita fuera de rango, y ya pasó el rango, agregar a faltantes
                    if (!$tieneVisitaCumplida && !$tieneVisitaFueraRango && $edadDias > $rango['max']) {
                        $diasFuera = $edadDias - $rango['max'];
                        // Verificar si ya existe en el array
                        $yaExiste = false;
                        foreach ($visitasFaltantes as $vf) {
                            if ($vf['nombre'] === $rango['nombre']) {
                                $yaExiste = true;
                                    break;
                                }
                            }
                        if (!$yaExiste) {
                            $visitasFaltantes[] = [
                                'nombre' => $rango['nombre'],
                                'dias_fuera' => $diasFuera,
                                'rango' => $rango
                            ];
                        }
                    }
                }
                
                // Crear alerta consolidada para visitas faltantes (SOLO si no hay alerta de datos faltantes)
                if (!empty($visitasFaltantes) && empty($visitasIncompletas)) {
                        $nombresFaltantes = array_column($visitasFaltantes, 'nombre');
                    $totalFaltantes = count($visitasFaltantes);
                        $maxDiasFuera = max(array_column($visitasFaltantes, 'dias_fuera'));
                    
                    $mensaje = $totalFaltantes === 1
                        ? "La visita " . $nombresFaltantes[0] . " debió realizarse. Ya pasaron {$maxDiasFuera} día(s) del límite máximo."
                        : "Las visitas " . implode(', ', $nombresFaltantes) . " debieron realizarse. Ya pasaron hasta {$maxDiasFuera} día(s) del límite máximo ({$totalFaltantes} visita" . ($totalFaltantes > 1 ? 's' : '') . " faltantes).";
                    
                    $alertas[] = [
                        'tipo' => 'visita',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Visitas Domiciliarias',
                        'edad_dias' => $edadDias,
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'visitas_faltantes' => $nombresFaltantes,
                        'total_visitas_faltantes' => $totalFaltantes,
                        'max_dias_fuera' => $maxDiasFuera,
                    ];
                }
                
                // Crear alerta consolidada para visitas fuera de rango (SOLO si no hay alerta de datos faltantes)
                if (!empty($visitasFueraRango) && empty($visitasIncompletas)) {
                    $nombresFueraRango = array_column($visitasFueraRango, 'nombre');
                    $totalFueraRango = count($visitasFueraRango);
                    
                    // Mensaje simplificado: solo indicar qué visitas están fuera de rango
                    $mensaje = $totalFueraRango === 1
                        ? "La visita " . $nombresFueraRango[0] . " fue realizada fuera del rango permitido."
                        : "Las visitas " . implode(', ', $nombresFueraRango) . " fueron realizadas fuera del rango permitido.";
                    
                    $alertas[] = [
                        'tipo' => 'visita_fuera_rango',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Visitas Domiciliarias',
                        'edad_dias' => $edadDias,
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'visitas_fuera_rango' => $nombresFueraRango,
                        'total_visitas_fuera_rango' => $totalFueraRango,
                    ];
                }
                
                // Si tiene menos de 2 visitas cumplidas y ya debería tenerlas, generar alerta general
                // SOLO si NO hay alertas específicas (faltantes, fuera de rango, o datos faltantes) para evitar duplicación
                if ($visitasCumplen < 2 && empty($visitasFaltantes) && empty($visitasFueraRango) && empty($visitasIncompletas)) {
                    $visitasEsperadas = 0;
                    if ($edadDias >= 28) $visitasEsperadas++; // Visita 1
                    if ($edadDias >= 150) $visitasEsperadas++; // Visita 2
                    if ($edadDias >= 240) $visitasEsperadas++; // Visita 3
                    if ($edadDias >= 330) $visitasEsperadas++; // Visita 4
                    
                    if ($visitasEsperadas >= 2 && $visitasCumplen < 2) {
                        $faltan = 2 - $visitasCumplen;
                        $alertas[] = [
                            'tipo' => 'visita_general',
                            'nino_id' => $ninoId,
                            'nino_nombre' => $nino->apellidos_nombres,
                            'nino_dni' => $nino->numero_doc,
                            'establecimiento' => $nino->establecimiento,
                            'control' => 'Visitas Domiciliarias',
                            'edad_dias' => $edadDias,
                        'visitas_cumplen' => $visitasCumplen,
                        'visitas_requeridas' => 2,
                            'prioridad' => 'alta',
                            'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                            'mensaje' => "El niño tiene {$edadDias} días y debe tener mínimo 2 visitas domiciliarias cumplidas. Actualmente tiene {$visitasCumplen} visita(s) cumplida(s). Faltan {$faltan} visita(s).",
                    ];
                    }
                }
            }
            
            // Alertas de tamizaje - SOLO TAMIZAJE NEONATAL ES REQUERIDO
            // Verificar para todos los niños que no tengan tamizaje registrado o esté fuera de rango
            $tamizaje = TamizajeNeonatal::where('id_niño', $ninoId)->first();
            
            if (!$tamizaje || !$tamizaje->fecha_tam_neo) {
                // No tiene tamizaje registrado
                // Mensaje simplificado: solo indicar que falta el tamizaje
                $mensaje = "El tamizaje neonatal no está registrado. Debe realizarse entre los 0 y 29 días de vida.";
                
                $alertas[] = [
                    'tipo' => 'tamizaje',
                    'nino_id' => $ninoId,
                    'nino_nombre' => $nino->apellidos_nombres,
                    'nino_dni' => $nino->numero_doc,
                    'establecimiento' => $nino->establecimiento,
                    'control' => 'Tamizaje Neonatal',
                    'edad_dias' => $edadDias,
                    'rango_min' => 0,
                    'rango_max' => 29,
                    'rango_dias' => '0-29',
                    'prioridad' => $edadDias > 29 ? 'alta' : 'media',
                    'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                    'mensaje' => $mensaje,
                ];
                } else {
                // Tiene tamizaje registrado, verificar si está fuera de rango
                $fechaTamizaje = Carbon::parse($tamizaje->fecha_tam_neo);
                $edadTamizaje = $fechaNacimiento->diffInDays($fechaTamizaje);
                
                if ($edadTamizaje < 0 || $edadTamizaje > 29) {
                    // Tamizaje fuera de rango
                    // Mensaje simplificado: solo indicar que está fuera de rango
                    $mensaje = "El tamizaje neonatal fue realizado fuera del rango permitido (0-29 días).";
                
                $alertas[] = [
                        'tipo' => 'tamizaje_fuera_rango',
                    'nino_id' => $ninoId,
                    'nino_nombre' => $nino->apellidos_nombres,
                    'nino_dni' => $nino->numero_doc,
                    'establecimiento' => $nino->establecimiento,
                    'control' => 'Tamizaje Neonatal',
                    'edad_dias' => $edadDias,
                    'rango_min' => 0,
                    'rango_max' => 29,
                    'rango_dias' => '0-29',
                        'prioridad' => 'alta',
                    'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                    'mensaje' => $mensaje,
                        'edad_tamizaje' => $edadTamizaje,
                ];
                }
            }
            
            // Alertas de vacunas (0-2 días) - AMBAS VACUNAS SON REQUERIDAS
            // Se generan alertas incluso si el niño tiene más de 30 días (alertas históricas)
            // Consolidar BCG y HVB en una sola alerta
            if ($edadDias >= 0) {
            $vacunas = VacunaRn::where('id_niño', $ninoId)->first();
            // Verificar que tenga AMBAS vacunas (BCG y HVB) - estado se calcula dinámicamente
            $tieneBCG = false;
            $tieneHVB = false;
                $problemasVacunas = [];
            
            if ($vacunas && $vacunas->fecha_bcg) {
                $fechaBCG = Carbon::parse($vacunas->fecha_bcg);
                $edadBCG = $fechaNacimiento->diffInDays($fechaBCG);
                $tieneBCG = ($edadBCG >= 0 && $edadBCG <= 2); // Rango 0-2 días
                
                if (!$tieneBCG) {
                        $problemasVacunas[] = [
                        'nombre' => 'BCG',
                            'edad_aplicacion' => $edadBCG,
                            'tipo' => 'fuera_rango'
                    ];
                }
            } else {
                    $problemasVacunas[] = [
                        'nombre' => 'BCG',
                        'tipo' => 'faltante'
                    ];
            }
            
            if ($vacunas && $vacunas->fecha_hvb) {
                $fechaHVB = Carbon::parse($vacunas->fecha_hvb);
                $edadHVB = $fechaNacimiento->diffInDays($fechaHVB);
                $tieneHVB = ($edadHVB >= 0 && $edadHVB <= 2); // Rango 0-2 días
                
                if (!$tieneHVB) {
                        $problemasVacunas[] = [
                        'nombre' => 'HVB',
                            'edad_aplicacion' => $edadHVB,
                            'tipo' => 'fuera_rango'
                    ];
                }
            } else {
                    $problemasVacunas[] = [
                        'nombre' => 'HVB',
                        'tipo' => 'faltante'
                    ];
            }
            
                // Generar una sola alerta consolidada si hay problemas con alguna vacuna
                if (!empty($problemasVacunas)) {
                    $vacunasFaltantes = [];
                    $vacunasFueraRango = [];
                    
                    foreach ($problemasVacunas as $problema) {
                        if ($problema['tipo'] === 'faltante') {
                            $vacunasFaltantes[] = $problema['nombre'];
                        } else {
                            $vacunasFueraRango[] = [
                                'nombre' => $problema['nombre'],
                                'edad' => $problema['edad_aplicacion']
                            ];
                    }
                    }
                    
                    $mensaje = '';
                        $diasFuera = $edadDias > 2 ? ($edadDias - 2) : 0;
                    
                    // Construir mensaje consolidado simplificado
                    $partesMensaje = [];
                    
                    if (!empty($vacunasFaltantes)) {
                        $vacunasFaltantesStr = implode(' y ', $vacunasFaltantes);
                        if (count($vacunasFaltantes) === 1) {
                            $partesMensaje[] = "la vacuna {$vacunasFaltantesStr} no está registrada";
                        } else {
                            $partesMensaje[] = "las vacunas {$vacunasFaltantesStr} no están registradas";
                        }
                    }
                    
                    if (!empty($vacunasFueraRango)) {
                        $vacunasFueraRangoNombres = array_column($vacunasFueraRango, 'nombre');
                        $vacunasFueraRangoStr = implode(' y ', $vacunasFueraRangoNombres);
                        if (count($vacunasFueraRango) === 1) {
                            $partesMensaje[] = "la vacuna {$vacunasFueraRangoStr} fue aplicada fuera del rango permitido (0-2 días)";
                        } else {
                            $partesMensaje[] = "las vacunas {$vacunasFueraRangoStr} fueron aplicadas fuera del rango permitido (0-2 días)";
                    }
                    }
                    
                    // Mensaje simplificado: solo indicar qué vacunas están mal, sin mencionar días de diferencia
                    $mensaje = implode(', ', $partesMensaje) . ". Deben aplicarse entre los 0 y 2 días de vida.";
                    
                    // Determinar prioridad basada en si alguna vacuna está fuera de rango o faltante
                    $prioridad = 'media';
                    if ($edadDias > 2 || !empty($vacunasFueraRango)) {
                        $prioridad = 'alta';
                    }
                    
                    $alertas[] = [
                        'tipo' => 'vacuna',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Vacunas RN (BCG y HVB)',
                        'edad_dias' => $edadDias,
                        'rango_min' => 0,
                        'rango_max' => 2,
                        'rango_dias' => '0-2',
                        'prioridad' => $prioridad,
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'dias_fuera' => $diasFuera,
                        'vacunas_faltantes' => $vacunasFaltantes,
                        'vacunas_fuera_rango' => array_column($vacunasFueraRango, 'nombre'),
                    ];
                }
            }
        }
        
        // Eliminar alertas duplicadas basándose en tipo, nino_id y control
        // Usar tipo, nino_id y control para identificar duplicados
        // Esto asegura que solo haya una alerta por tipo de problema por niño por control
        $alertasUnicas = [];
        $alertasVistas = [];
        
        foreach ($alertas as $alerta) {
            // Crear una clave única para identificar duplicados
            // Normalizar valores para evitar problemas con espacios o mayúsculas/minúsculas
            $tipo = strtolower(trim($alerta['tipo'] ?? ''));
            $ninoId = (string)($alerta['nino_id'] ?? '');
            $control = strtolower(trim($alerta['control'] ?? ''));
            
            // Usar clave directa sin md5 para mejor debugging
            $clave = $tipo . '|' . $ninoId . '|' . $control;
            
            // Solo agregar si no hemos visto esta alerta antes
            // Si ya existe una alerta del mismo tipo para el mismo niño y control, mantener la primera
            if (!isset($alertasVistas[$clave])) {
                $alertasVistas[$clave] = true;
                $alertasUnicas[] = $alerta;
            }
        }
        
        // Ordenar por prioridad (alta primero) y luego por edad
        usort($alertasUnicas, function($a, $b) {
            if ($a['prioridad'] === $b['prioridad']) {
                return $b['edad_dias'] - $a['edad_dias'];
            }
            return $a['prioridad'] === 'alta' ? -1 : 1;
        });
        
        return response()->json([
            'success' => true,
            'data' => $alertasUnicas,
            'total' => count($alertasUnicas)
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
    
    /**
     * Detectar datos faltantes del niño
     */
    private function detectarDatosFaltantesNino(Nino $nino): array
    {
        $camposFaltantes = [];
        
        if (empty($nino->tipo_doc)) $camposFaltantes[] = 'Tipo de Documento';
        if (empty($nino->numero_doc)) $camposFaltantes[] = 'Número de Documento';
        if (empty($nino->apellidos_nombres)) $camposFaltantes[] = 'Apellidos y Nombres';
        if (empty($nino->fecha_nacimiento)) $camposFaltantes[] = 'Fecha de Nacimiento';
        if (empty($nino->genero)) $camposFaltantes[] = 'Género';
        if (empty($nino->establecimiento)) $camposFaltantes[] = 'Establecimiento';
        
        return $camposFaltantes;
    }
    
    /**
     * Detectar datos faltantes de la madre
     */
    private function detectarDatosFaltantesMadre(Nino $nino): array
    {
        $camposFaltantes = [];
        
        // Obtener la madre del niño
        $madre = null;
        if ($nino->id_madre) {
            $madre = Madre::find($nino->id_madre);
        }
        if (!$madre) {
            $madre = Madre::where('id_niño', $nino->id)->first();
        }
        
        if (!$madre) {
            $camposFaltantes[] = 'Registro de Madre completo';
        } else {
            if (empty($madre->dni)) $camposFaltantes[] = 'DNI de la Madre';
            if (empty($madre->apellidos_nombres)) $camposFaltantes[] = 'Apellidos y Nombres de la Madre';
            if (empty($madre->celular)) $camposFaltantes[] = 'Celular de la Madre';
            if (empty($madre->domicilio)) $camposFaltantes[] = 'Domicilio de la Madre';
            if (empty($madre->referencia_direccion)) $camposFaltantes[] = 'Referencia de Dirección';
        }
        
        return $camposFaltantes;
    }
    
    /**
     * Detectar datos faltantes en datos extras
     */
    private function detectarDatosFaltantesExtras(Nino $nino): array
    {
        $camposFaltantes = [];
        
        $datosExtra = DatosExtra::where('id_niño', $nino->id)->first();
        
        if (!$datosExtra) {
            $camposFaltantes[] = 'Registro de Datos Extras completo';
        } else {
            if (empty($datosExtra->red)) $camposFaltantes[] = 'Red';
            if (empty($datosExtra->microred)) $camposFaltantes[] = 'Microred';
            if (empty($datosExtra->eess_nacimiento)) $camposFaltantes[] = 'EESS de Nacimiento';
            if (empty($datosExtra->distrito)) $camposFaltantes[] = 'Distrito';
            if (empty($datosExtra->provincia)) $camposFaltantes[] = 'Provincia';
            if (empty($datosExtra->departamento)) $camposFaltantes[] = 'Departamento';
            if (empty($datosExtra->seguro)) $camposFaltantes[] = 'Seguro';
            if (empty($datosExtra->programa)) $camposFaltantes[] = 'Programa';
        }
        
        return $camposFaltantes;
    }
    
    /**
     * Detectar datos faltantes en controles RN
     */
    private function detectarDatosFaltantesControlesRn(Nino $nino, int $ninoId, int $edadDias): array
    {
        $alertas = [];
        
        if ($edadDias <= 28) {
            $controlesRn = ControlRn::where('id_niño', $ninoId)->get();
            
            $controlesIncompletos = [];
            foreach ($controlesRn as $control) {
                if (empty($control->fecha)) {
                    $controlesIncompletos[] = "Control {$control->numero_control}";
                }
            }
            
            if (!empty($controlesIncompletos)) {
                $controlesStr = implode(', ', $controlesIncompletos);
                $total = count($controlesIncompletos);
                $mensaje = $total === 1 
                    ? "El control {$controlesStr} está incompleto. Falta la fecha del control."
                    : "Los controles {$controlesStr} están incompletos. Faltan las fechas de los controles ({$total} control" . ($total > 1 ? 'es' : '') . ").";
                
                $alertas[] = [
                    'tipo' => 'control_recien_nacido_datos_faltantes',
                    'nino_id' => $ninoId,
                    'nino_nombre' => $nino->apellidos_nombres,
                    'nino_dni' => $nino->numero_doc,
                    'establecimiento' => $nino->establecimiento,
                    'control' => 'Controles RN',
                    'edad_dias' => $edadDias,
                    'prioridad' => 'alta',
                    'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                    'mensaje' => $mensaje,
                    'controles_faltantes' => $controlesIncompletos,
                    'total_controles_faltantes' => $total,
                ];
            }
        }
        
        return $alertas;
    }
    
    /**
     * Detectar datos faltantes en controles CRED
     */
    private function detectarDatosFaltantesControlesCred(Nino $nino, int $ninoId, int $edadDias): array
    {
        $alertas = [];
        
        if ($edadDias >= 29 && $edadDias <= 359) {
            $controlesCred = ControlMenor1::where('id_niño', $ninoId)->get();
            
            $controlesIncompletos = [];
            foreach ($controlesCred as $control) {
                if (empty($control->fecha)) {
                    $controlesIncompletos[] = "Control {$control->numero_control}";
                }
            }
            
            if (!empty($controlesIncompletos)) {
                $controlesStr = implode(', ', $controlesIncompletos);
                $total = count($controlesIncompletos);
                $mensaje = $total === 1 
                    ? "El control CRED {$controlesStr} está incompleto. Falta la fecha del control."
                    : "Los controles CRED {$controlesStr} están incompletos. Faltan las fechas de los controles ({$total} control" . ($total > 1 ? 'es' : '') . ").";
                
                $alertas[] = [
                    'tipo' => 'control_cred_datos_faltantes',
                    'nino_id' => $ninoId,
                    'nino_nombre' => $nino->apellidos_nombres,
                    'nino_dni' => $nino->numero_doc,
                    'establecimiento' => $nino->establecimiento,
                    'control' => 'Controles CRED',
                    'edad_dias' => $edadDias,
                    'prioridad' => 'alta',
                    'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                    'mensaje' => $mensaje,
                    'controles_faltantes' => $controlesIncompletos,
                    'total_controles_faltantes' => $total,
                ];
            }
        }
        
        return $alertas;
    }
    
    /**
     * Detectar datos faltantes en tamizaje neonatal
     */
    private function detectarDatosFaltantesTamizaje(Nino $nino, int $ninoId, int $edadDias): array
    {
        $alertas = [];
        
        if ($edadDias >= 0 && $edadDias <= 29) {
            $tamizaje = TamizajeNeonatal::where('id_niño', $ninoId)->first();
            
            if ($tamizaje) {
                $camposFaltantes = [];
                
                if (empty($tamizaje->fecha_tam_neo)) {
                    $camposFaltantes[] = 'Fecha de Tamizaje Neonatal';
                }
                
                if (!empty($camposFaltantes)) {
                    $alertas[] = [
                        'tipo' => 'tamizaje_datos_faltantes',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Tamizaje Neonatal',
                        'edad_dias' => $edadDias,
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => 'El tamizaje neonatal está incompleto. Faltan los siguientes datos: ' . implode(', ', $camposFaltantes),
                        'campos_faltantes' => $camposFaltantes,
                    ];
                }
            }
        }
        
        return $alertas;
    }
    
    /**
     * Detectar datos faltantes en vacunas RN
     */
    private function detectarDatosFaltantesVacunas(Nino $nino, int $ninoId, int $edadDias): array
    {
        $alertas = [];
        
        if ($edadDias >= 0 && $edadDias <= 2) {
            $vacunas = VacunaRn::where('id_niño', $ninoId)->first();
            
            if ($vacunas) {
                $camposFaltantes = [];
                
                if (empty($vacunas->fecha_bcg)) {
                    $camposFaltantes[] = 'Fecha de Vacuna BCG';
                }
                if (empty($vacunas->fecha_hvb)) {
                    $camposFaltantes[] = 'Fecha de Vacuna HVB';
                }
                
                if (!empty($camposFaltantes)) {
                    $alertas[] = [
                        'tipo' => 'vacuna_datos_faltantes',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Vacunas RN',
                        'edad_dias' => $edadDias,
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => 'Las vacunas RN están incompletas. Faltan los siguientes datos: ' . implode(', ', $camposFaltantes),
                        'campos_faltantes' => $camposFaltantes,
                    ];
                }
            }
        }
        
        return $alertas;
    }
    
    /**
     * Detectar datos faltantes en visitas domiciliarias
     */
    private function detectarDatosFaltantesVisitas(Nino $nino, int $ninoId, int $edadDias): array
    {
        $alertas = [];
        
        if ($edadDias >= 28) {
            $visitas = VisitaDomiciliaria::where('id_niño', $ninoId)->get();
            
            $visitasIncompletas = [];
            $camposFaltantes = [];
            
            foreach ($visitas as $visita) {
                $faltantesVisita = [];
                
                if (empty($visita->fecha_visita)) {
                    $faltantesVisita[] = 'Fecha de Visita';
                }
                if (empty($visita->control_de_visita)) {
                    $faltantesVisita[] = 'Control de Visita';
                }
                
                if (!empty($faltantesVisita)) {
                    $controlNum = $visita->control_de_visita ?? 'N/A';
                    $visitasIncompletas[] = "Visita {$controlNum}";
                    $camposFaltantes = array_unique(array_merge($camposFaltantes, $faltantesVisita));
                }
            }
            
            if (!empty($visitasIncompletas)) {
                $visitasStr = implode(', ', $visitasIncompletas);
                $total = count($visitasIncompletas);
                $camposStr = implode(', ', $camposFaltantes);
                
                $mensaje = $total === 1 
                    ? "La visita domiciliaria {$visitasStr} está incompleta. Faltan los siguientes datos: {$camposStr}."
                    : "Las visitas domiciliarias {$visitasStr} están incompletas. Faltan los siguientes datos: {$camposStr} ({$total} visita" . ($total > 1 ? 's' : '') . ").";
                
                $alertas[] = [
                    'tipo' => 'visita_datos_faltantes',
                    'nino_id' => $ninoId,
                    'nino_nombre' => $nino->apellidos_nombres,
                    'nino_dni' => $nino->numero_doc,
                    'establecimiento' => $nino->establecimiento,
                    'control' => 'Visitas Domiciliarias',
                    'edad_dias' => $edadDias,
                    'prioridad' => 'alta',
                    'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                    'mensaje' => $mensaje,
                    'visitas_faltantes' => $visitasIncompletas,
                    'campos_faltantes' => $camposFaltantes,
                    'total_visitas_faltantes' => $total,
                ];
            }
        }
        
        return $alertas;
    }

    /**
     * Obtener datos extras de un niño por número de documento
     */
    public function datosExtras(Request $request)
    {
        $documento = $request->query('documento');
        
        if (!$documento) {
            return response()->json([
                'success' => false,
                'message' => 'Número de documento requerido'
            ], 400);
        }

        try {
            // Buscar el niño por número de documento aplicando filtros según el rol
            $query = Nino::where('numero_doc', $documento);
            $query = $this->applyRedMicroredFilter($query, 'datosExtra');
            $nino = $query->first();
            
            if (!$nino) {
                return response()->json([
                    'success' => false,
                    'message' => 'Niño no encontrado o no tiene acceso a este registro'
                ], 404);
            }

            // Obtener datos extras
            $datosExtra = $nino->datosExtra;
            
            // Obtener datos de la madre
            $madre = $nino->madre;

            // Preparar respuesta
            $ninoIdReal = $this->getNinoId($nino);
            $data = [
                'id' => $ninoIdReal,
                'id_niño' => $ninoIdReal,
                'establecimiento' => $nino->establecimiento,
                'fecha_nacimiento' => $nino->fecha_nacimiento ? $nino->fecha_nacimiento->format('Y-m-d') : null,
                'codigo_red' => $datosExtra ? $datosExtra->red : null,
                'codigo_microred' => $datosExtra ? $datosExtra->microred : null,
                'id_establecimiento' => $datosExtra ? $datosExtra->eess_nacimiento : null,
                'distrito' => $datosExtra ? $datosExtra->distrito : null,
                'provincia' => $datosExtra ? $datosExtra->provincia : null,
                'departamento' => $datosExtra ? $datosExtra->departamento : null,
                'seguro' => $datosExtra ? $datosExtra->seguro : null,
                'programa' => $datosExtra ? $datosExtra->programa : null,
                'dni_madre' => $madre ? $madre->dni : null,
                'apellidos_nombres_madre' => $madre ? $madre->apellidos_nombres : null,
                'celular_madre' => $madre ? $madre->celular : null,
                'domicilio_madre' => $madre ? $madre->domicilio : null,
                'referencia_direccion' => $madre ? $madre->referencia_direccion : null,
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar datos extras (solo para ADMIN)
     */
    /**
     * Obtener todos los controles de un niño (endpoint consolidado)
     */
    /**
     * Eliminar un niño y todos sus datos relacionados (solo admin)
     */
    public function eliminarNino($id)
    {
        // Verificar que el usuario sea admin
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'ADMIN') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para realizar esta acción. Solo los administradores pueden eliminar niños.'
            ], 403);
        }

        try {
            $nino = $this->findNino($id);
            $ninoIdReal = $this->getNinoId($nino);
            $nombreNino = $nino->apellidos_nombres ?? 'N/A';

            // Iniciar transacción
            DB::beginTransaction();

            // Eliminar todos los datos relacionados
            ControlMenor1::where('id_niño', $ninoIdReal)->delete();
            ControlRn::where('id_niño', $ninoIdReal)->delete();
            TamizajeNeonatal::where('id_niño', $ninoIdReal)->delete();
            VacunaRn::where('id_niño', $ninoIdReal)->delete();
            RecienNacido::where('id_niño', $ninoIdReal)->delete();
            VisitaDomiciliaria::where('id_niño', $ninoIdReal)->delete();
            DatosExtra::where('id_niño', $ninoIdReal)->delete();
            Madre::where('id_niño', $ninoIdReal)->delete();

            // Finalmente, eliminar el niño
            $nino->delete();

            // Confirmar transacción
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "El niño '{$nombreNino}' y todos sus datos relacionados han sido eliminados exitosamente."
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el niño especificado.'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al eliminar niño', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el niño: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function obtenerTodosControles($ninoId)
    {
        try {
            $nino = $this->findNino($ninoId);
            $ninoIdReal = $this->getNinoId($nino);
            
            // Obtener todos los controles
            $controlesRn = ControlRn::where('id_niño', $ninoIdReal)->orderBy('numero_control', 'asc')->get();
            $controlesCred = ControlMenor1::where('id_niño', $ninoIdReal)->orderBy('numero_control', 'asc')->get();
            $tamizaje = TamizajeNeonatal::where('id_niño', $ninoIdReal)->first();
            $cnv = RecienNacido::where('id_niño', $ninoIdReal)->first();
            $visitas = VisitaDomiciliaria::where('id_niño', $ninoIdReal)->orderBy('fecha_visita', 'desc')->get();
            $vacunas = VacunaRn::where('id_niño', $ninoIdReal)->first();
            $datosExtra = DatosExtra::where('id_niño', $ninoIdReal)->first();
            
            // Formatear controles recién nacido
            $controlesRnFormateados = $controlesRn->map(function($control) use ($nino) {
                // Calcular edad y estado dinámicamente
                $edadDias = null;
                $estado = 'SEGUIMIENTO';
                
                if ($nino && $nino->fecha_nacimiento && $control->fecha) {
                    $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                    $fechaControl = Carbon::parse($control->fecha);
                    $edadDias = $fechaNacimiento->diffInDays($fechaControl);
                    
                    // Calcular estado usando RangosCredService
                    $validacion = \App\Services\RangosCredService::validarControl(
                        $control->numero_control, 
                        $edadDias, 
                        'recien_nacido'
                    );
                    $estado = $validacion['estado'];
                }
                
                return [
                    'id' => $control->id_crn ?? $control->id,
                    'id_niño' => $control->id_niño,
                    'numero_control' => $control->numero_control,
                    'fecha' => $control->fecha ? $control->fecha->format('Y-m-d') : null,
                    'fecha_control' => $control->fecha ? $control->fecha->format('Y-m-d') : null,
                    'edad' => $edadDias, // Calculado dinámicamente
                    'edad_dias' => $edadDias, // Calculado dinámicamente
                    'estado' => $estado, // Calculado dinámicamente
                    // peso, talla, perimetro_cefalico eliminados - campos médicos innecesarios
                    'es_ejemplo' => false,
                ];
            });
            
            // Formatear controles CRED mensual
            $controlesCredFormateados = $controlesCred->map(function($control) use ($nino) {
                $fecha = $control->fecha ? ($control->fecha instanceof \Carbon\Carbon ? $control->fecha->format('Y-m-d') : $control->fecha) : null;
                
                // Calcular edad en días desde la fecha de nacimiento y la fecha del control
                $edadDias = null;
                $estadoRecalculado = 'SEGUIMIENTO'; // Por defecto
                
                if ($nino->fecha_nacimiento && $control->fecha) {
                    $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                    $fechaControl = Carbon::parse($control->fecha);
                    $edadDias = $fechaNacimiento->diffInDays($fechaControl);
                    
                    // Rangos CRED mensual
                    $rangosCRED = [
                        1 => ['min' => 29, 'max' => 59],
                        2 => ['min' => 60, 'max' => 89],
                        3 => ['min' => 90, 'max' => 119],
                        4 => ['min' => 120, 'max' => 149],
                        5 => ['min' => 150, 'max' => 179],
                        6 => ['min' => 180, 'max' => 209],
                        7 => ['min' => 210, 'max' => 239],
                        8 => ['min' => 240, 'max' => 269],
                        9 => ['min' => 270, 'max' => 299],
                        10 => ['min' => 300, 'max' => 329],
                        11 => ['min' => 330, 'max' => 359],
                    ];
                    
                    $numeroControl = $control->numero_control;
                    $rango = $rangosCRED[$numeroControl] ?? ['min' => 0, 'max' => 365];
                    
                    // Si hay control registrado, verificar si está dentro del rango
                    if ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) {
                        $estadoRecalculado = 'CUMPLE';
                    } elseif ($edadDias > $rango['max']) {
                        // Control registrado pero fuera del rango
                        $estadoRecalculado = 'NO CUMPLE';
                    } else {
                        // Control registrado pero antes del rango mínimo (raro, pero posible)
                        $estadoRecalculado = 'NO CUMPLE';
                    }
                }
                
                return [
                    'id' => $control->id_cred ?? $control->id,
                    'id_niño' => $control->id_niño,
                    'numero_control' => $control->numero_control,
                    'mes' => $control->numero_control,
                    'fecha' => $fecha,
                    'fecha_control' => $fecha,
                    'edad' => $edadDias, // Calcular edad en días desde fecha de nacimiento y fecha del control
                    'edad_dias' => $edadDias, // Alias para compatibilidad
                    'estado' => $estadoRecalculado,
                    // estado_cred_once y estado_cred_final eliminados - campos innecesarios
                    'es_ejemplo' => false,
                ];
            });
            
            // Formatear tamizaje
            $tamizajeFormateado = null;
            if ($tamizaje) {
                $tamizajeFormateado = [
                    'id' => $tamizaje->id_tamizaje ?? $tamizaje->id,
                    'id_niño' => $tamizaje->id_niño,
                    'numero_control' => $tamizaje->numero_control ?? null,
                    'fecha_tam_neo' => $tamizaje->fecha_tam_neo ? Carbon::parse($tamizaje->fecha_tam_neo)->format('Y-m-d') : null,
                    'fecha_tamizaje' => $tamizaje->fecha_tam_neo ? Carbon::parse($tamizaje->fecha_tam_neo)->format('Y-m-d') : null, // Alias para compatibilidad
                    'galen_fecha_tam_feo' => $tamizaje->galen_fecha_tam_feo ? Carbon::parse($tamizaje->galen_fecha_tam_feo)->format('Y-m-d') : null,
                    'es_ejemplo' => false,
                ];
            }
            
            // Formatear CNV
            $cnvFormateado = null;
            if ($cnv) {
                $cnvFormateado = [
                    'id' => $cnv->id_rn ?? $cnv->id,
                    'id_niño' => $cnv->id_niño,
                    'peso' => $cnv->peso,
                    'peso_nacer' => $cnv->peso, // Alias para compatibilidad con frontend
                    'edad_gestacional' => $cnv->edad_gestacional,
                    'clasificacion' => $cnv->clasificacion,
                    'es_ejemplo' => false,
                ];
            }
            
            // Formatear visitas
            $visitasFormateadas = $visitas->map(function($visita) use ($nino) {
                // Usar control_de_visita directamente, o numero_control como fallback
                $numeroControl = $visita->control_de_visita ?? $visita->numero_control ?? $visita->numero_visitas ?? 1;
                
                // Calcular edad en días de la visita si hay fecha
                $edadDias = null;
                if ($visita->fecha_visita && $nino->fecha_nacimiento) {
                    $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                    $fechaVisita = Carbon::parse($visita->fecha_visita);
                    $edadDias = $fechaNacimiento->diffInDays($fechaVisita);
                }
                
                // Mantener compatibilidad con código antiguo que usa grupo_visita y periodo
                $grupoVisita = $numeroControl <= 4 ? chr(64 + $numeroControl) : 'A'; // A, B, C, D
                $periodoTexto = "Control {$numeroControl}";
                
                return [
                    'id' => $visita->id_visita ?? $visita->id,
                    'id_niño' => $visita->id_niño,
                    'fecha_visita' => $visita->fecha_visita ? Carbon::parse($visita->fecha_visita)->format('Y-m-d') : null,
                    'control_de_visita' => $numeroControl,
                    'numero_control' => $numeroControl,
                    'numero_visitas' => $numeroControl, // Alias para compatibilidad
                    'grupo_visita' => $grupoVisita, // Mantener para compatibilidad
                    'periodo' => $periodoTexto, // Mantener para compatibilidad
                    'edad_dias' => $edadDias,
                    'es_ejemplo' => false,
                ];
            });
            
            // Formatear vacunas (como array para compatibilidad con el frontend)
            $vacunasFormateadas = [];
            if ($vacunas) {
                // Calcular edad en días desde fecha de nacimiento
                $fechaNacimiento = $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento) : null;
                
                if ($vacunas->fecha_bcg) {
                    $fechaBCG = Carbon::parse($vacunas->fecha_bcg);
                    $edadBCG = $fechaNacimiento ? $fechaNacimiento->diffInDays($fechaBCG) : null;
                    
                    $vacunasFormateadas[] = [
                        'id' => ($vacunas->id_vacuna ?? $vacunas->id) . '_bcg',
                        'id_niño' => $vacunas->id_niño,
                        'numero_control' => $vacunas->numero_control ?? null,
                        'nombre_vacuna' => 'BCG',
                        'fecha_aplicacion' => $fechaBCG->format('Y-m-d'),
                        'fecha_bcg' => $fechaBCG->format('Y-m-d'), // Alias para compatibilidad
                        'edad_dias' => $edadBCG,
                        'es_ejemplo' => false,
                    ];
                }
                if ($vacunas->fecha_hvb) {
                    $fechaHVB = Carbon::parse($vacunas->fecha_hvb);
                    $edadHVB = $fechaNacimiento ? $fechaNacimiento->diffInDays($fechaHVB) : null;
                    
                    $vacunasFormateadas[] = [
                        'id' => ($vacunas->id_vacuna ?? $vacunas->id) . '_hvb',
                        'id_niño' => $vacunas->id_niño,
                        'numero_control' => $vacunas->numero_control ?? null,
                        'nombre_vacuna' => 'HVB',
                        'fecha_aplicacion' => $fechaHVB->format('Y-m-d'),
                        'fecha_hvb' => $fechaHVB->format('Y-m-d'), // Alias para compatibilidad
                        'edad_dias' => $edadHVB,
                        'es_ejemplo' => false,
                    ];
                }
            }
            
            // Formatear datos extras
            $datosExtraFormateado = null;
            if ($datosExtra) {
                $datosExtraFormateado = [
                    'id' => $datosExtra->id_extra ?? $datosExtra->id,
                    'id_niño' => $datosExtra->id_niño,
                    'red' => $datosExtra->red,
                    'microred' => $datosExtra->microred,
                    'eess_nacimiento' => $datosExtra->eess_nacimiento,
                    'distrito' => $datosExtra->distrito,
                    'provincia' => $datosExtra->provincia,
                    'departamento' => $datosExtra->departamento,
                    'seguro' => $datosExtra->seguro,
                    'programa' => $datosExtra->programa,
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'nino' => [
                        'id_niño' => $nino->id,
                        'apellidos_nombres' => $nino->apellidos_nombres,
                        'numero_doc' => $nino->numero_doc,
                        'fecha_nacimiento' => $nino->fecha_nacimiento ? $nino->fecha_nacimiento->format('Y-m-d') : null,
                        'genero' => $nino->genero,
                    ],
                    'datos_extra' => $datosExtraFormateado,
                    'controles_recien_nacido' => [
                        'controles' => $controlesRnFormateados,
                    ],
                    'controles_cred_mensual' => [
                        'controles' => $controlesCredFormateados,
                    ],
                    'tamizaje' => $tamizajeFormateado,
                    'cnv' => $cnvFormateado,
                    'visitas' => $visitasFormateadas,
                    'vacunas' => $vacunasFormateadas,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener controles: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Obtener los últimos controles CRED registrados para el dashboard
     */
    public function ultimosControlesCred(Request $request)
    {
        try {
            $limite = $request->get('limite', 10);
            
            // Obtener los últimos controles CRED ordenados por id_cred descendente (o id si no existe id_cred)
            // Intentar primero con id_cred, si falla usar id
            try {
                $controles = ControlMenor1::with('nino')
                    ->orderBy('id_cred', 'desc')
                    ->limit($limite)
                    ->get();
            } catch (\Exception $e) {
                // Si falla, intentar con id
                $controles = ControlMenor1::with('nino')
                    ->orderBy('id', 'desc')
                    ->limit($limite)
                    ->get();
            }
            
            \Log::info('Controles CRED encontrados: ' . $controles->count());
            
            // Formatear los datos con información del niño
            $controlesFormateados = $controles->map(function($control) {
                $nino = $control->nino;
                
                if (!$nino) {
                    // Si no hay relación, intentar buscar el niño manualmente
                    $nino = Nino::find($control->id_niño);
                    if (!$nino) {
                        \Log::warning('Control CRED sin niño asociado: id_cred=' . ($control->id_cred ?? $control->id) . ', id_niño=' . $control->id_niño);
                        return null;
                    }
                }
                
                $fechaNacimiento = $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento) : null;
                $fechaFormateada = $fechaNacimiento ? $fechaNacimiento->format('Y-m-d') : null;
                
                // Calcular edad y estado dinámicamente
                $edadDias = null;
                $estado = 'SEGUIMIENTO';
                
                if ($nino && $nino->fecha_nacimiento && $control->fecha) {
                    $fechaNac = Carbon::parse($nino->fecha_nacimiento);
                    $fechaCtrl = Carbon::parse($control->fecha);
                    $edadDias = $fechaNac->diffInDays($fechaCtrl);
                    
                    // Calcular estado usando RangosCredService
                    $validacion = \App\Services\RangosCredService::validarControl(
                        $control->numero_control, 
                        $edadDias, 
                        'cred'
                    );
                    $estado = $validacion['estado'];
                }
                
                return [
                    'id_cred' => $control->id_cred ?? $control->id,
                    'id_niño' => $control->id_niño,
                    'numero_control' => $control->numero_control,
                    'fecha' => $control->fecha ? ($control->fecha instanceof \Carbon\Carbon ? $control->fecha->format('Y-m-d') : $control->fecha) : null,
                    'edad' => $edadDias, // Calculado dinámicamente
                    'estado' => $estado, // Calculado dinámicamente
                    'nino' => [
                        'id_niño' => $nino->id,
                        'establecimiento' => $nino->establecimiento ?? '-',
                        'numero_doc' => $nino->numero_doc ?? '-',
                        'apellidos_nombres' => $nino->apellidos_nombres ?? '-',
                        'fecha_nacimiento' => $fechaFormateada,
                        'genero' => $nino->genero ?? 'M',
                    ]
                ];
            })->filter(); // Eliminar nulls si algún control no tiene niño asociado
            
            \Log::info('Controles CRED formateados: ' . $controlesFormateados->count());
            
            return response()->json([
                'success' => true,
                'data' => $controlesFormateados->values(), // Reindexar el array
                'total' => $controlesFormateados->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en API ultimosControlesCred: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Generar PDF de explicación de alertas CRED
     */
    public function generarPdfExplicacionAlertas(Request $request)
    {
        $print = $request->get('print', false);
        $fechaGeneracion = Carbon::now()->format('d/m/Y H:i:s');
        
        // Información de las alertas para el PDF
        $informacionAlertas = [
            [
                'nombre' => 'Alertas de Controles Recién Nacido (RN)',
                'como' => 'Se generan alertas cuando un niño de 0 a 28 días no tiene registrados los controles correspondientes a su edad. El sistema verifica que existan los controles RN 1, 2, 3 y 4 según los rangos de días establecidos.',
                'campos' => [
                    'Número de control RN',
                    'Fecha del control',
                    'Edad del niño en días'
                ],
                'rangos' => [
                    'Control RN 1' => '2-6',
                    'Control RN 2' => '7-13',
                    'Control RN 3' => '14-20',
                    'Control RN 4' => '21-28'
                ]
            ],
            [
                'nombre' => 'Alertas de Controles CRED Mensuales',
                'como' => 'Se generan alertas cuando un niño de 29 a 359 días no tiene registrados los controles CRED mensuales correspondientes. El sistema verifica que existan los controles del mes 1 al 11 según los rangos de días establecidos.',
                'campos' => [
                    'Número de control CRED (mes)',
                    'Fecha del control',
                    'Edad del niño en días'
                ],
                'rangos' => [
                    'Control CRED Mes 1' => '29-59',
                    'Control CRED Mes 2' => '60-89',
                    'Control CRED Mes 3' => '90-119',
                    'Control CRED Mes 4' => '120-149',
                    'Control CRED Mes 5' => '150-179',
                    'Control CRED Mes 6' => '180-209',
                    'Control CRED Mes 7' => '210-239',
                    'Control CRED Mes 8' => '240-269',
                    'Control CRED Mes 9' => '270-299',
                    'Control CRED Mes 10' => '300-329',
                    'Control CRED Mes 11' => '330-359'
                ]
            ],
            [
                'nombre' => 'Alertas de Tamizaje Neonatal',
                'como' => 'Se genera una alerta cuando un niño de 1 a 29 días no tiene registrado el tamizaje neonatal. Este examen es obligatorio y debe realizarse dentro del primer mes de vida.',
                'campos' => [
                    'Fecha de tamizaje neonatal',
                    'Edad del niño en días'
                ]
            ],
            [
                'nombre' => 'Alertas de Vacunas',
                'como' => 'Se generan alertas cuando un niño de 0 a 30 días no tiene registradas las vacunas BCG y/o HVB dentro del rango establecido (0-2 días después del nacimiento).',
                'campos' => [
                    'Fecha de vacuna BCG',
                    'Fecha de vacuna HVB',
                    'Edad del niño en días'
                ],
                'rangos' => [
                    'Vacuna BCG' => '0-2',
                    'Vacuna HVB' => '0-2'
                ]
            ],
            [
                'nombre' => 'Alertas de CNV (Carné de Nacido Vivo)',
                'como' => 'Se genera una alerta cuando un niño no tiene registrado el CNV completo. Todos los campos son requeridos: peso al nacer, edad gestacional y clasificación.',
                'campos' => [
                    'Peso al nacer',
                    'Edad gestacional',
                    'Clasificación'
                ]
            ],
            [
                'nombre' => 'Alertas de Visitas Domiciliarias',
                'como' => 'Se generan alertas cuando un niño de 28 días o más no tiene registradas al menos 2 de las 4 visitas domiciliarias requeridas dentro de los rangos establecidos.',
                'campos' => [
                    'Fecha de visita domiciliaria',
                    'Número de control de visita',
                    'Edad del niño en días'
                ],
                'rangos' => [
                    'Visita 1 (28 días)' => '28',
                    'Visita 2' => '60-150',
                    'Visita 3' => '180-240',
                    'Visita 4' => '270-330'
                ]
            ]
        ];
        
        return view('dashboard.pdf-explicacion-alertas', [
            'print' => $print,
            'fechaGeneracion' => $fechaGeneracion,
            'informacionAlertas' => $informacionAlertas
        ]);
    }

    /**
     * Eliminar todos los datos del sistema (solo admin)
     * Esta función elimina:
     * - Todos los niños
     * - Todas las madres
     * - Todos los controles (RN y CRED)
     * - Todos los datos extras
     * - Todos los tamizajes
     * - Todas las vacunas
     * - Todas las visitas domiciliarias
     * - Todos los CNV
     * 
     * NOTA: NO elimina usuarios ni solicitudes
     */
    public function eliminarTodosDatos(Request $request)
    {
        // Verificar que el usuario sea admin
        $user = auth()->user();
        if (!$user || (strtolower($user->role) !== 'admin' && strtolower($user->role) !== 'administrator')) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

        try {
            // Nota: TRUNCATE no funciona dentro de transacciones en MySQL
            // porque TRUNCATE hace commit automático. Por eso no usamos transacciones aquí.
            
            // Desactivar verificación de foreign keys temporalmente para poder hacer truncate
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Eliminar todas las tablas (el orden no importa con foreign keys desactivadas)
            // 1. Eliminar controles CRED
            ControlMenor1::truncate();
            
            // 2. Eliminar controles RN
            ControlRn::truncate();
            
            // 3. Eliminar visitas domiciliarias
            VisitaDomiciliaria::truncate();
            
            // 4. Eliminar tamizajes
            TamizajeNeonatal::truncate();
            
            // 5. Eliminar vacunas
            VacunaRn::truncate();
            
            // 6. Eliminar CNV
            RecienNacido::truncate();
            
            // 7. Eliminar datos extras
            DatosExtra::truncate();
            
            // 8. Eliminar madres
            Madre::truncate();
            
            // 9. Eliminar niños
            Nino::truncate();

            // Reactivar verificación de foreign keys
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            \Log::info('Todos los datos eliminados por el usuario: ' . $user->email);

            return response()->json([
                'success' => true,
                'message' => 'Todos los datos han sido eliminados exitosamente'
            ]);

        } catch (\Exception $e) {
            // Asegurar que las foreign keys se reactiven incluso si hay error
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } catch (\Exception $e2) {
                // Ignorar errores al reactivar foreign keys
            }
            
            \Log::error('Error al eliminar todos los datos: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar los datos: ' . $e->getMessage()
            ], 500);
        }
    }
}
