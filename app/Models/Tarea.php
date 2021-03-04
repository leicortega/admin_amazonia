<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $fillable = [
        'fecha', 'tarea', 'fecha_limite', 'estado', 'adjunto', 'tipos_tareas_id', 'supervisor', 'asignado', 'name_tarea'
    ];

    public function detalle_tareas() {
        return $this->hasMany('App\Models\Detalle_tarea', 'tareas_id');
    }

    public function tipo_tarea(){
        return $this->belongsTo('App\Models\Tipos_tarea', 'tipos_tareas_id');
    }

    public function supervisor_id() {
        return $this->belongsTo('App\User', 'supervisor');
    }

    public function asignado_id() {
        return $this->belongsTo('App\User', 'asignado');
    }
}
