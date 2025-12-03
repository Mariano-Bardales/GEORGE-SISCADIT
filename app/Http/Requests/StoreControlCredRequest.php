<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreControlCredRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nino_id' => 'required|integer|exists:ninos,id_niño',
            'mes' => 'required|integer|between:1,11',
            'fecha_control' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'nino_id.required' => 'El ID del niño es obligatorio',
            'nino_id.exists' => 'El niño especificado no existe',
            'mes.required' => 'El número de mes es obligatorio',
            'mes.between' => 'El número de mes debe estar entre 1 y 11',
            'fecha_control.required' => 'La fecha del control es obligatoria',
            'fecha_control.date' => 'La fecha del control debe ser una fecha válida',
        ];
    }
}

