<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Model;

class Tipo_Vehiculo extends Model
{
    protected $table = 'tipo_vehiculo';

    protected $fillable = [
        'nombre', 'categoria_vehiculo'
    ];
}
