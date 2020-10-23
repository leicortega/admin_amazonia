<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle_actividad_mantenimiento extends Model
{
    protected $fillable = [
        'descripcion', 'imagen_soporte', 'actividad_mantenimientos_id'
    ];

    public function actividad_mantenimientos() {
        return $this->belongsTo('App\Models\Actividad_mantenimiento');
    }
}
