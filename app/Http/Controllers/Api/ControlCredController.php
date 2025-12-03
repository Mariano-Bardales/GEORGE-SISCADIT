<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreControlCredRequest;
use App\Repositories\NinoRepository;
use App\Repositories\ControlRepository;
use App\Services\EdadService;
use App\Services\EstadoControlService;
use Illuminate\Http\Request;

/**
 * Controlador API para controles CRED
 */
class ControlCredController extends Controller
{
    protected $ninoRepository;
    protected $controlRepository;
    protected $edadService;
    protected $estadoService;

    public function __construct(
        NinoRepository $ninoRepository,
        ControlRepository $controlRepository,
        EdadService $edadService,
        EstadoControlService $estadoService
    ) {
        $this->ninoRepository = $ninoRepository;
        $this->controlRepository = $controlRepository;
        $this->edadService = $edadService;
        $this->estadoService = $estadoService;
    }

    /**
     * Obtener controles CRED de un niño
     */
    public function index(Request $request)
    {
        $ninoId = $request->query('nino_id');
        $mes = $request->query('mes');

        if (!$ninoId) {
            return response()->json([
                'success' => false,
                'message' => 'nino_id es requerido'
            ], 400);
        }

        try {
            $nino = $this->ninoRepository->findByIdOrFail($ninoId);
            
            $query = $this->controlRepository->getCredByNino($ninoId);
            
            if ($mes) {
                $query = $query->where('numero_control', $mes);
            }

            $controles = $query->map(function($control) use ($nino) {
                $edadDias = null;
                $estado = 'SEGUIMIENTO';

                if ($nino->fecha_nacimiento && $control->fecha) {
                    $edadDias = $this->edadService->calcularEdadEnDias(
                        $nino->fecha_nacimiento,
                        $control->fecha
                    );
                    
                    if ($edadDias !== null) {
                        $estado = $this->estadoService->determinarEstado(
                            $control->numero_control,
                            $edadDias,
                            'cred'
                        );
                    }
                }

                return [
                    'id' => $control->id_cred ?? $control->id,
                    'id_niño' => $control->id_niño,
                    'numero_control' => $control->numero_control,
                    'fecha' => $control->fecha ? $control->fecha->format('Y-m-d') : null,
                    'edad_dias' => $edadDias,
                    'estado' => $estado,
                    'es_ejemplo' => false,
                ];
            });

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
                'message' => 'Error al obtener controles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Registrar control CRED
     */
    public function store(StoreControlCredRequest $request, $id = null)
    {
        try {
            $nino = $this->ninoRepository->findByIdOrFail($request->nino_id);
            
            $edadDias = $this->edadService->calcularEdadEnDias(
                $nino->fecha_nacimiento,
                $request->fecha_control
            );

            $estado = $this->estadoService->determinarEstado(
                $request->mes,
                $edadDias,
                'cred'
            );

            $data = [
                'id_niño' => $this->ninoRepository->getRealId($nino),
                'numero_control' => $request->mes,
                'fecha' => $request->fecha_control,
            ];

            if ($id) {
                $control = $this->controlRepository->findCredById($id);
                if (!$control) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Control no encontrado'
                    ], 404);
                }
                
                $this->controlRepository->updateCred($control, $data);
            } else {
                $control = $this->controlRepository->createCred($data);
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
}

