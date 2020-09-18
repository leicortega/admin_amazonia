<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Model;

class Tipo_Vinculacion extends Model
{
    protected $table = 'tipo_vinculacion';

    protected $fillable = [
        'nombre'
    ];
}
