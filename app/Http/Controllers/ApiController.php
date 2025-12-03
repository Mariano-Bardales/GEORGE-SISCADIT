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
     * Helper para obtener el ID correcto del niño (id_niño o id)
     */
    private function getNinoId($nino)
    {
        return $nino->id_niño ?? $nino->id ?? null;
    }
    
    /**
     * Helper para buscar un niño por ID (solo id_niño)
     */
    private function findNino($id)
    {
        return Nino::where('id_niño', $id)->firstOrFail();
    }
    
    public function dashboardStats()
    {
        $totalNinos = Nino::count();
        $totalControles = ControlRn::count() + ControlMenor1::count();
        $totalUsuarios = User::count();
        
        // Calcular alertas reales
        $totalAlertas = 0;
        $hoy = Carbon::now();
        
        $ninos = Nino::all();
        
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
                $controlesRn = ControlRn::where('id_niño', $ninoId)->count();
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
                $controlesCred = ControlMenor1::where('id_niño', $ninoId)->count();
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
                $tamizaje = TamizajeNeonatal::where('id_niño', $ninoId)->first();
                if (!$tamizaje) {
                    $totalAlertas++;
                }
            }
            
            // Alertas de vacunas
            if ($edadDias <= 30) {
                $ninoId = $this->getNinoId($nino);
                $vacunas = VacunaRn::where('id_niño', $ninoId)->first();
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
        $masculino = Nino::where('genero', 'M')->count();
        $femenino = Nino::where('genero', 'F')->count();

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
            $query = Nino::with(['datosExtra', 'madre']);
            
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
            
            // Ordenar por id_niño (más recientes primero, ya que no hay created_at)
            $query->orderBy('id_niño', 'desc');
            
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
                    'datos_extras' => $nino->datos_extras ?? null,
                    'created_at' => null, // No hay timestamps en la tabla
                    'updated_at' => null, // No hay timestamps en la tabla
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
                    $controles = $controles->map(function($control) {
                        return [
                            'id' => $control->id,
                            'id_niño' => $control->id_niño,
                            'numero_control' => $control->numero_control,
                            'fecha' => $control->fecha ? $control->fecha->format('Y-m-d') : null,
                            'edad' => $control->edad,
                            'estado' => $control->estado,
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
        
        return response()->json(['success' => true, 'data' => $controles]);
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
                        'estado_cred_once' => $control->estado_cred_once ?? null,
                        'estado_cred_final' => $control->estado_cred_final ?? null,
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
                if ($nino && $nino->fecha_nacimiento && $control->fecha) {
                    $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                    $fechaControl = Carbon::parse($control->fecha);
                    $edadDias = $fechaNacimiento->diffInDays($fechaControl);
                }
                
                return [
                    'id' => $control->id_cred ?? $control->id,
                    'id_niño' => $control->id_niño,
                    'numero_control' => $control->numero_control,
                    'fecha' => $control->fecha ? ($control->fecha instanceof \Carbon\Carbon ? $control->fecha->format('Y-m-d') : $control->fecha) : null,
                    'edad' => $edadDias, // Calcular edad en días
                    'edad_dias' => $edadDias, // Alias para compatibilidad
                    'estado' => $control->estado ?? 'SEGUIMIENTO',
                    'estado_cred_once' => $control->estado_cred_once ?? null,
                    'estado_cred_final' => $control->estado_cred_final ?? null,
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
            $cnv = RecienNacido::where('id_niño', $ninoId)->first();
            
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
            }
        } else {
            $visitas = VisitaDomiciliaria::all();
        }
        return response()->json(['success' => true, 'data' => $visitas]);
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
        $periodos = [
            '28d' => ['dias' => 28, 'descripcion' => 'Visita domiciliaria a los 28 días de vida'],
            '60-150d' => ['dias' => 105, 'descripcion' => 'Visita domiciliaria entre 60 a 150 días'],
            '151-240d' => ['dias' => 195, 'descripcion' => 'Visita domiciliaria entre 151 a 240 días'],
            '241-330d' => ['dias' => 285, 'descripcion' => 'Visita domiciliaria entre 241 a 330 días'],
        ];
        
        $seed = $ninoIdReal % 100;
        $numeroVisita = 1;
        
        foreach ($periodos as $periodo => $info) {
            if ($edadDias >= $info['dias'] - 15) { // Generar si ya pasó o está cerca
                $fechaVisita = $fechaNacimiento->copy()->addDays($info['dias'] + (($seed + $numeroVisita) % 7) - 3);
                
                $visitasEjemplo->push([
                    'id' => null,
                    'id_niño' => $ninoIdReal,
                    'grupo_visita' => $periodo,
                    'fecha_visita' => $fechaVisita->format('Y-m-d'),
                    'numero_visitas' => $numeroVisita,
                    'estado' => ($edadDias >= $info['dias']) ? 'cumple' : 'pendiente',
                    'es_ejemplo' => true,
                ]);
                $numeroVisita++;
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
            
            $estado = ($edadDias >= 0 && $edadDias <= 1) ? 'SI' : 'NO';
            
            $vacunaData = [];
            if ($request->tipo_vacuna === 'BCG') {
                $vacunaData['fecha_bcg'] = $request->fecha_aplicacion;
                $vacunaData['edad_bcg'] = $edadDias;
                $vacunaData['estado_bcg'] = $estado;
            } else {
                $vacunaData['fecha_hvb'] = $request->fecha_aplicacion;
                $vacunaData['edad_hvb'] = $edadDias;
                $vacunaData['estado_hvb'] = $estado;
            }
            
            $vacuna = VacunaRn::updateOrCreate(
                ['id_niño' => $ninoIdReal],
                $vacunaData
            );
            
            // Actualizar cumple_BCG_HVB si ambas vacunas están aplicadas
            if ($vacuna->fecha_bcg && $vacuna->fecha_hvb) {
                $vacuna->cumple_BCG_HVB = ($vacuna->estado_bcg === 'SI' && $vacuna->estado_hvb === 'SI') ? 'SI' : 'NO';
                $vacuna->save();
            }

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
        $total = 0;
        $hoy = Carbon::now();
        
        // Obtener todos los niños
        $ninos = Nino::all();
        
        foreach ($ninos as $nino) {
            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
            $edadDias = $fechaNacimiento->diffInDays($hoy);
            
            // Alertas de controles recién nacido (0-28 días)
            if ($edadDias <= 28) {
                $ninoId = $this->getNinoId($nino);
                $controlesRn = ControlRn::where('id_niño', $ninoId)->count();
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
                    $total += ($controlesEsperados - $controlesRn);
                }
            }
            
            // Alertas de CRED mensual (29-359 días)
            if ($edadDias >= 29 && $edadDias <= 359) {
                $ninoId = $this->getNinoId($nino);
                $controlesCred = ControlMenor1::where('id_niño', $ninoId)->count();
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
                    $total += ($controlesEsperados - $controlesCred);
                }
            }
            
            // Alertas de tamizaje (0-29 días) - SOLO TAMIZAJE NEONATAL ES REQUERIDO
            if ($edadDias >= 0 && $edadDias <= 29) {
                $ninoId = $this->getNinoId($nino);
                $tamizaje = TamizajeNeonatal::where('id_niño', $ninoId)->first();
                // Solo verificar tamizaje neonatal (fecha_tam_neo), sisgalen es opcional
                if (!$tamizaje || !$tamizaje->fecha_tam_neo) {
                    $total++;
                }
            }
            
            // Alertas de vacunas (0-2 días) - AMBAS VACUNAS SON REQUERIDAS
            if ($edadDias >= 0 && $edadDias <= 2) {
                $ninoId = $this->getNinoId($nino);
                $vacunas = VacunaRn::where('id_niño', $ninoId)->first();
                // Verificar que tenga AMBAS vacunas (BCG y HVB)
                $tieneBCG = $vacunas && $vacunas->fecha_bcg && 
                           strtoupper(trim($vacunas->estado_bcg ?? '')) === 'SI';
                $tieneHVB = $vacunas && $vacunas->fecha_hvb && 
                           strtoupper(trim($vacunas->estado_hvb ?? '')) === 'SI';
                
                // Si falta UNA o AMBAS vacunas, generar alerta
                if (!$tieneBCG || !$tieneHVB) {
                    $total++;
                }
            }
            
            // Alertas de CNV (Carné de Nacido Vivo) - TODOS LOS DATOS SON REQUERIDOS
            $ninoId = $this->getNinoId($nino);
            $cnv = RecienNacido::where('id_niño', $ninoId)->first();
            if (!$cnv || empty($cnv->peso) || empty($cnv->edad_gestacional) || empty($cnv->clasificacion)) {
                $total++;
            }
            
            // Alertas de visitas domiciliarias - MÍNIMO 2 DE 4 REQUERIDAS
            if ($edadDias >= 28) {
                $visitas = VisitaDomiciliaria::where('id_niño', $ninoId)->get();
                
                $visitasCumplen = 0;
                $rangosVisitas = [
                    'A' => ['min' => 28, 'max' => 28],
                    'B' => ['min' => 60, 'max' => 150],
                    'C' => ['min' => 180, 'max' => 240],
                    'D' => ['min' => 270, 'max' => 330],
                ];
                
                foreach ($visitas as $visita) {
                    if ($visita->fecha_visita) {
                        $fechaVisita = Carbon::parse($visita->fecha_visita);
                        $edadVisita = $fechaNacimiento->diffInDays($fechaVisita);
                        $grupoVisita = strtoupper(trim($visita->grupo_visita ?? 'A'));
                        $rango = $rangosVisitas[$grupoVisita] ?? ['min' => 0, 'max' => 365];
                        
                        if ($edadVisita >= $rango['min'] && $edadVisita <= $rango['max']) {
                            $visitasCumplen++;
                        }
                    }
                }
                
                // Si tiene menos de 2 visitas cumplidas y ya pasó el tiempo para tenerlas, generar alerta
                if ($visitasCumplen < 2) {
                    // Verificar si ya debería tener al menos 2 visitas según su edad
                    $visitasEsperadas = 0;
                    if ($edadDias >= 28) $visitasEsperadas++; // Visita A
                    if ($edadDias >= 150) $visitasEsperadas++; // Visita B
                    if ($edadDias >= 240) $visitasEsperadas++; // Visita C
                    if ($edadDias >= 330) $visitasEsperadas++; // Visita D
                    
                    // Si debería tener al menos 2 visitas pero no las tiene, generar alerta
                    if ($visitasEsperadas >= 2 && $visitasCumplen < 2) {
                        $total++;
                    }
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'total' => $total
        ]);
    }

    /**
     * Obtener todas las alertas detalladas del sistema
     */
    public function obtenerAlertas(Request $request)
    {
        $hoy = Carbon::now();
        $alertas = [];
        
        // Obtener todos los niños
        $ninos = Nino::all();
        
        foreach ($ninos as $nino) {
            if (!$nino->fecha_nacimiento) continue;
            
            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
            $edadDias = $fechaNacimiento->diffInDays($hoy);
            $ninoId = $this->getNinoId($nino);
            
            // Alertas de controles recién nacido (0-28 días)
            if ($edadDias <= 28) {
                $controlesRn = ControlRn::where('id_niño', $ninoId)->get();
                $controlesRegistrados = $controlesRn->pluck('numero_control')->toArray();
                
                $rangosRN = [
                    1 => ['min' => 2, 'max' => 6, 'nombre' => 'CRN1'],
                    2 => ['min' => 7, 'max' => 13, 'nombre' => 'CRN2'],
                    3 => ['min' => 14, 'max' => 20, 'nombre' => 'CRN3'],
                    4 => ['min' => 21, 'max' => 28, 'nombre' => 'CRN4']
                ];
                
                foreach ($rangosRN as $num => $rango) {
                    $debeTener = false;
                    if ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) {
                        $debeTener = true;
                    } else if ($edadDias > $rango['max']) {
                        $debeTener = true;
                    }
                    
                    if ($debeTener && !in_array($num, $controlesRegistrados)) {
                        $diasFuera = $edadDias > $rango['max'] ? ($edadDias - $rango['max']) : 0;
                        $mensaje = $edadDias > $rango['max'] 
                            ? "El niño tiene {$edadDias} días y el control {$rango['nombre']} debió realizarse entre los {$rango['min']} y {$rango['max']} días. Ya pasaron {$diasFuera} día(s) del límite máximo."
                            : "El niño tiene {$edadDias} días y debe realizarse el control {$rango['nombre']} entre los {$rango['min']} y {$rango['max']} días.";
                        
                        $alertas[] = [
                            'tipo' => 'control_recien_nacido',
                            'nino_id' => $ninoId,
                            'nino_nombre' => $nino->apellidos_nombres,
                            'nino_dni' => $nino->numero_doc,
                            'establecimiento' => $nino->establecimiento,
                            'control' => $rango['nombre'],
                            'edad_dias' => $edadDias,
                            'rango_min' => $rango['min'],
                            'rango_max' => $rango['max'],
                            'rango_dias' => $rango['min'] . '-' . $rango['max'],
                            'prioridad' => $edadDias > $rango['max'] ? 'alta' : 'media',
                            'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                            'mensaje' => $mensaje,
                            'dias_fuera' => $diasFuera,
                        ];
                    }
                }
            }
            
            // Alertas de CRED mensual (29-359 días)
            if ($edadDias >= 29 && $edadDias <= 359) {
                $controlesCred = ControlMenor1::where('id_niño', $ninoId)->get();
                $controlesRegistradosMap = [];
                foreach ($controlesCred as $control) {
                    $controlesRegistradosMap[$control->numero_control] = $control;
                }
                
                $rangosCred = [
                    1 => ['min' => 29, 'max' => 59, 'nombre' => 'Mes 1'],
                    2 => ['min' => 60, 'max' => 89, 'nombre' => 'Mes 2'],
                    3 => ['min' => 90, 'max' => 119, 'nombre' => 'Mes 3'],
                    4 => ['min' => 120, 'max' => 149, 'nombre' => 'Mes 4'],
                    5 => ['min' => 150, 'max' => 179, 'nombre' => 'Mes 5'],
                    6 => ['min' => 180, 'max' => 209, 'nombre' => 'Mes 6'],
                    7 => ['min' => 210, 'max' => 239, 'nombre' => 'Mes 7'],
                    8 => ['min' => 240, 'max' => 269, 'nombre' => 'Mes 8'],
                    9 => ['min' => 270, 'max' => 299, 'nombre' => 'Mes 9'],
                    10 => ['min' => 300, 'max' => 329, 'nombre' => 'Mes 10'],
                    11 => ['min' => 330, 'max' => 359, 'nombre' => 'Mes 11']
                ];
                
                $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                
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
                            // Control fuera de rango
                            $diasFuera = $edadDiasControl > $rango['max'] ? ($edadDiasControl - $rango['max']) : ($rango['min'] - $edadDiasControl);
                            $mensaje = $edadDiasControl > $rango['max']
                                ? "El control {$rango['nombre']} fue realizado a los {$edadDiasControl} días, fuera del rango permitido ({$rango['min']}-{$rango['max']} días). Está {$diasFuera} día(s) fuera del límite máximo."
                                : "El control {$rango['nombre']} fue realizado a los {$edadDiasControl} días, fuera del rango permitido ({$rango['min']}-{$rango['max']} días). Está {$diasFuera} día(s) antes del límite mínimo.";
                            
                            $alertas[] = [
                                'tipo' => 'control_cred_mensual',
                                'nino_id' => $ninoId,
                                'nino_nombre' => $nino->apellidos_nombres,
                                'nino_dni' => $nino->numero_doc,
                                'establecimiento' => $nino->establecimiento,
                                'control' => $rango['nombre'],
                                'mes' => $mes,
                                'edad_dias' => $edadDias,
                                'edad_dias_control' => $edadDiasControl,
                                'rango_min' => $rango['min'],
                                'rango_max' => $rango['max'],
                                'rango_dias' => $rango['min'] . '-' . $rango['max'],
                                'prioridad' => 'alta',
                                'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                                'fecha_control' => $control->fecha->format('Y-m-d'),
                                'mensaje' => $mensaje,
                                'dias_fuera' => $diasFuera,
                            ];
                        }
                    } else if ($debeTener && !$control) {
                        // Control faltante
                        $diasFuera = $edadDias > $rango['max'] ? ($edadDias - $rango['max']) : 0;
                        $mensaje = $edadDias > $rango['max'] 
                            ? "El niño tiene {$edadDias} días y el control {$rango['nombre']} debió realizarse entre los {$rango['min']} y {$rango['max']} días. Ya pasaron {$diasFuera} día(s) del límite máximo."
                            : "El niño tiene {$edadDias} días y debe realizarse el control {$rango['nombre']} entre los {$rango['min']} y {$rango['max']} días.";
                        
                        $alertas[] = [
                            'tipo' => 'control_cred_mensual',
                            'nino_id' => $ninoId,
                            'nino_nombre' => $nino->apellidos_nombres,
                            'nino_dni' => $nino->numero_doc,
                            'establecimiento' => $nino->establecimiento,
                            'control' => $rango['nombre'],
                            'mes' => $mes,
                            'edad_dias' => $edadDias,
                            'rango_min' => $rango['min'],
                            'rango_max' => $rango['max'],
                            'rango_dias' => $rango['min'] . '-' . $rango['max'],
                            'prioridad' => $edadDias > $rango['max'] ? 'alta' : 'media',
                            'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                            'mensaje' => $mensaje,
                            'dias_fuera' => $diasFuera,
                        ];
                    }
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
            
            // Alertas de visitas domiciliarias - MÍNIMO 2 DE 4 REQUERIDAS
            if ($edadDias >= 28) {
                $visitas = VisitaDomiciliaria::where('id_niño', $ninoId)->get();
                $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                
                $rangosVisitas = [
                    'A' => ['min' => 28, 'max' => 28, 'nombre' => 'Visita A (28 días)'],
                    'B' => ['min' => 60, 'max' => 150, 'nombre' => 'Visita B (2-5 meses)'],
                    'C' => ['min' => 180, 'max' => 240, 'nombre' => 'Visita C (6-8 meses)'],
                    'D' => ['min' => 270, 'max' => 330, 'nombre' => 'Visita D (9-11 meses)']
                ];
                
                $visitasCumplen = 0;
                $visitasPorGrupo = [];
                
                foreach ($visitas as $visita) {
                    $grupoVisita = strtoupper(trim($visita->grupo_visita ?? 'A'));
                    if (!isset($visitasPorGrupo[$grupoVisita])) {
                        $visitasPorGrupo[$grupoVisita] = [];
                    }
                    $visitasPorGrupo[$grupoVisita][] = $visita;
                }
                
                // Evaluar cada grupo de visita
                foreach ($rangosVisitas as $grupo => $rango) {
                    $visitasGrupo = $visitasPorGrupo[$grupo] ?? [];
                    $tieneVisitaCumplida = false;
                    
                    foreach ($visitasGrupo as $visita) {
                        if ($visita->fecha_visita) {
                            $fechaVisita = Carbon::parse($visita->fecha_visita);
                            $edadDiasVisita = $fechaNacimiento->diffInDays($fechaVisita);
                            
                            if ($edadDiasVisita >= $rango['min'] && $edadDiasVisita <= $rango['max']) {
                                $tieneVisitaCumplida = true;
                                $visitasCumplen++;
                                break; // Solo cuenta una visita cumplida por grupo
                            } elseif ($edadDiasVisita > $rango['max']) {
                                // Visita fuera de rango (tarde)
                                $diasFuera = $edadDiasVisita - $rango['max'];
                                $alertas[] = [
                                    'tipo' => 'visita',
                                    'nino_id' => $ninoId,
                                    'nino_nombre' => $nino->apellidos_nombres,
                                    'nino_dni' => $nino->numero_doc,
                                    'establecimiento' => $nino->establecimiento,
                                    'control' => $rango['nombre'],
                                    'grupo' => $grupo,
                                    'edad_dias' => $edadDias,
                                    'edad_dias_visita' => $edadDiasVisita,
                                    'rango_min' => $rango['min'],
                                    'rango_max' => $rango['max'],
                                    'rango_dias' => $rango['min'] . '-' . $rango['max'],
                                    'prioridad' => 'alta',
                                    'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                                    'fecha_visita' => $visita->fecha_visita->format('Y-m-d'),
                                    'mensaje' => "La {$rango['nombre']} fue realizada a los {$edadDiasVisita} días, fuera del rango permitido ({$rango['min']}-{$rango['max']} días). Está {$diasFuera} día(s) fuera del límite máximo.",
                                    'dias_fuera' => $diasFuera,
                                ];
                            }
                        }
                    }
                    
                    // Si no tiene visita cumplida y ya pasó el rango, generar alerta
                    if (!$tieneVisitaCumplida && $edadDias > $rango['max']) {
                        $diasFuera = $edadDias - $rango['max'];
                        $alertas[] = [
                            'tipo' => 'visita',
                            'nino_id' => $ninoId,
                            'nino_nombre' => $nino->apellidos_nombres,
                            'nino_dni' => $nino->numero_doc,
                            'establecimiento' => $nino->establecimiento,
                            'control' => $rango['nombre'],
                            'grupo' => $grupo,
                            'edad_dias' => $edadDias,
                            'rango_min' => $rango['min'],
                            'rango_max' => $rango['max'],
                            'rango_dias' => $rango['min'] . '-' . $rango['max'],
                            'prioridad' => 'alta',
                            'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                            'mensaje' => "El niño tiene {$edadDias} días y la {$rango['nombre']} debió realizarse entre los {$rango['min']} y {$rango['max']} días. Ya pasaron {$diasFuera} día(s) del límite máximo.",
                            'dias_fuera' => $diasFuera,
                        ];
                    }
                }
                
                // Si tiene menos de 2 visitas cumplidas y ya debería tenerlas, generar alerta general
                if ($visitasCumplen < 2) {
                    $visitasEsperadas = 0;
                    if ($edadDias >= 28) $visitasEsperadas++; // Visita A
                    if ($edadDias >= 150) $visitasEsperadas++; // Visita B
                    if ($edadDias >= 240) $visitasEsperadas++; // Visita C
                    if ($edadDias >= 330) $visitasEsperadas++; // Visita D
                    
                    // Si debería tener al menos 2 visitas pero no las tiene, generar alerta general
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
            
            // Alertas de tamizaje (0-29 días) - SOLO TAMIZAJE NEONATAL ES REQUERIDO
            if ($edadDias >= 0 && $edadDias <= 29) {
                $tamizaje = TamizajeNeonatal::where('id_niño', $ninoId)->first();
                // Solo verificar tamizaje neonatal (fecha_tam_neo), sisgalen es opcional
                if (!$tamizaje || !$tamizaje->fecha_tam_neo) {
                    $diasFuera = $edadDias > 29 ? ($edadDias - 29) : 0;
                    $mensaje = $edadDias > 29 
                        ? "El niño tiene {$edadDias} días y el tamizaje neonatal debió realizarse entre los 0 y 29 días. Ya pasaron {$diasFuera} día(s) del límite máximo."
                        : "El niño tiene {$edadDias} días y debe realizarse el tamizaje neonatal entre los 0 y 29 días de vida.";
                    
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
                        'dias_fuera' => $diasFuera,
                    ];
                }
            }
            
            // Alertas de vacunas (0-2 días) - AMBAS VACUNAS SON REQUERIDAS
            if ($edadDias >= 0 && $edadDias <= 2) {
                $vacunas = VacunaRn::where('id_niño', $ninoId)->first();
                // Verificar que tenga AMBAS vacunas (BCG y HVB)
                $tieneBCG = $vacunas && $vacunas->fecha_bcg && 
                           strtoupper(trim($vacunas->estado_bcg ?? '')) === 'SI';
                $tieneHVB = $vacunas && $vacunas->fecha_hvb && 
                           strtoupper(trim($vacunas->estado_hvb ?? '')) === 'SI';
                
                // Si falta BCG, generar alerta
                if (!$tieneBCG) {
                    $diasFuera = $edadDias > 2 ? ($edadDias - 2) : 0;
                    $mensaje = $edadDias > 2 
                        ? "El niño tiene {$edadDias} días y la vacuna BCG debió aplicarse entre los 0 y 2 días. Ya pasaron {$diasFuera} día(s) del límite máximo."
                        : "El niño tiene {$edadDias} días y debe aplicarse la vacuna BCG entre los 0 y 2 días de vida.";
                    
                    $alertas[] = [
                        'tipo' => 'vacuna',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Vacuna BCG',
                        'edad_dias' => $edadDias,
                        'rango_min' => 0,
                        'rango_max' => 2,
                        'rango_dias' => '0-2',
                        'prioridad' => $edadDias > 2 ? 'alta' : 'media',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'dias_fuera' => $diasFuera,
                    ];
                }
                
                // Si falta HVB, generar alerta
                if (!$tieneHVB) {
                    $diasFuera = $edadDias > 2 ? ($edadDias - 2) : 0;
                    $mensaje = $edadDias > 2 
                        ? "El niño tiene {$edadDias} días y la vacuna HVB debió aplicarse entre los 0 y 2 días. Ya pasaron {$diasFuera} día(s) del límite máximo."
                        : "El niño tiene {$edadDias} días y debe aplicarse la vacuna HVB entre los 0 y 2 días de vida.";
                    
                    $alertas[] = [
                        'tipo' => 'vacuna',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Vacuna HVB',
                        'edad_dias' => $edadDias,
                        'rango_min' => 0,
                        'rango_max' => 2,
                        'rango_dias' => '0-2',
                        'prioridad' => $edadDias > 2 ? 'alta' : 'media',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'dias_fuera' => $diasFuera,
                    ];
                }
            }
        }
        
        // Ordenar por prioridad (alta primero) y luego por edad
        usort($alertas, function($a, $b) {
            if ($a['prioridad'] === $b['prioridad']) {
                return $b['edad_dias'] - $a['edad_dias'];
            }
            return $a['prioridad'] === 'alta' ? -1 : 1;
        });
        
        return response()->json([
            'success' => true,
            'data' => $alertas,
            'total' => count($alertas)
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
          ->header('Pragma', 'no-cache')
          ->header('Expires', '0');
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
            // Buscar el niño por número de documento
            $nino = Nino::where('numero_doc', $documento)->first();
            
            if (!$nino) {
                return response()->json([
                    'success' => false,
                    'message' => 'Niño no encontrado'
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
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el niño: ' . $e->getMessage()
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
            $controlesRnFormateados = $controlesRn->map(function($control) {
                return [
                    'id' => $control->id_crn ?? $control->id,
                    'id_niño' => $control->id_niño,
                    'numero_control' => $control->numero_control,
                    'fecha' => $control->fecha ? $control->fecha->format('Y-m-d') : null,
                    'fecha_control' => $control->fecha ? $control->fecha->format('Y-m-d') : null,
                    'edad' => $control->edad,
                    'edad_dias' => $control->edad,
                    'estado' => $control->estado,
                    'peso' => $control->peso ?? null,
                    'talla' => $control->talla ?? null,
                    'perimetro_cefalico' => $control->perimetro_cefalico ?? null,
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
                    'estado_cred_once' => $control->estado_cred_once ?? null,
                    'estado_cred_final' => $control->estado_cred_final ?? null,
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
                $numeroControl = $visita->numero_control ?? 1;
                
                // Calcular edad en días de la visita para determinar el grupo
                $grupoVisita = null;
                $periodoTexto = "Visita {$numeroControl}";
                
                if ($visita->fecha_visita && $nino->fecha_nacimiento) {
                    $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                    $fechaVisita = Carbon::parse($visita->fecha_visita);
                    $edadDias = $fechaNacimiento->diffInDays($fechaVisita);
                    
                    // Determinar grupo basado en edad en días
                    if ($edadDias >= 28 && $edadDias <= 35) {
                        $grupoVisita = 'A';
                        $periodoTexto = '28 días de vida';
                    } elseif ($edadDias >= 60 && $edadDias <= 150) {
                        $grupoVisita = 'B';
                        $periodoTexto = '2-5 meses';
                    } elseif ($edadDias >= 180 && $edadDias <= 240) {
                        $grupoVisita = 'C';
                        $periodoTexto = '6-8 meses';
                    } elseif ($edadDias >= 270 && $edadDias <= 330) {
                        $grupoVisita = 'D';
                        $periodoTexto = '9-11 meses';
                    } else {
                        // Si no coincide con ningún rango, usar el numero_control
                        $grupoVisita = $numeroControl <= 4 ? chr(64 + $numeroControl) : 'A'; // A, B, C, D
                    }
                } else {
                    // Si no hay fecha, usar numero_control para determinar grupo
                    $grupoVisita = $numeroControl <= 4 ? chr(64 + $numeroControl) : 'A'; // A, B, C, D
                }
                
                return [
                    'id' => $visita->id_visita ?? $visita->id,
                    'id_niño' => $visita->id_niño,
                    'fecha_visita' => $visita->fecha_visita ? Carbon::parse($visita->fecha_visita)->format('Y-m-d') : null,
                    'grupo_visita' => $grupoVisita,
                    'periodo' => $periodoTexto,
                    'numero_control' => $numeroControl,
                    'numero_visitas' => $numeroControl, // Alias para compatibilidad
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
                        'id_niño' => $nino->id_niño,
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
                    $nino = Nino::where('id_niño', $control->id_niño)->first();
                    if (!$nino) {
                        \Log::warning('Control CRED sin niño asociado: id_cred=' . ($control->id_cred ?? $control->id) . ', id_niño=' . $control->id_niño);
                        return null;
                    }
                }
                
                $fechaNacimiento = $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento) : null;
                $fechaFormateada = $fechaNacimiento ? $fechaNacimiento->format('Y-m-d') : null;
                
                return [
                    'id_cred' => $control->id_cred ?? $control->id,
                    'id_niño' => $control->id_niño,
                    'numero_control' => $control->numero_control,
                    'fecha' => $control->fecha ? ($control->fecha instanceof \Carbon\Carbon ? $control->fecha->format('Y-m-d') : $control->fecha) : null,
                    'edad' => $control->edad,
                    'estado' => $control->estado,
                    'nino' => [
                        'id_niño' => $nino->id_niño,
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
}
