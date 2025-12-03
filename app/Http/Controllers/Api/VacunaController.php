<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\NinoRepository;
use App\Models\VacunaRn;
use App\Models\Nino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * Controlador API para Vacunas RN
 */
class VacunaController extends Controller
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
     * Obtener vacunas
     */
    public function index(Request $request)
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
            'fecha_bcg' => $bcgAplicada ? $fechaBCG->format('Y-m-d') : null,
            'fecha_hvb' => $hvbAplicada ? $fechaHVB->format('Y-m-d') : null,
            'es_ejemplo' => true,
        ];
    }

    /**
     * Registrar vacuna
     */
    public function store(Request $request)
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
            
            $vacunaData = [];
            if ($request->tipo_vacuna === 'BCG') {
                $vacunaData['fecha_bcg'] = $request->fecha_aplicacion;
            } else {
                $vacunaData['fecha_hvb'] = $request->fecha_aplicacion;
            }
            
            $vacuna = VacunaRn::updateOrCreate(
                ['id_niño' => $ninoIdReal],
                $vacunaData
            );

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
}

