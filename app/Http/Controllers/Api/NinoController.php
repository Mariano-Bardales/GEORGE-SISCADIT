<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\NinoRepository;
use App\Models\Nino;
use App\Models\ControlRn;
use App\Models\ControlMenor1;
use App\Models\TamizajeNeonatal;
use App\Models\VacunaRn;
use App\Models\RecienNacido;
use App\Models\VisitaDomiciliaria;
use App\Models\DatosExtra;
use App\Models\Madre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Controlador API para Niños
 */
class NinoController extends Controller
{
    protected $ninoRepository;

    public function __construct(NinoRepository $ninoRepository)
    {
        $this->ninoRepository = $ninoRepository;
    }

    /**
     * Helper para obtener el ID correcto del niño
     */
    private function getNinoId($nino)
    {
        return $nino->id_niño ?? $nino->id ?? null;
    }

    /**
     * Helper para buscar un niño por ID
     */
    private function findNino($id)
    {
        return Nino::where('id_niño', $id)->firstOrFail();
    }

    /**
     * Obtener lista de niños con paginación
     */
    public function index(Request $request)
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
            
            // Ordenar por id (más recientes primero)
            $query->orderBy('id', 'desc');
            
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
                    'id_niño' => $this->getNinoId($nino),
                    'establecimiento' => $nino->establecimiento ?? '-',
                    'tipo_doc' => $nino->tipo_doc ?? '-',
                    'id_tipo_documento' => $idTipoDoc,
                    'numero_doc' => $nino->numero_doc ?? '-',
                    'numero_documento' => $nino->numero_doc ?? '-',
                    'apellidos_nombres' => $nino->apellidos_nombres ?? '-',
                    'fecha_nacimiento' => $nino->fecha_nacimiento ? $nino->fecha_nacimiento->format('Y-m-d') : null,
                    'genero' => $nino->genero ?? 'M',
                    'edad_meses' => $nino->edad_meses ?? null,
                    'edad_dias' => $nino->edad_dias ?? null,
                    'datos_extras' => $nino->datos_extras ?? null,
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

    /**
     * Obtener datos extras de un niño
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
     * Obtener todos los controles de un niño
     */
    public function controles($ninoId)
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
                
                // Calcular edad en días
                $edadDias = null;
                $estadoRecalculado = 'SEGUIMIENTO';
                
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
                    
                    if ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) {
                        $estadoRecalculado = 'CUMPLE';
                    } else if ($edadDias < $rango['min']) {
                        $estadoRecalculado = 'SEGUIMIENTO';
                    } else {
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
                    'edad' => $edadDias,
                    'edad_dias' => $edadDias,
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
                    'fecha_tamizaje' => $tamizaje->fecha_tam_neo ? Carbon::parse($tamizaje->fecha_tam_neo)->format('Y-m-d') : null,
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
                    'edad_gestacional' => $cnv->edad_gestacional ?? null,
                    'clasificacion' => $cnv->clasificacion ?? null,
                    'es_ejemplo' => false,
                ];
            }
            
            // Formatear visitas
            $visitasFormateadas = $visitas->map(function($visita) use ($nino) {
                // Calcular grupo_visita dinámicamente basado en la edad del niño
                $fechaNacimiento = $nino->fecha_nacimiento ? Carbon::parse($nino->fecha_nacimiento) : null;
                $fechaVisita = $visita->fecha_visita ? Carbon::parse($visita->fecha_visita) : null;
                $edadDias = null;
                $periodo = null;
                
                if ($fechaNacimiento && $fechaVisita) {
                    $edadDias = $fechaNacimiento->diffInDays($fechaVisita);
                    
                    if ($edadDias >= 28 && $edadDias <= 35) {
                        $periodo = '28d';
                    } else if ($edadDias >= 60 && $edadDias <= 150) {
                        $periodo = '60-150d';
                    } else if ($edadDias >= 151 && $edadDias <= 240) {
                        $periodo = '151-240d';
                    } else if ($edadDias >= 241 && $edadDias <= 330) {
                        $periodo = '241-330d';
                    }
                }
                
                return [
                    'id' => $visita->id_visita ?? $visita->id,
                    'id_niño' => $visita->id_niño,
                    'numero_control' => $visita->numero_control ?? null,
                    'fecha_visita' => $visita->fecha_visita ? Carbon::parse($visita->fecha_visita)->format('Y-m-d') : null,
                    'periodo' => $periodo,
                    'grupo_visita' => $periodo,
                    'es_ejemplo' => false,
                ];
            });
            
            // Formatear vacunas
            $vacunasFormateadas = null;
            if ($vacunas) {
                $vacunasFormateadas = [
                    'id' => $vacunas->id_vacuna ?? $vacunas->id,
                    'id_niño' => $vacunas->id_niño,
                    'numero_control' => $vacunas->numero_control ?? null,
                    'fecha_bcg' => $vacunas->fecha_bcg ? Carbon::parse($vacunas->fecha_bcg)->format('Y-m-d') : null,
                    'edad_bcg' => $vacunas->edad_bcg ?? null,
                    'fecha_hvb' => $vacunas->fecha_hvb ? Carbon::parse($vacunas->fecha_hvb)->format('Y-m-d') : null,
                    'edad_hvb' => $vacunas->edad_hvb ?? null,
                    'es_ejemplo' => false,
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'controles_rn' => $controlesRnFormateados,
                    'controles_cred' => $controlesCredFormateados,
                    'tamizaje' => $tamizajeFormateado,
                    'cnv' => $cnvFormateado,
                    'visitas' => $visitasFormateadas,
                    'vacunas' => $vacunasFormateadas,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener controles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un niño y todos sus datos relacionados
     */
    public function destroy($id)
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
}

