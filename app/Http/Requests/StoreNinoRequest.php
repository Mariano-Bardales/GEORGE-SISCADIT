<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNinoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'establecimiento' => 'nullable|string|max:150',
            'tipo_doc' => 'nullable|string|max:10',
            'numero_doc' => 'nullable|string|max:20',
            'apellidos_nombres' => 'required|string|max:150',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'nullable|string|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'apellidos_nombres.required' => 'El nombre completo es obligatorio',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
        ];
    }
}

