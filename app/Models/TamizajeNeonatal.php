<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TamizajeNeonatal extends Model
{
    use HasFactory;

    protected $table = 'tamizaje_neonatals';
    
    protected $primaryKey = 'id_tamizaje';
    
    public $incrementing = true;
    
    // Deshabilitar timestamps - campos eliminados de la base de datos
    public $timestamps = false;

    protected $fillable = [
        'id_niño',
        // fecha_29_dias eliminado - se calcula dinámicamente (fecha_nacimiento + 29 días)
        'fecha_tam_neo',
        // edad_tam_neo eliminado - se calcula dinámicamente desde fecha_nacimiento y fecha_tam_neo
        'galen_fecha_tam_feo',
        // galen_dias_tam_feo eliminado - se calcula dinámicamente desde fecha_nacimiento y galen_fecha_tam_feo
        // cumple_tam_neo eliminado - se calcula dinámicamente comparando fecha_tam_neo con fecha_nacimiento + 29 días
    ];

    protected $casts = [
        'fecha_tam_neo' => 'date',
        'galen_fecha_tam_feo' => 'date',
    ];

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'id_niño');
    }
}
