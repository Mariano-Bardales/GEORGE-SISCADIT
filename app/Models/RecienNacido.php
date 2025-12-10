<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecienNacido extends Model
{
    use HasFactory;

    protected $table = 'recien_nacidos';
    
    protected $primaryKey = 'id';
    
    public $incrementing = true;
    
    // Deshabilitar timestamps - campos eliminados de la base de datos
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
