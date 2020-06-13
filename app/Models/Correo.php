<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Correo extends Model
{

    protected $connection = 'amazonia_mysql';
    protected $table = 'correos';

    protected $fillable = [
        'fecha', 'nombre', 'apellido', 'email', 'asunto', 'mensaje', 'fecha_respuesta', 'id_user_respuesta', 'respuesta'
    ];
}
