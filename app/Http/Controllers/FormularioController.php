<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormularioController extends Controller
{
    /**
     * Mostrar el formulario de solicitud
     */
    public function show()
    {
        return view('formulario.solicitud');
    }

    /**
     * Procesar el envío del formulario
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Id_Tipo_Documento' => 'required|integer|in:1', // Solo DNI (valor 1)
            'Numero_Documento' => 'required|string|regex:/^[0-9]{8}$/', // Exactamente 8 dígitos
            'Codigo_Red' => 'required|integer|between:1,9',
            'Codigo_Microred' => 'required|string|max:255',
            'Id_Establecimiento' => 'required|string|max:255',
            'Motivo' => 'required|string|max:255',
            'Cargo' => 'required|string|max:255',
            'Celular' => 'required|string|regex:/^[0-9]{1,9}$/', // Máximo 9 dígitos
            'Correo' => 'required|email|max:255',
            'acceptTerms' => 'required|accepted',
        ], [
            'Id_Tipo_Documento.required' => 'Debe seleccionar un tipo de documento',
            'Id_Tipo_Documento.in' => 'Solo se acepta DNI como tipo de documento',
            'Numero_Documento.required' => 'El número de DNI es obligatorio',
            'Numero_Documento.regex' => 'El número de DNI debe tener exactamente 8 dígitos',
            'Codigo_Red.required' => 'Debe seleccionar una red',
            'Codigo_Microred.required' => 'Debe seleccionar una microred',
            'Id_Establecimiento.required' => 'Debe seleccionar un establecimiento',
            'Motivo.required' => 'El motivo es obligatorio',
            'Cargo.required' => 'El cargo es obligatorio',
            'Celular.required' => 'El celular es obligatorio',
            'Celular.regex' => 'El celular debe tener máximo 9 dígitos (solo números)',
            'Correo.required' => 'El correo electrónico es obligatorio',
            'Correo.email' => 'Debe ingresar un correo electrónico válido',
            'acceptTerms.required' => 'Debe aceptar la confidencialidad de datos',
            'acceptTerms.accepted' => 'Debe aceptar la confidencialidad de datos',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, complete todos los campos correctamente.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $solicitud = Solicitud::create([
                'id_tipo_documento' => $request->Id_Tipo_Documento,
                'numero_documento' => $request->Numero_Documento,
                'codigo_red' => $request->Codigo_Red,
                'codigo_microred' => $request->Codigo_Microred,
                'id_establecimiento' => $request->Id_Establecimiento,
                'motivo' => $request->Motivo,
                'cargo' => $request->Cargo,
                'celular' => $request->Celular,
                'correo' => $request->Correo,
                'accept_terms' => $request->has('acceptTerms'),
                'estado' => 'pendiente',
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Solicitud enviada correctamente',
                    'solicitud_id' => $solicitud->id
                ], 200);
            }

            return redirect()->route('formulario')->with('success', 'Solicitud enviada correctamente');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al procesar la solicitud. Por favor, intente nuevamente.'
                ], 500);
            }
            return back()->with('error', 'Error al procesar la solicitud. Por favor, intente nuevamente.')->withInput();
        }
    }
}
