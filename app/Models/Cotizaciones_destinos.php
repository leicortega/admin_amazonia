<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizaciones_destinos extends Model
{
    protected $connection = 'amazonia_mysql';
    protected $table = 'cotizaciones_destinos';

    protected $fillable = [
        'id', 'departamento_origen','ciudad_origen','departamento_destino','ciudad_destino', 'id_cotizaciones'
    ];
}
