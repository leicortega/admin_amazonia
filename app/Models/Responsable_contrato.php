<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Responsable_contrato extends Model
{
    protected $table = 'responsable_contrato';

    protected $fillable = [
        'tipo_identificacion', 'identificacion', 'nombre', 'direccion', 'correo', 'telefono', 'tercero_id'
    ];
}
