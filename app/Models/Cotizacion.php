<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $connection = 'amazonia_mysql';
    protected $table = 'cotizaciones';

    protected $fillable = [
        'id','num_cotizacion','fecha','tercero_id', 'cotizacion_parte_uno', 'cotizacion_parte_dos', 'aceptada'
    ];
}
