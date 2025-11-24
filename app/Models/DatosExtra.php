<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosExtra extends Model
{
    use HasFactory;

    protected $table = 'datos_extra';
    
    protected $primaryKey = 'id_extra';
    
    public $incrementing = true;

    // Deshabilitar timestamps porque la tabla no tiene created_at y updated_at
    public $timestamps = false;

    protected $fillable = [
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
        return $this->belongsTo(Nino::class, 'id_niño');
    }
}
