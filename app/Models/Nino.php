<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nino extends Model
{
    use HasFactory;

    protected $table = 'ninos';
    
    protected $primaryKey = 'id';
    
    public $incrementing = true;
    
    // Deshabilitar timestamps - campos eliminados de la base de datos
    public $timestamps = false;

    protected $fillable = [
        'id', // Permitir asignar ID personalizado
        'id_madre',
        'establecimiento',
        'tipo_doc',
        'numero_doc',
        'apellidos_nombres',
        'fecha_nacimiento',
        'genero',
        // edad_meses y edad_dias eliminados - se calculan dinámicamente con EdadService
        'datos_extras',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    /**
     * Relación con la madre por id_madre (belongsTo)
     */
    public function madrePorIdMadre()
    {
        return $this->belongsTo(Madre::class, 'id_madre', 'id');
    }
    
    /**
     * Relación con la madre por id_niño (hasOne)
     */
    public function madrePorIdNino()
    {
        return $this->hasOne(Madre::class, 'id_niño', 'id');
    }
    
    /**
     * Accessor para obtener la madre del niño (maneja ambas relaciones)
     * Este método se ejecuta cuando se accede a $nino->madre
     */
    public function getMadreAttribute()
    {
        // Primero intentar por id_madre
        if ($this->id_madre) {
            $madre = Madre::find($this->id_madre);
            if ($madre) {
                return $madre;
            }
        }
        
        // Si no existe, buscar por id_niño
        return Madre::where('id_niño', $this->id)->first();
    }

    public function datosExtra()
    {
        return $this->hasOne(DatosExtra::class, 'id_niño', 'id');
    }

    public function recienNacido()
    {
        return $this->hasOne(RecienNacido::class, 'id_niño', 'id');
    }

    public function vacunaRn()
    {
        return $this->hasOne(VacunaRn::class, 'id_niño', 'id');
    }

    public function tamizajeNeonatal()
    {
        return $this->hasOne(TamizajeNeonatal::class, 'id_niño', 'id');
    }

    public function controlesRn()
    {
        return $this->hasMany(ControlRn::class, 'id_niño', 'id');
    }

    public function controlesMenor1()
    {
        return $this->hasMany(ControlMenor1::class, 'id_niño', 'id');
    }

    public function visitasDomiciliarias()
    {
        return $this->hasMany(VisitaDomiciliaria::class, 'id_niño', 'id');
    }
}
