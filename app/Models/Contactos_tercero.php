<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contactos_tercero extends Model
{
    protected $fillable = [
        'identificacion', 'nombre', 'telefono', 'direccion', 'terceros_id'
    ];
}
