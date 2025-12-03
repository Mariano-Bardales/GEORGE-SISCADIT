<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlRn extends Model
{
    use HasFactory;

    protected $table = 'controles_rn';
    
    protected $primaryKey = 'id_crn';
    
    public $incrementing = true;
    
    // Deshabilitar timestamps porque la tabla no tiene created_at y updated_at
    public $timestamps = false;

    protected $fillable = [
        'id_crn', // Permitir asignar ID personalizado
        'id_niño',
        'numero_control',
        'fecha',
        'edad',
        'estado',
        'peso',
        'talla',
        'perimetro_cefalico',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'id_niño');
    }
}
