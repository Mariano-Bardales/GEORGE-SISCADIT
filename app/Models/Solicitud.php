<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'id_tipo_documento',
        'numero_documento',
        'codigo_red',
        'codigo_microred',
        'id_establecimiento',
        'motivo',
        'cargo',
        'celular',
        'correo',
        'accept_terms',
        'estado',
        'user_id',
    ];

    /**
     * Relación con el usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'accept_terms' => 'boolean',
    ];
}
