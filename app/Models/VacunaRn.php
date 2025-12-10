<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacunaRn extends Model
{
    use HasFactory;

    protected $table = 'vacuna_rns';
    
    protected $primaryKey = 'id_vacuna';
    
    public $incrementing = true;
    
    // Deshabilitar timestamps - campos eliminados de la base de datos
    public $timestamps = false;

    protected $fillable = [
        'id_niño',
        'fecha_bcg',
        // edad_bcg eliminado - se calcula dinámicamente desde fecha_nacimiento y fecha_bcg
        // estado_bcg eliminado - se puede determinar por fecha_bcg (si existe = aplicada)
        'fecha_hvb',
        // edad_hvb eliminado - se calcula dinámicamente desde fecha_nacimiento y fecha_hvb
        // estado_hvb eliminado - se puede determinar por fecha_hvb (si existe = aplicada)
        // cumple_BCG_HVB eliminado - se calcula dinámicamente (ambas fechas existen)
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
