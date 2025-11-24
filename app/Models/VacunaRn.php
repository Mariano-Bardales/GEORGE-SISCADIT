<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacunaRn extends Model
{
    use HasFactory;

    protected $table = 'vacuna_rn';
    
    protected $primaryKey = 'id_vacuna';
    
    public $incrementing = true;
    
    // Deshabilitar timestamps porque la tabla no tiene created_at y updated_at
    public $timestamps = false;

    protected $fillable = [
        'id_niño',
        'fecha_bcg',
        'edad_bcg',
        'estado_bcg',
        'fecha_hvb',
        'edad_hvb',
        'estado_hvb',
        'cumple_BCG_HVB',
    ];

    protected $casts = [
        'fecha_bcg' => 'date',
        'fecha_hvb' => 'date',
    ];

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'id_niño');
    }
}
