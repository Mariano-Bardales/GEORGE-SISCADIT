<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitaDomiciliaria extends Model
{
    use HasFactory;

    protected $table = 'visitas_domiciliarias';
    
    protected $primaryKey = 'id_visita';
    
    public $incrementing = true;
    
    // Deshabilitar timestamps porque la tabla no tiene created_at y updated_at
    public $timestamps = false;

    protected $fillable = [
        'id_niño',
        'grupo_visita',
        'fecha_visita',
        'numero_visitas',
    ];

    protected $casts = [
        'fecha_visita' => 'date',
    ];

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'id_niño');
    }
}
