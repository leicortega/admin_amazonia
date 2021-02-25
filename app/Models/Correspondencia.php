<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Correspondencia extends Model
{
    protected $table = 'correspondencia';

    protected $fillable = [
        'tipo_radicacion_id', 'dependencia_id', 'asunto', 'numero_folios', 'origen_id', 'adjunto', 'tercero_id', 'users_id'
    ];
}
