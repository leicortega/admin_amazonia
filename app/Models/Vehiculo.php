<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $fillable = [
        'placa', 'licencia_transito', 'modelo', 'capacidad', 'numero_motor', 'chasis', 'numero_interno', 'tarjeta_operacion', 'color', 'estado', 'tipo_vehiculo_id', 'marca_id', 'tipo_vinculacion_id', 'linea_id', 'tipo_carroceria_id', 'personal_id'
    ];
}