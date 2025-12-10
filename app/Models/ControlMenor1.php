<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlMenor1 extends Model
{
    use HasFactory;

    protected $table = 'control_menor1s';
    
    protected $primaryKey = 'id';
    
    public $incrementing = true;
    
    // Deshabilitar timestamps porque la tabla no tiene created_at y updated_at
    public $timestamps = false;

    protected $fillable = [
        'id', // Permitir asignar ID personalizado
        'id_niño',
        'numero_control',
        'fecha',
        // edad eliminado - se calcula dinámicamente desde fecha_nacimiento y fecha del control
        // estado eliminado - se calcula dinámicamente con EstadoControlService
        // estado_cred_once eliminado - campo innecesario
        // estado_cred_final eliminado - campo innecesario
        // peso, talla, perimetro_cefalico eliminados - campos médicos innecesarios
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'id_niño', 'id');
    }
}
