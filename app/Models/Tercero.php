<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tercero extends Model
{
    protected $fillable = [
        'tipo_identificacion', 'identificacion', 'nombre', 'correo', 'telefono', 'tipo_tercero', 'regimen', 'departamento', 'municipio', 'direccion'
    ];
}

