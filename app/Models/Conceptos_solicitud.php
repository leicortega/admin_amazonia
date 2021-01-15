<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conceptos_solicitud extends Model
{
    protected $table = 'conceptos_solicitud';
    protected $fillable = [
         'nombre', 'valor_entregado', 'valor_soportado', 'saldo', 'solicitud_id'
    ];
}
