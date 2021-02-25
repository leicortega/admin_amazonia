<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class respuesta_correspondencia extends Model
{
    protected $table = 'respuesta_correspondencia';

    protected $fillable = [
        'asunto', 'mensaje', 'adjunto', 'correspondencia_id'
    ];
}
