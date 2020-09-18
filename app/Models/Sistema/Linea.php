<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Model;

class Linea extends Model
{
    protected $table = 'linea';

    protected $fillable = [
        'nombre'
    ];
}
