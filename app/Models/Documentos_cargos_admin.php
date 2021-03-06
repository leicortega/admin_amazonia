<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentos_cargos_admin extends Model
{
    protected $table = 'documentos_cargos_admin';

    protected $fillable = [
        'nombre', 'vigencia'
    ];
}
