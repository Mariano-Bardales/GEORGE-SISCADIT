<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecienNacido extends Model
{
    use HasFactory;

    protected $table = 'recien_nacido';
    
    protected $primaryKey = 'id_rn';
    
    public $incrementing = true;
    
    // Deshabilitar timestamps porque la tabla no tiene created_at y updated_at
    public $timestamps = false;

    protected $fillable = [
        'id_niño',
        'peso',
        'edad_gestacional',
        'clasificacion',
    ];

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'id_niño');
    }
}
