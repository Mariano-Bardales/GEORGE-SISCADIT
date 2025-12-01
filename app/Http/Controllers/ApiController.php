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
            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
            $edadDias = $fechaNacimiento->diffInDays($hoy);
            
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

        // Calcular calidad de datos basado en registros completos y controles fuera de rango
        $totalNinos = Nino::count();
        $ninosConDatosCompletos = 0;
        $ninosConErrores = 0;
        $hoy = Carbon::now();

        if ($totalNinos > 0) {
            $ninos = Nino::with(['datosExtra', 'madre'])->get();
            
            foreach ($ninos as $nino) {
                if (!$nino->fecha_nacimiento) {
                    $ninosConErrores++;
                    continue;
                }
                
                $tieneErrores = false;
                
                // Verificar datos básicos del niño
                if (empty($nino->apellidos_nombres) || empty($nino->fecha_nacimiento) || empty($nino->genero)) {
                    $tieneErrores = true;
                }
                
                // Verificar datos extras
                if (!$nino->datosExtra || empty($nino->datosExtra->red) || empty($nino->datosExtra->microred)) {
                    $tieneErrores = true;
                }
                
                // Verificar datos de la madre
                if (!$nino->madre || empty($nino->madre->apellidos_nombres)) {
                    $tieneErrores = true;
                }
                
                // Verificar controles fuera de rango (solo si los datos básicos están completos)
                if (!$tieneErrores) {
                    $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                    $edadDias = $fechaNacimiento->diffInDays($hoy);
                    $ninoId = $this->getNinoId($nino);
                    
                    // Verificar controles CRED mensual fuera de rango o faltantes
                    if ($edadDias >= 29 && $edadDias <= 359) {
                        $controlesCred = ControlMenor1::where('id_niño', $ninoId)->get();
                        $rangosCred = [
                            1 => ['min' => 29, 'max' => 59], 2 => ['min' => 60, 'max' => 89], 3 => ['min' => 90, 'max' => 119],
                            4 => ['min' => 120, 'max' => 149], 5 => ['min' => 150, 'max' => 179], 6 => ['min' => 180, 'max' => 209],
                            7 => ['min' => 210, 'max' => 239], 8 => ['min' => 240, 'max' => 269], 9 => ['min' => 270, 'max' => 299],
                            10 => ['min' => 300, 'max' => 329], 11 => ['min' => 330, 'max' => 359]
                        ];
                        
                        foreach ($rangosCred as $numControl => $rango) {
                            $control = $controlesCred->firstWhere('numero_control', $numControl);
                            
                            if ($control && $control->fecha) {
                                // Verificar si el control está fuera de rango
                                $fechaControl = Carbon::parse($control->fecha);
                                $edadDiasControl = $fechaNacimiento->diffInDays($fechaControl);
                                
                                if ($edadDiasControl < $rango['min'] || $edadDiasControl > $rango['max']) {
                                    $tieneErrores = true;
                                    break; // Ya tiene un error, no necesitamos seguir verificando
                                }
                            } elseif ($edadDias > $rango['max']) {
                                // Ya pasó el rango y no hay control registrado - es un error
                                $tieneErrores = true;
                                break;
                            }
                        }
                    }
                    
                    // Verificar controles RN fuera de rango o faltantes
                    if ($edadDias <= 28) {
                        $controlesRn = ControlRn::where('id_niño', $ninoId)->get();
                        $rangosRN = [
                            1 => ['min' => 2, 'max' => 6],
                            2 => ['min' => 7, 'max' => 13],
                            3 => ['min' => 14, 'max' => 20],
                            4 => ['min' => 21, 'max' => 28]
                        ];
                        
                        foreach ($rangosRN as $numControl => $rango) {
                            $control = $controlesRn->firstWhere('numero_control', $numControl);
                            
                            if ($control && $control->fecha) {
                                // Verificar si el control está fuera de rango
                                $fechaControl = Carbon::parse($control->fecha);
                                $edadDiasControl = $fechaNacimiento->diffInDays($fechaControl);
                                
                                if ($edadDiasControl < $rango['min'] || $edadDiasControl > $rango['max']) {
                                    $tieneErrores = true;
                                    break;
                                }
                            } elseif ($edadDias > $rango['max']) {
                                // Ya pasó el rango y no hay control registrado - es un error
                                $tieneErrores = true;
                                break;
                            }
                        }
                    }
                    
                    // Verificar visitas domiciliarias fuera de rango o faltantes
                    if ($edadDias <= 365) {
                        $visitas = VisitaDomiciliaria::where('id_niño', $ninoId)->get();
                        $rangosVisitas = [
                            '28 días' => ['min' => 28, 'max' => 35],
                            '2-5 meses' => ['min' => 60, 'max' => 150],
                            '6-8 meses' => ['min' => 180, 'max' => 240],
                            '9-11 meses' => ['min' => 270, 'max' => 330]
                        ];
                        
                        foreach ($rangosVisitas as $periodo => $rango) {
                            $visita = $visitas->firstWhere('periodo', $periodo);
                            
                            if ($visita && $visita->fecha_visita) {
                                // Verificar si la visita está fuera de rango
                                $fechaVisita = Carbon::parse($visita->fecha_visita);
                                $edadDiasVisita = $fechaNacimiento->diffInDays($fechaVisita);
                                
                                if ($edadDiasVisita < $rango['min'] || $edadDiasVisita > $rango['max']) {
                                    $tieneErrores = true;
                                    break;
                                }
                            } elseif ($edadDias > $rango['max']) {
                                // Ya pasó el rango y no hay visita registrada - es un error
                                $tieneErrores = true;
                                break;
                            }
                        }
                    }
                }
                
                if ($tieneErrores) {
                    $ninosConErrores++;
                } else {
                    $ninosConDatosCompletos++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'genero' => [
                    'masculino' => $masculino,
                    'femenino' => $femenino,
                ],
                'calidad_datos' => [
                    'perfectos' => $ninosConDatosCompletos,
                    'con_errores' => $ninosConErrores,
                ],
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
                            'peso' => $control->peso,
                            'talla' => $control->talla,
                            'perimetro_cefalico' => $control->perimetro_cefalico,
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
                
                // Solo generar datos de ejemplo si NO hay controles reales
                // Los controles reales siempre tienen prioridad
                if ($controles->isEmpty()) {
                    $controles = $this->generarDatosEjemploCredMensual($nino, $ninoIdReal);
                } else {
                    // Los controles reales ya tienen el formato correcto del modelo
                    // No necesitamos mapearlos, solo asegurarnos de que se devuelvan correctamente
                    // El modelo Eloquent ya devuelve objetos con todas las propiedades
                }
                
                return response()->json([
                    'success' => true, 
                    'data' => [
                        'controles' => $controles,
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
            $controles = ControlMenor1::all();
            return response()->json(['success' => true, 'data' => ['controles' => $controles]]);
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
            1 => ['min' => 29, 'max' => 59, 'peso_base' => 3.5, 'talla_base' => 50, 'pc_base' => 35],
            2 => ['min' => 60, 'max' => 89, 'peso_base' => 4.2, 'talla_base' => 55, 'pc_base' => 37],
            3 => ['min' => 90, 'max' => 119, 'peso_base' => 5.0, 'talla_base' => 60, 'pc_base' => 39],
            4 => ['min' => 120, 'max' => 149, 'peso_base' => 5.8, 'talla_base' => 64, 'pc_base' => 40],
            5 => ['min' => 150, 'max' => 179, 'peso_base' => 6.5, 'talla_base' => 67, 'pc_base' => 41],
            6 => ['min' => 180, 'max' => 209, 'peso_base' => 7.2, 'talla_base' => 70, 'pc_base' => 42],
            7 => ['min' => 210, 'max' => 239, 'peso_base' => 7.8, 'talla_base' => 72, 'pc_base' => 43],
            8 => ['min' => 240, 'max' => 269, 'peso_base' => 8.3, 'talla_base' => 74, 'pc_base' => 44],
            9 => ['min' => 270, 'max' => 299, 'peso_base' => 8.8, 'talla_base' => 76, 'pc_base' => 45],
            10 => ['min' => 300, 'max' => 329, 'peso_base' => 9.2, 'talla_base' => 78, 'pc_base' => 45.5],
            11 => ['min' => 330, 'max' => 359, 'peso_base' => 9.6, 'talla_base' => 79, 'pc_base' => 46],
        ];
        
        // Usar el ID del niño para generar variaciones consistentes pero diferentes por niño
        $seed = $ninoIdReal % 100; // Usar módulo para tener valores entre 0-99
        
        foreach ($rangos as $numeroControl => $rango) {
            // Solo generar controles que ya deberían haberse realizado (edad actual >= min del rango)
            if ($edadDias >= $rango['min']) {
                // Calcular fecha del control (aproximadamente en el medio del rango)
                $diasDesdeNacimiento = $rango['min'] + (($rango['max'] - $rango['min']) / 2);
                $fechaControl = $fechaNacimiento->copy()->addDays($diasDesdeNacimiento);
                
                // Generar variaciones basadas en el seed del niño
                $variacionPeso = (($seed + $numeroControl) % 20 - 10) / 100; // Variación de -0.1 a +0.1 kg
                $variacionTalla = (($seed + $numeroControl * 2) % 10 - 5) / 10; // Variación de -0.5 a +0.5 cm
                $variacionPC = (($seed + $numeroControl * 3) % 6 - 3) / 10; // Variación de -0.3 a +0.3 cm
                
                $peso = round($rango['peso_base'] + $variacionPeso, 2);
                $talla = round($rango['talla_base'] + $variacionTalla, 1);
                $perimetroCefalico = round($rango['pc_base'] + $variacionPC, 1);
                
                // Determinar estado: si la edad actual está dentro del rango, es "cumple", si ya pasó es "cumple", si aún no llega es "pendiente"
                $estado = 'cumple';
                if ($edadDias < $rango['min']) {
                    $estado = 'pendiente';
                } elseif ($edadDias > $rango['max']) {
                    $estado = 'cumple'; // Ya cumplió
                }
                
                $controlesEjemplo->push([
                    'id' => null, // No es un registro real
                    'id_niño' => $ninoIdReal,
                    'numero_control' => $numeroControl,
                    'fecha' => $fechaControl->format('Y-m-d'),
                    'edad' => (int)$diasDesdeNacimiento,
                    'peso' => $peso,
                    'talla' => $talla,
                    'perimetro_cefalico' => $perimetroCefalico,
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
                    'peso' => $request->peso ?? $control->peso,
                    'talla' => $request->talla ?? $control->talla,
                    'perimetro_cefalico' => $request->perimetro_cefalico ?? $control->perimetro_cefalico,
                ]);
            } else {
                // Crear nuevo control
                $control = ControlMenor1::create([
                    'id_niño' => $ninoIdReal,
                    'numero_control' => $request->mes,
                    'fecha' => $request->fecha_control,
                    'edad' => $edadDias,
                    'estado' => $estado,
                    'peso' => $request->peso,
                    'talla' => $request->talla,
                    'perimetro_cefalico' => $request->perimetro_cefalico,
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
            'peso_nacer' => 'nullable|numeric|min:0',
            'edad_gestacional' => 'nullable|numeric|min:20|max:45',
            'clasificacion' => 'nullable|string|in:Normal,Bajo Peso al Nacer y/o Prematuro',
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
            
            $dataToUpdate = [];
            if ($request->has('peso_nacer') && $request->peso_nacer !== null && $request->peso_nacer !== '') {
                $dataToUpdate['peso'] = $request->peso_nacer;
            }
            if ($request->has('edad_gestacional') && $request->edad_gestacional !== null && $request->edad_gestacional !== '') {
                $dataToUpdate['edad_gestacional'] = $request->edad_gestacional;
            }
            if ($request->has('clasificacion') && $request->clasificacion !== null && $request->clasificacion !== '') {
                $dataToUpdate['clasificacion'] = $request->clasificacion;
            }
            
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
                'grupo_visita' => $request->periodo,
                'fecha_visita' => $request->fecha_visita,
                'numero_visitas' => $numeroVisitas,
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

    public function topEstablecimientos()
    {
        // Obtener datos reales de establecimientos desde la base de datos
        $establecimientosData = DB::table('niños')
            ->select('establecimiento', DB::raw('COUNT(*) as total_ninos'))
            ->whereNotNull('establecimiento')
            ->where('establecimiento', '!=', '')
            ->groupBy('establecimiento')
            ->get();

        $establecimientosStats = [];
        
        foreach ($establecimientosData as $est) {
            $establecimiento = $est->establecimiento;
            $totalNinos = $est->total_ninos;
            
            // Contar controles por establecimiento
            $ninosIds = Nino::where('establecimiento', $establecimiento)
                ->get()
                ->map(function($nino) {
                    return $this->getNinoId($nino);
                })
                ->toArray();
            $totalControlesRn = ControlRn::whereIn('id_niño', $ninosIds)->count();
            $totalControlesCred = ControlMenor1::whereIn('id_niño', $ninosIds)->count();
            $totalControles = $totalControlesRn + $totalControlesCred;
            
            // Calcular calidad de datos (porcentaje de niños con datos completos Y controles que cumplen)
            $ninosCompletos = 0;
            $ninos = Nino::where('establecimiento', $establecimiento)
                ->with(['datosExtra', 'madre'])
                ->get();
            
            $hoy = Carbon::now();
            
            foreach ($ninos as $nino) {
                $completo = true;
                
                // Verificar datos básicos
                if (empty($nino->apellidos_nombres) || empty($nino->fecha_nacimiento) || empty($nino->genero)) {
                    $completo = false;
                }
                if (!$nino->datosExtra || empty($nino->datosExtra->red)) {
                    $completo = false;
                }
                if (!$nino->madre || empty($nino->madre->apellidos_nombres)) {
                    $completo = false;
                }
                
                // Si los datos básicos están completos, verificar controles que cumplen
                if ($completo && $nino->fecha_nacimiento) {
                    $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                    $edadDias = $fechaNacimiento->diffInDays($hoy);
                    $ninoId = $this->getNinoId($nino);
                    
                    // Verificar controles CRED mensual fuera de rango o faltantes
                    if ($edadDias >= 29 && $edadDias <= 359) {
                        $controlesCred = ControlMenor1::where('id_niño', $ninoId)->get();
                        $rangosCred = [
                            1 => ['min' => 29, 'max' => 59], 2 => ['min' => 60, 'max' => 89], 3 => ['min' => 90, 'max' => 119],
                            4 => ['min' => 120, 'max' => 149], 5 => ['min' => 150, 'max' => 179], 6 => ['min' => 180, 'max' => 209],
                            7 => ['min' => 210, 'max' => 239], 8 => ['min' => 240, 'max' => 269], 9 => ['min' => 270, 'max' => 299],
                            10 => ['min' => 300, 'max' => 329], 11 => ['min' => 330, 'max' => 359]
                        ];
                        
                        foreach ($rangosCred as $numControl => $rango) {
                            $control = $controlesCred->firstWhere('numero_control', $numControl);
                            
                            if ($control && $control->fecha) {
                                $fechaControl = Carbon::parse($control->fecha);
                                $edadDiasControl = $fechaNacimiento->diffInDays($fechaControl);
                                
                                if ($edadDiasControl < $rango['min'] || $edadDiasControl > $rango['max']) {
                                    $completo = false;
                                    break;
                                }
                            } elseif ($edadDias > $rango['max']) {
                                $completo = false;
                                break;
                            }
                        }
                    }
                    
                    // Verificar controles RN fuera de rango o faltantes
                    if ($completo && $edadDias <= 28) {
                        $controlesRn = ControlRn::where('id_niño', $ninoId)->get();
                        $rangosRN = [
                            1 => ['min' => 2, 'max' => 6],
                            2 => ['min' => 7, 'max' => 13],
                            3 => ['min' => 14, 'max' => 20],
                            4 => ['min' => 21, 'max' => 28]
                        ];
                        
                        foreach ($rangosRN as $numControl => $rango) {
                            $control = $controlesRn->firstWhere('numero_control', $numControl);
                            
                            if ($control && $control->fecha) {
                                $fechaControl = Carbon::parse($control->fecha);
                                $edadDiasControl = $fechaNacimiento->diffInDays($fechaControl);
                                
                                if ($edadDiasControl < $rango['min'] || $edadDiasControl > $rango['max']) {
                                    $completo = false;
                                    break;
                                }
                            } elseif ($edadDias > $rango['max']) {
                                $completo = false;
                                break;
                            }
                        }
                    }
                    
                    // Verificar visitas domiciliarias fuera de rango o faltantes
                    if ($completo && $edadDias <= 365) {
                        $visitas = VisitaDomiciliaria::where('id_niño', $ninoId)->get();
                        $rangosVisitas = [
                            '28 días' => ['min' => 28, 'max' => 35],
                            '2-5 meses' => ['min' => 60, 'max' => 150],
                            '6-8 meses' => ['min' => 180, 'max' => 240],
                            '9-11 meses' => ['min' => 270, 'max' => 330]
                        ];
                        
                        foreach ($rangosVisitas as $periodo => $rango) {
                            $visita = $visitas->firstWhere('periodo', $periodo);
                            
                            if ($visita && $visita->fecha_visita) {
                                $fechaVisita = Carbon::parse($visita->fecha_visita);
                                $edadDiasVisita = $fechaNacimiento->diffInDays($fechaVisita);
                                
                                if ($edadDiasVisita < $rango['min'] || $edadDiasVisita > $rango['max']) {
                                    $completo = false;
                                    break;
                                }
                            } elseif ($edadDias > $rango['max']) {
                                $completo = false;
                                break;
                            }
                        }
                    }
                }
                
                if ($completo) {
                    $ninosCompletos++;
                }
            }
            
            $calidadPorcentaje = $totalNinos > 0 ? round(($ninosCompletos / $totalNinos) * 100, 1) : 0;
            
            $establecimientosStats[] = [
                'establecimiento' => $establecimiento,
                'total_controles' => $totalControles,
                'total_ninos' => $totalNinos,
                'calidad_porcentaje' => $calidadPorcentaje,
            ];
        }
        
        // Ordenar por calidad y total de controles
        usort($establecimientosStats, function($a, $b) {
            if ($a['calidad_porcentaje'] == $b['calidad_porcentaje']) {
                return $b['total_controles'] - $a['total_controles'];
            }
            return $b['calidad_porcentaje'] - $a['calidad_porcentaje'];
        });
        
        // Top 5 establecimientos con mejor calidad
        $topEstablecimientos = array_slice($establecimientosStats, 0, 5);
        
        // Establecimientos que necesitan mejora (calidad < 70%)
        $necesitanMejora = array_filter($establecimientosStats, function($est) {
            return $est['calidad_porcentaje'] < 70 && $est['total_ninos'] > 0;
        });
        
        // Ordenar por calidad (menor primero) y tomar los primeros 5
        usort($necesitanMejora, function($a, $b) {
            if ($a['calidad_porcentaje'] == $b['calidad_porcentaje']) {
                return $a['total_controles'] - $b['total_controles'];
            }
            return $a['calidad_porcentaje'] - $b['calidad_porcentaje'];
        });
        $necesitanMejora = array_slice($necesitanMejora, 0, 5);

        return response()->json([
            'success' => true,
            'data' => [
                'top_establecimientos' => $topEstablecimientos,
                'necesitan_mejora' => array_values($necesitanMejora),
            ]
        ]);
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
            
            // Alertas de tamizaje (1-29 días)
            if ($edadDias >= 1 && $edadDias <= 29) {
                $ninoId = $this->getNinoId($nino);
                $tamizaje = TamizajeNeonatal::where('id_niño', $ninoId)->first();
                if (!$tamizaje) {
                    $total++;
                }
            }
            
            // Alertas de vacunas (0-30 días)
            if ($edadDias <= 30) {
                $ninoId = $this->getNinoId($nino);
                $vacunas = VacunaRn::where('id_niño', $ninoId)->first();
                if (!$vacunas || !$vacunas->fecha_bcg || !$vacunas->fecha_hvb) {
                    $total++;
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
            
            // Alertas de visitas domiciliarias (0-365 días)
            if ($edadDias <= 365) {
                $visitas = VisitaDomiciliaria::where('id_niño', $ninoId)->get();
                $visitasRegistradasMap = [];
                foreach ($visitas as $visita) {
                    $visitasRegistradasMap[$visita->periodo] = $visita;
                }
                
                $rangosVisitas = [
                    '28 días' => ['min' => 28, 'max' => 35],
                    '2-5 meses' => ['min' => 60, 'max' => 150],
                    '6-8 meses' => ['min' => 180, 'max' => 240],
                    '9-11 meses' => ['min' => 270, 'max' => 330]
                ];
                
                $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                
                foreach ($rangosVisitas as $periodo => $rango) {
                    $visita = isset($visitasRegistradasMap[$periodo]) ? $visitasRegistradasMap[$periodo] : null;
                    
                    if ($visita && $visita->fecha_visita) {
                        // Verificar si la visita está fuera de rango
                        $fechaVisita = Carbon::parse($visita->fecha_visita);
                        $edadDiasVisita = $fechaNacimiento->diffInDays($fechaVisita);
                        
                        if ($edadDiasVisita < $rango['min'] || $edadDiasVisita > $rango['max']) {
                            // Visita fuera de rango
                            $diasFuera = $edadDiasVisita > $rango['max'] ? ($edadDiasVisita - $rango['max']) : ($rango['min'] - $edadDiasVisita);
                            $mensaje = $edadDiasVisita > $rango['max']
                                ? "La visita {$periodo} fue realizada a los {$edadDiasVisita} días, fuera del rango permitido ({$rango['min']}-{$rango['max']} días). Está {$diasFuera} día(s) fuera del límite máximo."
                                : "La visita {$periodo} fue realizada a los {$edadDiasVisita} días, fuera del rango permitido ({$rango['min']}-{$rango['max']} días). Está {$diasFuera} día(s) antes del límite mínimo.";
                            
                            $alertas[] = [
                                'tipo' => 'visita',
                                'nino_id' => $ninoId,
                                'nino_nombre' => $nino->apellidos_nombres,
                                'nino_dni' => $nino->numero_doc,
                                'establecimiento' => $nino->establecimiento,
                                'control' => "Visita {$periodo}",
                                'periodo' => $periodo,
                                'edad_dias' => $edadDias,
                                'edad_dias_visita' => $edadDiasVisita,
                                'rango_min' => $rango['min'],
                                'rango_max' => $rango['max'],
                                'rango_dias' => $rango['min'] . '-' . $rango['max'],
                                'prioridad' => 'alta',
                                'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                                'fecha_visita' => $visita->fecha_visita->format('Y-m-d'),
                                'mensaje' => $mensaje,
                                'dias_fuera' => $diasFuera,
                            ];
                        }
                    } else if ($edadDias > $rango['max']) {
                        // Visita faltante y ya pasó el rango
                        $diasFuera = $edadDias - $rango['max'];
                        $mensaje = "El niño tiene {$edadDias} días y la visita {$periodo} debió realizarse entre los {$rango['min']} y {$rango['max']} días. Ya pasaron {$diasFuera} día(s) del límite máximo.";
                        
                        $alertas[] = [
                            'tipo' => 'visita',
                            'nino_id' => $ninoId,
                            'nino_nombre' => $nino->apellidos_nombres,
                            'nino_dni' => $nino->numero_doc,
                            'establecimiento' => $nino->establecimiento,
                            'control' => "Visita {$periodo}",
                            'periodo' => $periodo,
                            'edad_dias' => $edadDias,
                            'rango_min' => $rango['min'],
                            'rango_max' => $rango['max'],
                            'rango_dias' => $rango['min'] . '-' . $rango['max'],
                            'prioridad' => 'alta',
                            'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                            'mensaje' => $mensaje,
                            'dias_fuera' => $diasFuera,
                        ];
                    }
                }
            }
            
            // Alertas de tamizaje (1-29 días)
            if ($edadDias >= 1 && $edadDias <= 29) {
                $tamizaje = TamizajeNeonatal::where('id_niño', $ninoId)->first();
                if (!$tamizaje) {
                    $diasFuera = $edadDias > 29 ? ($edadDias - 29) : 0;
                    $mensaje = $edadDias > 29 
                        ? "El niño tiene {$edadDias} días y el tamizaje neonatal debió realizarse entre los 1 y 29 días. Ya pasaron {$diasFuera} día(s) del límite máximo."
                        : "El niño tiene {$edadDias} días y debe realizarse el tamizaje neonatal entre los 1 y 29 días de vida.";
                    
                    $alertas[] = [
                        'tipo' => 'tamizaje',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Tamizaje Neonatal',
                        'edad_dias' => $edadDias,
                        'rango_min' => 1,
                        'rango_max' => 29,
                        'rango_dias' => '1-29',
                        'prioridad' => $edadDias > 29 ? 'alta' : 'media',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'dias_fuera' => $diasFuera,
                    ];
                }
            }
            
            // Alertas de vacunas (0-30 días)
            if ($edadDias <= 30) {
                $vacunas = VacunaRn::where('id_niño', $ninoId)->first();
                if (!$vacunas || !$vacunas->fecha_bcg) {
                    $diasFuera = $edadDias > 7 ? ($edadDias - 7) : 0;
                    $mensaje = $edadDias > 7 
                        ? "El niño tiene {$edadDias} días y la vacuna BCG debió aplicarse entre los 0 y 7 días. Ya pasaron {$diasFuera} día(s) del límite máximo."
                        : "El niño tiene {$edadDias} días y debe aplicarse la vacuna BCG entre los 0 y 7 días de vida.";
                    
                    $alertas[] = [
                        'tipo' => 'vacuna',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Vacuna BCG',
                        'edad_dias' => $edadDias,
                        'rango_min' => 0,
                        'rango_max' => 7,
                        'rango_dias' => '0-7',
                        'prioridad' => $edadDias > 7 ? 'alta' : 'media',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'dias_fuera' => $diasFuera,
                    ];
                }
                if (!$vacunas || !$vacunas->fecha_hvb) {
                    $diasFuera = $edadDias > 1 ? ($edadDias - 1) : 0;
                    $mensaje = $edadDias > 1 
                        ? "El niño tiene {$edadDias} días y la vacuna HVB debió aplicarse entre los 0 y 1 día. Ya pasaron {$diasFuera} día(s) del límite máximo."
                        : "El niño tiene {$edadDias} días y debe aplicarse la vacuna HVB entre los 0 y 1 día de vida.";
                    
                    $alertas[] = [
                        'tipo' => 'vacuna',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => 'Vacuna HVB',
                        'edad_dias' => $edadDias,
                        'rango_min' => 0,
                        'rango_max' => 1,
                        'rango_dias' => '0-1',
                        'prioridad' => $edadDias > 1 ? 'alta' : 'media',
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
            $data = [
                'id' => $nino->id,
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
     * Obtener todos los controles de un niño (endpoint consolidado)
     */
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
            $controlesCredFormateados = $controlesCred->map(function($control) {
                return [
                    'id' => $control->id_cred ?? $control->id,
                    'id_niño' => $control->id_niño,
                    'mes' => $control->numero_control,
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
            
            // Formatear tamizaje
            $tamizajeFormateado = null;
            if ($tamizaje) {
                $tamizajeFormateado = [
                    'id' => $tamizaje->id_tamizaje ?? $tamizaje->id,
                    'id_niño' => $tamizaje->id_niño,
                    'fecha_tamizaje' => $tamizaje->fecha_tam_neo ? Carbon::parse($tamizaje->fecha_tam_neo)->format('Y-m-d') : null,
                    'edad_dias' => $tamizaje->edad_tam_neo ?? null,
                    'cumple' => $tamizaje->cumple_tam_neo ?? null,
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
            $visitasFormateadas = $visitas->map(function($visita) {
                // Mapear grupo_visita a texto legible
                $periodoMap = [
                    'A' => '28 días de vida',
                    'B' => '2-5 meses',
                    'C' => '6-8 meses',
                    'D' => '9-11 meses',
                    // Mantener compatibilidad con códigos antiguos
                    '28d' => '28 días de vida',
                    '2-5m' => '2-5 meses',
                    '6-8m' => '6-8 meses',
                    '9-11m' => '9-11 meses',
                ];
                $periodoTexto = $periodoMap[$visita->grupo_visita] ?? $visita->grupo_visita;
                
                return [
                    'id' => $visita->id_visita ?? $visita->id,
                    'id_niño' => $visita->id_niño,
                    'fecha_visita' => $visita->fecha_visita ? Carbon::parse($visita->fecha_visita)->format('Y-m-d') : null,
                    'grupo_visita' => $visita->grupo_visita,
                    'periodo' => $periodoTexto, // Para compatibilidad con frontend
                    'numero_visitas' => $visita->numero_visitas,
                    'es_ejemplo' => false,
                ];
            });
            
            // Formatear vacunas (como array para compatibilidad con el frontend)
            $vacunasFormateadas = [];
            if ($vacunas) {
                if ($vacunas->fecha_bcg) {
                    $vacunasFormateadas[] = [
                        'id' => ($vacunas->id_vacuna ?? $vacunas->id) . '_bcg',
                        'id_niño' => $vacunas->id_niño,
                        'nombre_vacuna' => 'BCG',
                        'fecha_aplicacion' => Carbon::parse($vacunas->fecha_bcg)->format('Y-m-d'),
                        'edad_dias' => $vacunas->edad_bcg,
                        'es_ejemplo' => false,
                    ];
                }
                if ($vacunas->fecha_hvb) {
                    $vacunasFormateadas[] = [
                        'id' => ($vacunas->id_vacuna ?? $vacunas->id) . '_hvb',
                        'id_niño' => $vacunas->id_niño,
                        'nombre_vacuna' => 'HVB',
                        'fecha_aplicacion' => Carbon::parse($vacunas->fecha_hvb)->format('Y-m-d'),
                        'edad_dias' => $vacunas->edad_hvb,
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
}
