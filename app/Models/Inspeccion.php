<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspeccion extends Model
{
    protected $table = 'inspecciones';

    protected $fillable = [
        'fecha_inicio', 'kilometraje_inicio', 'observaciones_inicio', 'fecha_final', 'kilometraje_final', 'observaciones_final', 'users_id', 'vehiculo_id',
    ];

    public function users() {
        return $this->belongsTo('App\User');
    }

    public function vehiculo() {
        return $this->belongsTo('App\Models\Vehiculo');
    }

    public function detalle() {
        return $this->hasMany('App\Models\Detalle_inspeccion', 'inspecciones_id');
    }

    public function adjuntos() {
        return $this->hasMany('App\Models\Adjuntos_inspeccion', 'inspecciones_id');
    }
}
