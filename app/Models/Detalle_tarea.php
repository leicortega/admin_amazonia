<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle_tarea extends Model
{
    protected $fillable = [
        'fecha', 'estado', 'observaciones', 'adjunto', 'tareas_id', 'users_id'
    ];

    public function tarea() {
        return $this->belongsTo('App\Models\Tarea');
    }

    public function user() {
        return $this->belongsTo('App\User', 'users_id');
    }
}
