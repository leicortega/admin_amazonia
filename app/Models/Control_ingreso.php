<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Control_ingreso extends Model
{
    protected $fillable = [
        'name', 'identificacion', 'telefono', 'edad', 'email', 'tipo',
    ];

    public function ingresos() {
        return $this->hasMany('App\Models\Ingreso', 'control_ingreso_id');
    }
}
