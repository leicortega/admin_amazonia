<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estados_solicitud extends Model
{
    protected $table = 'estados_solicitud';

    protected $fillable = [
        'estado', 'descripcion', 'users_id', 'conceptos_id'
    ];
}
