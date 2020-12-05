<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    protected $fillable = [
        'fecha', 'notificado', 'alertas'
    ];
}
