<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TamizajeNeonatal extends Model
{
    use HasFactory;

    protected $table = 'tamizaje_neonatal';
    
    protected $primaryKey = 'id_tamizaje';
    
    public $incrementing = true;
    
    // Deshabilitar timestamps porque la tabla no tiene created_at y updated_at
    public $timestamps = false;

    protected $fillable = [
        'id_niño',
        'fecha_29_dias',
        'fecha_tam_neo',
        'edad_tam_neo',
        'galen_fecha_tam_feo',
        'galen_dias_tam_feo',
        'cumple_tam_neo',
    ];

    protected $casts = [
        'fecha_29_dias' => 'date',
        'fecha_tam_neo' => 'date',
        'galen_fecha_tam_feo' => 'date',
    ];

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'id_niño');
    }
}
