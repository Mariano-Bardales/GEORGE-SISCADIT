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
     * Store a newly created child in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'Id_Tipo_Documento' => 'required|integer|between:1,6',
            'Numero_Documento' => 'required|string|max:20',
            'Apellidos_Nombres' => 'required|string|max:150',
            'Fecha_Nacimiento' => 'required|date',
            'Genero' => 'required|in:M,F',
            'Codigo_Red' => 'required',
            'Codigo_Microred' => 'required',
            'Id_Establecimiento' => 'required',
        ], [
            'Id_Tipo_Documento.required' => 'El tipo de documento es obligatorio',
            'Numero_Documento.required' => 'El número de documento es obligatorio',
            'Apellidos_Nombres.required' => 'Los apellidos y nombres son obligatorios',
            'Fecha_Nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'Fecha_Nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
            'Genero.required' => 'El género es obligatorio',
            'Genero.in' => 'El género debe ser Masculino (M) o Femenino (F)',
            'Codigo_Red.required' => 'Debe seleccionar una red',
            'Codigo_Microred.required' => 'Debe seleccionar una microred',
            'Id_Establecimiento.required' => 'Debe seleccionar un establecimiento',
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

            // Handle mother data
            $madre = null;
            if ($request->filled('DNI_Madre') || $request->filled('Apellidos_Nombres_Madre')) {
                // Try to find existing mother by DNI
                if ($request->filled('DNI_Madre')) {
                    $madre = Madre::where('dni', $request->DNI_Madre)->first();
                }

                // If not found, create new mother
                if (!$madre) {
                    $madre = Madre::create([
                        'dni' => $request->DNI_Madre ?? null,
                        'apellidos_nombres' => $request->Apellidos_Nombres_Madre ?? 'Sin especificar',
                        'celular' => $request->Celular_Madre ?? null,
                        'domicilio' => $request->Domicilio_Madre ?? null,
                        'referencia_direccion' => $request->Referencia_Direccion ?? null,
                    ]);
                } else {
                    // Update existing mother if new data is provided
                    $updateData = [];
                    if ($request->filled('Apellidos_Nombres_Madre')) {
                        $updateData['apellidos_nombres'] = $request->Apellidos_Nombres_Madre;
                    }
                    if ($request->filled('Celular_Madre')) {
                        $updateData['celular'] = $request->Celular_Madre;
                    }
                    if ($request->filled('Domicilio_Madre')) {
                        $updateData['domicilio'] = $request->Domicilio_Madre;
                    }
                    if ($request->filled('Referencia_Direccion')) {
                        $updateData['referencia_direccion'] = $request->Referencia_Direccion;
                    }
                    if (!empty($updateData)) {
                        $madre->update($updateData);
                    }
                }
            } else {
                // Create a default mother if no data is provided
                $madre = Madre::create([
                    'dni' => null,
                    'apellidos_nombres' => 'Sin especificar',
                    'celular' => null,
                    'domicilio' => null,
                    'referencia_direccion' => null,
                ]);
            }

            // Calculate age from fecha_nacimiento
            $fechaNacimiento = Carbon::parse($request->Fecha_Nacimiento);
            $now = Carbon::now();
            $edadMeses = $fechaNacimiento->diffInMonths($now);
            $edadDias = $fechaNacimiento->diffInDays($now);

            // Obtener el nombre del establecimiento
            // Si se seleccionó desde el select, obtener el texto del establecimiento seleccionado
            $nombreEstablecimiento = $request->Nombre_Establecimiento;
            
            // Si no hay nombre pero hay ID de establecimiento, intentar obtener el nombre
            if (empty($nombreEstablecimiento) && $request->filled('Id_Establecimiento')) {
                // El nombre debería venir del formulario, pero si no, usar el ID como fallback
                $nombreEstablecimiento = 'Establecimiento ID: ' . $request->Id_Establecimiento;
            }

            // Create child (nino)
            // Obtener el ID correcto de la madre (puede ser id_madre o id)
            $madreId = $madre->id_madre ?? $madre->id;
            
            $nino = Nino::create([
                'id_madre' => $madreId,
                'establecimiento' => $nombreEstablecimiento,
                'tipo_doc' => $tipoDoc,
                'numero_doc' => $request->Numero_Documento,
                'apellidos_nombres' => $request->Apellidos_Nombres,
                'fecha_nacimiento' => $request->Fecha_Nacimiento,
                'genero' => $request->Genero,
                'edad_meses' => $edadMeses,
                'edad_dias' => $edadDias,
                'datos_extras' => null,
            ]);
            
            // Obtener el ID real del niño creado (puede ser id_niño)
            $ninoId = $nino->id_niño ?? $nino->id;

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

            // Create datos_extras
            DatosExtra::create([
                'id_niño' => $ninoId,
                'red' => $nombreRed,
                'microred' => $nombreMicrored,
                'eess_nacimiento' => $request->Id_Establecimiento ?? null,
                'distrito' => $request->Distrito ?? null,
                'provincia' => $request->Provincia ?? null,
                'departamento' => $request->Departamento ?? null,
                'seguro' => $request->Seguro ?? null,
                'programa' => $request->Programa ?? null,
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
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar el niño: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error al registrar el niño. Por favor, intente nuevamente.')->withInput();
        }
    }
}

