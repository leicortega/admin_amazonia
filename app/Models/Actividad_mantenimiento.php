<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actividad_mantenimiento extends Model
{
    protected $fillable = [
        'fecha', 'tipo', 'observaciones', 'mantenimientos_id'
    ];

    public function mantenimientos() {
        return $this->belongsTo('App\Models\Mantenimiento');
    }

    public function detalle_actividades() {
        return $this->hasMany('App\Models\Detalle_actividad_mantenimiento', 'actividad_mantenimientos_id');
    }
}
