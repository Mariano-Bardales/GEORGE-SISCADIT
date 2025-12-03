<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\NinoRepository;
use App\Models\TamizajeNeonatal;
use App\Models\Nino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * Controlador API para Tamizaje Neonatal
 */
class TamizajeController extends Controller
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
     * Obtener tamizaje
     */
    public function index(Request $request)
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
            'fecha_tam_neo' => $fechaTamizaje->format('Y-m-d'),
            'tipo_tamizaje' => ($seed % 2 == 0) ? 'Tamizaje Neonatal' : 'Tamizaje Galen',
            'resultado' => ($seed % 3 == 0) ? 'Normal' : (($seed % 3 == 1) ? 'Pendiente' : 'Anormal'),
            'estado' => ($seed % 3 == 0) ? 'cumple' : 'seguimiento',
            'es_ejemplo' => true,
        ];
    }

    /**
     * Registrar tamizaje
     */
    public function store(Request $request)
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
                    'numero_control' => 1, // Por defecto
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
}

