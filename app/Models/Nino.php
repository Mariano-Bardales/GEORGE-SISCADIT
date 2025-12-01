<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nino extends Model
{
    use HasFactory;

    protected $table = 'niños';
    
    protected $primaryKey = 'id_niño';
    
    public $incrementing = true;
    
    // Deshabilitar timestamps porque la tabla no tiene created_at y updated_at
    public $timestamps = false;

    protected $fillable = [
        'establecimiento',
        'tipo_doc',
        'numero_doc',
        'apellidos_nombres',
        'fecha_nacimiento',
        'genero',
        'edad_meses',
        'edad_dias',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    /**
     * Relación con la madre
     * La madre tiene el id_niño, así que usamos hasOne
     */
    public function madre()
    {
        return $this->hasOne(Madre::class, 'id_niño', 'id_niño');
    }

    public function datosExtra()
    {
        return $this->hasOne(DatosExtra::class, 'id_niño', 'id_niño');
    }

    public function recienNacido()
    {
        return $this->hasOne(RecienNacido::class, 'id_niño', 'id_niño');
    }

    public function vacunaRn()
    {
        return $this->hasOne(VacunaRn::class, 'id_niño', 'id_niño');
    }

    public function tamizajeNeonatal()
    {
        return $this->hasOne(TamizajeNeonatal::class, 'id_niño', 'id_niño');
    }

    public function controlesRn()
    {
        return $this->hasMany(ControlRn::class, 'id_niño', 'id_niño');
    }

    public function controlesMenor1()
    {
        return $this->hasMany(ControlMenor1::class, 'id_niño', 'id_niño');
    }

    public function visitasDomiciliarias()
    {
        return $this->hasMany(VisitaDomiciliaria::class, 'id_niño', 'id_niño');
    }
}
