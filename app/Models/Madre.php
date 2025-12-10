<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Madre extends Model
{
    use HasFactory;

    protected $table = 'madres';
    
    protected $primaryKey = 'id';
    
    public $incrementing = true;

    // Deshabilitar timestamps - campos eliminados de la base de datos
    public $timestamps = false;

    protected $fillable = [
        'id', // Permitir asignar ID personalizado
        'id_niño',
        'dni',
        'apellidos_nombres',
        'celular',
        'domicilio',
        'referencia_direccion',
    ];

    /**
     * Relación con el niño
     * Una madre pertenece a un niño (madres.id_niño -> ninos.id)
     */
    public function nino()
    {
        return $this->belongsTo(Nino::class, 'id_niño', 'id');
    }
}
