<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Model;

class Tipo_Carroceria extends Model
{
    protected $table = 'tipo_carroceria';

    protected $fillable = [
        'nombre'
    ];
}
