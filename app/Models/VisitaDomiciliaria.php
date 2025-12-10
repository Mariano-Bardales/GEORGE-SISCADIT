<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitaDomiciliaria extends Model
{
    use HasFactory;

    protected $table = 'visita_domiciliarias';
    
    protected $primaryKey = 'id_visita';
    
    public $incrementing = true;
    
    // Deshabilitar timestamps - campos eliminados de la base de datos
    public $timestamps = false;

    protected $fillable = [
        'id_niño',
        'control_de_visita', // 1, 2, 3, 4 (con rangos: 1=28d, 2=60-150d, 3=180-240d, 4=270-330d)
        'fecha_visita',
        // grupo_visita eliminado - reemplazado por control_de_visita
        // numero_visitas eliminado - reemplazado por control_de_visita
    ];

    protected $casts = [
        'fecha_visita' => 'date',
    ];

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'id_niño');
    }
}
