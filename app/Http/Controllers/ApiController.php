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
     * Helper para buscar un niño por ID (puede ser id_niño o id)
     */
    private function findNino($id)
    {
        return Nino::where('id_niño', $id)
                   ->orWhere('id', $id)
                   ->firstOrFail();
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

        // Calcular calidad de datos basado en registros completos
        $totalNinos = Nino::count();
        $ninosConDatosCompletos = 0;
        $ninosConErrores = 0;

        if ($totalNinos > 0) {
            $ninos = Nino::with(['datosExtra', 'madre'])->get();
            
            foreach ($ninos as $nino) {
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
                'establecimiento' => $nino->establecimiento,
                'tipo_doc' => $nino->tipo_doc,
                'id_tipo_documento' => $idTipoDoc, // Para compatibilidad
                'numero_doc' => $nino->numero_doc,
                'numero_documento' => $nino->numero_doc, // Para compatibilidad
                'apellidos_nombres' => $nino->apellidos_nombres,
                'fecha_nacimiento' => $nino->fecha_nacimiento ? $nino->fecha_nacimiento->format('Y-m-d') : null,
                'genero' => $nino->genero,
                'edad_meses' => $nino->edad_meses,
                'edad_dias' => $nino->edad_dias,
                'datos_extras' => $nino->datos_extras,
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
            // Buscar por id_niño (puede ser id_niño o id dependiendo del modelo)
            $controles = ControlRn::where('id_niño', $ninoId)->orderBy('numero_control', 'asc')->get();
        } else {
            $controles = ControlRn::all();
        }
        
        return response()->json(['success' => true, 'data' => $controles]);
    }
    
    public function registrarControlRecienNacido(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Validamos que venga un ID y dejamos que findNino resuelva si es id_niño o id
            'nino_id' => 'required|integer',
            'numero_control' => 'required|integer|between:0,4',
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
                0 => ['min' => 0, 'max' => 1],
                1 => ['min' => 2, 'max' => 6],
                2 => ['min' => 7, 'max' => 13],
                3 => ['min' => 14, 'max' => 20],
                4 => ['min' => 21, 'max' => 28],
            ];
            
            $rango = $rangos[$request->numero_control] ?? ['min' => 0, 'max' => 28];
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
                0 => ['min' => 0, 'max' => 1],
                1 => ['min' => 2, 'max' => 6],
                2 => ['min' => 7, 'max' => 13],
                3 => ['min' => 14, 'max' => 20],
                4 => ['min' => 21, 'max' => 28],
            ];
            
            $rango = $rangos[$control->numero_control] ?? ['min' => 0, 'max' => 28];
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
    
    public function registrarCredMensual(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'nino_id' => 'required|exists:ninos,id',
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
    
    public function registrarTamizaje(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nino_id' => 'required|exists:ninos,id',
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
            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
            $fechaTamizaje = Carbon::parse($request->fecha_tam_neo);
            $edadDias = $fechaNacimiento->diffInDays($fechaTamizaje);
            
            $cumple = ($edadDias >= 1 && $edadDias <= 30) ? 'SI' : 'NO';

            $tamizaje = TamizajeNeonatal::updateOrCreate(
                ['id_niño' => $request->nino_id],
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
        } else {
            $cnv = RecienNacido::all();
        }
        return response()->json(['success' => true, 'data' => $cnv]);
    }
    
    public function registrarCNV(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nino_id' => 'required|exists:ninos,id',
            'peso_nacer' => 'required|numeric|min:0',
            'edad_gestacional' => 'required|numeric|min:20|max:45',
            'clasificacion' => 'required|string|in:A TÉRMINO,PREMATURO,BAJO PESO,PREMATURO BAJO PESO',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cnv = RecienNacido::updateOrCreate(
                ['id_niño' => $request->nino_id],
                [
                    'peso' => $request->peso_nacer,
                    'edad_gestacional' => $request->edad_gestacional,
                    'clasificacion' => $request->clasificacion,
                ]
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
        } else {
            $visitas = VisitaDomiciliaria::all();
        }
        return response()->json(['success' => true, 'data' => $visitas]);
    }
    
    public function registrarVisita(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nino_id' => 'required|exists:ninos,id',
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
            // Contar visitas existentes para este niño
            $numeroVisitas = VisitaDomiciliaria::where('id_niño', $request->nino_id)->count() + 1;

            $visita = VisitaDomiciliaria::create([
                'id_niño' => $request->nino_id,
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
    
    public function registrarVacuna(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nino_id' => 'required|exists:ninos,id',
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
            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
            $fechaAplicacion = Carbon::parse($request->fecha_aplicacion);
            $edadDias = $fechaNacimiento->diffInDays($fechaAplicacion);
            
            $estado = ($edadDias >= 0 && $edadDias <= 1) ? 'SI' : 'NO';
            
            $vacuna = VacunaRn::updateOrCreate(
                ['id_niño' => $request->nino_id],
                [
                    $request->tipo_vacuna === 'BCG' ? 'fecha_bcg' : 'fecha_hvb' => $request->fecha_aplicacion,
                    $request->tipo_vacuna === 'BCG' ? 'edad_bcg' : 'edad_hvb' => $edadDias,
                    $request->tipo_vacuna === 'BCG' ? 'estado_bcg' : 'estado_hvb' => $estado,
                ]
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
            
            // Calcular calidad de datos (porcentaje de niños con datos completos)
            $ninosCompletos = 0;
            $ninos = Nino::where('establecimiento', $establecimiento)
                ->with(['datosExtra', 'madre'])
                ->get();
            
            foreach ($ninos as $nino) {
                $completo = true;
                if (empty($nino->apellidos_nombres) || empty($nino->fecha_nacimiento) || empty($nino->genero)) {
                    $completo = false;
                }
                if (!$nino->datosExtra || empty($nino->datosExtra->red)) {
                    $completo = false;
                }
                if (!$nino->madre || empty($nino->madre->apellidos_nombres)) {
                    $completo = false;
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
}
