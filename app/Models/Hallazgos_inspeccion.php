<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hallazgos_inspeccion extends Model
{
    protected $table = 'hallazgos_inspecciones';

    protected $fillable = [
        'inspecciones_id', 'mantenimientos_id', 'vehiculos_id'
    ];

    public function inspeccion() {
        return $this->belongsTo('App\Models\Inspeccion', 'inspecciones_id');
    }

    public function mantenimiento() {
        return $this->belongsTo('App\Models\Mantenimiento', 'mantenimientos_id');
    }

    public function vehiculo() {
        return $this->belongsTo('App\Models\Vehiculo', 'vehiculos_id');
    }
}
