<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    protected $fillable = [
        'fecha', 'descripcion_solicitud', 'estado', 'persona_contabilidad', 'fecha_contabilidad', 'observaciones_contabilidad', 'persona_autoriza', 'fecha_autorizacion', 'observaciones_autorizacion', 'vehiculo_id', 'personal_id'
    ];

    public function vehiculo() {
        return $this->belongsTo('App\Models\Vehiculo');
    }

    public function personal() {
        return $this->belongsTo('App\Models\Personal');
    }

    public function actividades() {
        return $this->hasMany('App\Models\Actividad_mantenimiento', 'mantenimientos_id');
    }

    public function facturas() {
        return $this->hasMany('App\Models\Facturas_mantenimiento', 'mantenimientos_id');
    }
}
