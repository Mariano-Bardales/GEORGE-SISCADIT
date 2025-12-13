<?php

namespace App\Http\Controllers;

use App\Models\Nino;
use App\Models\Madre;
use App\Models\DatosExtra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ControlCredController extends Controller
{
    /**
     * Display the controles CRED page.
     */
    public function index()
    {
        return view('dashboard.controles-cred');
    }

    /**
     * Mostrar formulario independiente para registrar CRED mensual
     */
    public function formCredMensual(Request $request)
    {
        $ninoId = $request->query('nino_id');
        $mes = (int) $request->query('mes');
        $controlId = $request->query('control_id'); // Para editar

        if (!$ninoId || $mes < 1 || $mes > 11) {
            return redirect()->route('controles-cred')
                ->with('error', 'Parámetros inválidos para registrar CRED mensual.');
        }

        $nino = Nino::findOrFail($ninoId);

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

        $rango = $rangos[$mes] ?? null;

        // Buscar control existente si se está editando
        $control = null;
        $ninoIdReal = $nino->id;
        
        if ($controlId) {
            $control = DB::table('controles_menor1')
                ->where('id_cred', $controlId)
                ->where('id_niño', $ninoIdReal)
                ->where(function($query) use ($mes) {
                    $query->where('mes', $mes)
                          ->orWhere('numero_control', $mes);
                })
                ->first();
        } else {
            // Buscar por mes si no hay ID específico
            $control = DB::table('controles_menor1')
                ->where('id_niño', $ninoIdReal)
                ->where(function($query) use ($mes) {
                    $query->where('mes', $mes)
                          ->orWhere('numero_control', $mes);
                })
                ->first();
        }

        return view('controles.registrar-cred-mensual', [
            'nino' => $nino,
            'mes' => $mes,
            'rango' => $rango,
            'control' => $control,
            'fechaNacimiento' => $nino->fecha_nacimiento ? \Carbon\Carbon::parse($nino->fecha_nacimiento)->format('Y-m-d') : null,
        ]);
    }

    /**
     * Mostrar formulario para registrar Control Recién Nacido
     */
    public function formRecienNacido(Request $request)
    {
        $ninoId = $request->query('nino_id');
        $numeroControl = (int) $request->query('numero_control');

        if (!$ninoId || $numeroControl < 1 || $numeroControl > 4) {
            return redirect()->route('controles-cred')
                ->with('error', 'Parámetros inválidos para registrar control Recién Nacido.');
        }

        $nino = Nino::findOrFail($ninoId);

        $rangos = [
            1 => ['min' => 2, 'max' => 6],
            2 => ['min' => 7, 'max' => 13],
            3 => ['min' => 14, 'max' => 20],
            4 => ['min' => 21, 'max' => 28],
        ];

        $rango = $rangos[$numeroControl] ?? null;

        // Buscar control existente
        $control = DB::table('controles_rn')
            ->where('id_niño', $nino->id)
            ->where('numero_control', $numeroControl)
            ->first();

        return view('controles.form-recien-nacido', [
            'nino' => $nino,
            'numeroControl' => $numeroControl,
            'rango' => $rango,
            'control' => $control,
            'fechaNacimiento' => $nino->fecha_nacimiento ? \Carbon\Carbon::parse($nino->fecha_nacimiento)->format('Y-m-d') : null,
        ]);
    }

    /**
     * Mostrar formulario para registrar Tamizaje Neonatal
     */
    public function formTamizaje(Request $request)
    {
        $ninoId = $request->query('nino_id');

        if (!$ninoId) {
            return redirect()->route('controles-cred')
                ->with('error', 'Parámetros inválidos para registrar Tamizaje.');
        }

        $nino = Nino::findOrFail($ninoId);

        // Buscar tamizaje existente
        $tamizaje = DB::table('tamizaje_neonatal')
            ->where('id_niño', $nino->id)
            ->first();

        return view('controles.form-tamizaje', [
            'nino' => $nino,
            'tamizaje' => $tamizaje,
        ]);
    }

    /**
     * Mostrar formulario para registrar CNV
     */
    public function formCNV(Request $request)
    {
        $ninoId = $request->query('nino_id');

        if (!$ninoId) {
            return redirect()->route('controles-cred')
                ->with('error', 'Parámetros inválidos para registrar CNV.');
        }

        $nino = Nino::findOrFail($ninoId);

        // Buscar CNV existente (está en la tabla recien_nacidos)
        $cnv = DB::table('recien_nacidos')
            ->where('id_niño', $nino->id)
            ->first();

        return view('controles.form-cnv', [
            'nino' => $nino,
            'cnv' => $cnv,
        ]);
    }

    /**
     * Mostrar formulario para registrar Visita Domiciliaria
     */
    public function formVisita(Request $request)
    {
        $ninoId = $request->query('nino_id');
        $periodo = $request->query('periodo');

        if (!$ninoId || !$periodo) {
            return redirect()->route('controles-cred')
                ->with('error', 'Parámetros inválidos para registrar Visita Domiciliaria.');
        }

        $nino = Nino::findOrFail($ninoId);

        $periodos = [
            '28d' => ['texto' => '28 a 30 días', 'min' => 28, 'max' => 30],
            '2-5m' => ['texto' => '2-5 meses', 'min' => 60, 'max' => 150],
            '6-8m' => ['texto' => '6-8 meses', 'min' => 180, 'max' => 240],
            '9-11m' => ['texto' => '9-11 meses', 'min' => 270, 'max' => 330],
        ];

        $periodoData = $periodos[$periodo] ?? null;
        if (!$periodoData) {
            return redirect()->route('controles-cred')
                ->with('error', 'Período inválido.');
        }

        // Buscar visita existente
        $visita = DB::table('visitas_domiciliarias')
            ->where('id_niño', $nino->id)
            ->where('grupo_visita', $periodo)
            ->first();

        return view('controles.form-visita', [
            'nino' => $nino,
            'periodo' => $periodo,
            'periodoTexto' => $periodoData['texto'],
            'rango' => ['min' => $periodoData['min'], 'max' => $periodoData['max']],
            'visita' => $visita,
        ]);
    }

    /**
     * Mostrar formulario para registrar Vacuna RN
     */
    public function formVacuna(Request $request)
    {
        $ninoId = $request->query('nino_id');
        $tipoVacuna = $request->query('tipo');

        if (!$ninoId || !in_array($tipoVacuna, ['BCG', 'HVB'])) {
            return redirect()->route('controles-cred')
                ->with('error', 'Parámetros inválidos para registrar Vacuna.');
        }

        $nino = Nino::findOrFail($ninoId);

        // Buscar vacuna existente
        $vacuna = DB::table('vacuna_rn')
            ->where('id_niño', $nino->id)
            ->first();

        $fechaVacuna = null;
        $edadVacuna = null;

        if ($vacuna) {
            if ($tipoVacuna === 'BCG') {
                $fechaVacuna = $vacuna->fecha_bcg ?? null;
                $edadVacuna = $vacuna->edad_bcg ?? null;
            } else {
                $fechaVacuna = $vacuna->fecha_hvb ?? null;
                $edadVacuna = $vacuna->edad_hvb ?? null;
            }
        }

        return view('controles.form-vacuna', [
            'nino' => $nino,
            'tipoVacuna' => $tipoVacuna,
            'fechaVacuna' => $fechaVacuna,
            'edadVacuna' => $edadVacuna,
        ]);
    }

    /**
     * Store a newly created child in storage.
     */
    public function store(Request $request)
    {
        // Validate the request - TODOS LOS CAMPOS SON REQUERIDOS
        $validator = Validator::make($request->all(), [
            // Datos del Niño
            'Nombre_Establecimiento' => 'required|string|max:150',
            'Id_Tipo_Documento' => 'required|integer|between:1,6',
            'Numero_Documento' => 'required|string|max:20',
            'Apellidos_Nombres' => 'required|string|max:150',
            'Fecha_Nacimiento' => 'required|date|before_or_equal:today',
            'Genero' => 'required|in:M,F',
            
            // Datos Extras
            'Codigo_Red' => 'required',
            'Codigo_Microred' => 'required',
            'Id_Establecimiento' => 'required',
            'Distrito' => 'required|string|max:100',
            'Provincia' => 'required|string|max:100',
            'Departamento' => 'required|string|max:100',
            'Seguro' => 'required|in:SIS,ESSALUD,PRIVADO,SIN_SEGURO',
            'Programa' => 'required|in:CRED,PIANE,PIM,JUNTOS,PAIS',
            
            // Datos de la Madre
            'DNI_Madre' => 'required|string|max:20',
            'Apellidos_Nombres_Madre' => 'required|string|max:150',
            'Celular_Madre' => 'required|string|max:20',
            'Domicilio_Madre' => 'required|string|max:255',
            'Referencia_Direccion' => 'required|string|max:255',
        ], [
            // Datos del Niño
            'Nombre_Establecimiento.required' => 'El nombre del establecimiento es obligatorio',
            'Id_Tipo_Documento.required' => 'El tipo de documento es obligatorio',
            'Numero_Documento.required' => 'El número de documento es obligatorio',
            'Apellidos_Nombres.required' => 'Los apellidos y nombres son obligatorios',
            'Fecha_Nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'Fecha_Nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
            'Fecha_Nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser una fecha futura',
            'Genero.required' => 'El género es obligatorio',
            'Genero.in' => 'El género debe ser Masculino (M) o Femenino (F)',
            
            // Datos Extras
            'Codigo_Red.required' => 'Debe seleccionar una red',
            'Codigo_Microred.required' => 'Debe seleccionar una microred',
            'Id_Establecimiento.required' => 'Debe seleccionar un establecimiento',
            'Distrito.required' => 'El distrito es obligatorio',
            'Provincia.required' => 'La provincia es obligatoria',
            'Departamento.required' => 'El departamento es obligatorio',
            'Seguro.required' => 'Debe seleccionar un seguro',
            'Seguro.in' => 'El seguro seleccionado no es válido',
            'Programa.required' => 'Debe seleccionar un programa',
            'Programa.in' => 'El programa seleccionado no es válido',
            
            // Datos de la Madre
            'DNI_Madre.required' => 'El DNI de la madre es obligatorio',
            'Apellidos_Nombres_Madre.required' => 'Los apellidos y nombres de la madre son obligatorios',
            'Celular_Madre.required' => 'El celular de la madre es obligatorio',
            'Domicilio_Madre.required' => 'El domicilio de la madre es obligatorio',
            'Referencia_Direccion.required' => 'La referencia de dirección es obligatoria',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, complete todos los campos obligatorios correctamente.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Map tipo documento
            $tipoDocMap = [
                1 => 'DNI',
                2 => 'CE',
                3 => 'PASS',
                4 => 'DIE',
                5 => 'S/ DOCUMENTO',
                6 => 'CNV'
            ];
            $tipoDoc = $tipoDocMap[$request->Id_Tipo_Documento] ?? 'S/ DOCUMENTO';

            // Obtener el nombre del establecimiento
            $nombreEstablecimiento = $request->Nombre_Establecimiento;
            
            if (empty($nombreEstablecimiento) && $request->filled('Id_Establecimiento')) {
                $nombreEstablecimiento = 'Establecimiento ID: ' . $request->Id_Establecimiento;
            }

            // PRIMERO: Crear el niño (tabla principal)
            $nino = Nino::create([
                'establecimiento' => $nombreEstablecimiento,
                'tipo_doc' => $tipoDoc,
                'numero_doc' => $request->Numero_Documento,
                'apellidos_nombres' => $request->Apellidos_Nombres,
                'fecha_nacimiento' => $request->Fecha_Nacimiento,
                'genero' => $request->Genero,
            ]);
            
            // Obtener el ID del niño creado
            $ninoId = $nino->id;

            // SEGUNDO: Crear la madre con referencia al niño (todos los campos son requeridos)
            Madre::create([
                'id_niño' => $ninoId,
                'dni' => $request->DNI_Madre,
                'apellidos_nombres' => $request->Apellidos_Nombres_Madre,
                'celular' => $request->Celular_Madre,
                'domicilio' => $request->Domicilio_Madre,
                'referencia_direccion' => $request->Referencia_Direccion,
            ]);

            // Obtener el nombre de la red y microred seleccionadas
            $nombreRed = null;
            $nombreMicrored = null;
            
            if ($request->filled('Codigo_Red')) {
                // Mapear código de red a nombre (basado en las opciones del formulario)
                $redesMap = [
                    '1' => 'AGUAYTIA',
                    '2' => 'ATALAYA',
                    '3' => 'BAP-CURARAY',
                    '4' => 'CORONEL PORTILLO',
                    '5' => 'ESSALUD',
                    '6' => 'FEDERICO BASADRE - YARINACOCHA',
                    '7' => 'HOSPITAL AMAZONICO - YARINACOCHA',
                    '8' => 'HOSPITAL REGIONAL DE PUCALLPA',
                    '9' => 'NO PERTENECE A NINGUNA RED',
                ];
                $nombreRed = $redesMap[$request->Codigo_Red] ?? $request->Codigo_Red;
            }
            
            // La microred viene como texto del select, no como código
            $nombreMicrored = $request->Codigo_Microred ?? null;

            // Obtener el nombre del establecimiento para eess_nacimiento
            $eessNacimiento = $request->Nombre_Establecimiento ?? $nombreEstablecimiento;
            
            // Create datos_extras (todos los campos son requeridos)
            DatosExtra::create([
                'id_niño' => $ninoId,
                'red' => $nombreRed,
                'microred' => $nombreMicrored,
                'eess_nacimiento' => $eessNacimiento,
                'distrito' => $request->Distrito,
                'provincia' => $request->Provincia,
                'departamento' => $request->Departamento,
                'seguro' => $request->Seguro,
                'programa' => $request->Programa,
            ]);

            DB::commit();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Niño registrado exitosamente',
                    'nino_id' => $ninoId
                ], 200);
            }

            return redirect()->route('controles-cred')->with('success', 'Niño registrado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log del error para debugging
            \Log::error('Error al registrar niño', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar el niño: ' . $e->getMessage(),
                    'exception' => config('app.debug') ? $e->getTraceAsString() : null
                ], 500);
            }
            
            return back()->with('error', 'Error al registrar el niño. Por favor, intente nuevamente.')->withInput();
        }
    }
}
