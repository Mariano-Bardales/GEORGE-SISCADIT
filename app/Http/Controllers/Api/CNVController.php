<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\NinoRepository;
use App\Models\RecienNacido;
use App\Models\Nino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * Controlador API para CNV (Carné de Nacido Vivo)
 */
class CNVController extends Controller
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
        return Nino::findOrFail($id);
    }

    /**
     * Obtener CNV
     */
    public function index(Request $request)
    {
        $ninoId = $request->query('nino_id');
        if ($ninoId) {
            try {
                // Buscar el niño primero para obtener el id correcto
                $nino = $this->findNino($ninoId);
                $ninoIdReal = $this->getNinoId($nino);
                $cnv = RecienNacido::where('id_niño', $ninoIdReal)->first();
                
                // Si no hay CNV real, generar datos de ejemplo
                if (!$cnv) {
                    $cnv = $this->generarDatosEjemploCNV($nino, $ninoIdReal);
                }
            } catch (\Exception $e) {
                $cnv = null;
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
        
        // Clasificación
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

    /**
     * Registrar CNV
     */
    public function store(Request $request)
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
                'peso' => (int)$request->peso_nacer, // Convertir a entero (gramos)
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
}

