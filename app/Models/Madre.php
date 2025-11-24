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
        'dni',
        'apellidos_nombres',
        'celular',
        'domicilio',
        'referencia_direccion',
    ];

    public function ninos()
    {
        return $this->hasMany(Nino::class, 'id_madre', 'id_madre');
    }
}
