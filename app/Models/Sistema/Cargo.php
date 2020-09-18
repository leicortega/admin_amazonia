<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $fillable = [
        'id', 'nombre', 'funciones', 'obligaciones'
    ];
}
