<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosExtra extends Model
{
    use HasFactory;

    protected $table = 'datos_extras';
    
    protected $primaryKey = 'id';
    
    public $incrementing = true;

    // Deshabilitar timestamps - campos eliminados de la base de datos
    public $timestamps = false;

    protected $fillable = [
        'id', // Permitir asignar ID personalizado
        'id_niño',
        'red',
        'microred',
        'eess_nacimiento',
        'distrito',
        'provincia',
        'departamento',
        'seguro',
        'programa',
    ];

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'id_niño', 'id');
    }
}
