<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\NinoRepository;
use App\Models\VisitaDomiciliaria;
use App\Models\Nino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * Controlador API para Visitas Domiciliarias
 */
class VisitaController extends Controller
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
     * Obtener visitas
     */
    public function index(Request $request)
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
            '28d' => ['dias' => 28, 'descripcion' => 'Visita domiciliaria entre 28 a 30 días de vida'],
            '60-150d' => ['dias' => 105, 'descripcion' => 'Visita domiciliaria entre 60 a 150 días'],
            '151-240d' => ['dias' => 195, 'descripcion' => 'Visita domiciliaria entre 151 a 240 días'],
            '241-330d' => ['dias' => 285, 'descripcion' => 'Visita domiciliaria entre 241 a 330 días'],
        ];
        
        $seed = $ninoIdReal % 100;
        $numeroVisita = 1;
        
        foreach ($periodos as $periodo => $info) {
            if ($edadDias >= $info['dias'] - 15) {
                $fechaVisita = $fechaNacimiento->copy()->addDays($info['dias'] + (($seed + $numeroVisita) % 7) - 3);
                
                $visitasEjemplo->push([
                    'id' => null,
                    'id_niño' => $ninoIdReal,
                    'numero_control' => $numeroVisita,
                    'fecha_visita' => $fechaVisita->format('Y-m-d'),
                    'estado' => ($edadDias >= $info['dias']) ? 'cumple' : 'pendiente',
                    'es_ejemplo' => true,
                ]);
                $numeroVisita++;
            }
        }
        
        return $visitasEjemplo;
    }

    /**
     * Registrar visita
     */
    public function store(Request $request)
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
}

