<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Madre extends Model
{
    use HasFactory;

    protected $table = 'madres';
    
    protected $primaryKey = 'id_madre';
    
    public $incrementing = true;

    // Deshabilitar timestamps porque la tabla no tiene created_at y updated_at
    public $timestamps = false;

    protected $fillable = [
        'id_madre', // Permitir asignar ID personalizado
        'id_niño',
        'dni',
        'apellidos_nombres',
        'celular',
        'domicilio',
        'referencia_direccion',
    ];

    /**
     * Relación con el niño
     * La madre pertenece a un niño (tiene id_niño)
     */
    public function nino()
    {
        return $this->belongsTo(Nino::class, 'id_niño', 'id_niño');
    }
}
