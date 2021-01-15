<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archivos_soportados extends Model
{
    protected $table = 'archivos_soportados';

    protected $fillable = [
        'archivo', 'valor_soporte', 'conceptos_solicitud_id'
    ];
}
