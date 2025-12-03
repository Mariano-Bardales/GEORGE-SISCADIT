<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreControlRnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nino_id' => 'required|integer|exists:ninos,id_niño',
            'numero_control' => 'required|integer|between:1,4',
            'fecha_control' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'nino_id.required' => 'El ID del niño es obligatorio',
            'nino_id.exists' => 'El niño especificado no existe',
            'numero_control.required' => 'El número de control es obligatorio',
            'numero_control.between' => 'El número de control debe estar entre 1 y 4',
            'fecha_control.required' => 'La fecha del control es obligatoria',
            'fecha_control.date' => 'La fecha del control debe ser una fecha válida',
        ];
    }
}

