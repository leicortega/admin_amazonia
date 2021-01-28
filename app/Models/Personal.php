<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = 'personal';

    protected $fillable = [
        'tipo_identificacion', 'identificacion', 'nombres', 'primer_apellido', 'segundo_apellido', 'fecha_ingreso', 'direccion', 'sexo', 'rh', 'estado', 'tipo_vinculacion', 'telefonos', 'correo', 'firma'
    ];

    public function cargos_personal() {
        return $this->hasMany('App\Models\Cargos_personal', 'personal_id');
    }
}
