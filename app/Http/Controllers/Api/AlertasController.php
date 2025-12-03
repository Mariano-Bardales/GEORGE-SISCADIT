<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AlertasService;
use Illuminate\Http\Request;

/**
 * Controlador API para alertas
 */
class AlertasController extends Controller
{
    protected $alertasService;

    public function __construct(AlertasService $alertasService)
    {
        $this->alertasService = $alertasService;
    }

    /**
     * Obtener todas las alertas
     */
    public function index(Request $request)
    {
        try {
            $alertas = $this->alertasService->obtenerTodasLasAlertas();

            return response()->json([
                'success' => true,
                'data' => $alertas,
                'total' => count($alertas)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener alertas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener total de alertas
     */
    public function total()
    {
        try {
            $total = $this->alertasService->contarTotalAlertas();

            return response()->json([
                'success' => true,
                'total' => $total
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al contar alertas: ' . $e->getMessage(),
                'total' => 0
            ], 500);
        }
    }
}

