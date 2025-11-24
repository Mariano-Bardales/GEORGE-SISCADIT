<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlMenor1 extends Model
{
    use HasFactory;

    protected $table = 'controles_menor1';
    
    protected $primaryKey = 'id_cred';
    
    public $incrementing = true;
    
    // Deshabilitar timestamps porque la tabla no tiene created_at y updated_at
    public $timestamps = false;

    protected $fillable = [
        'id_niño',
        'numero_control',
        'fecha',
        'edad',
        'estado',
        'estado_cred_once',
        'estado_cred_final',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'id_niño');
    }
}
