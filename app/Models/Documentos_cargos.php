<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentos_cargos extends Model
{
    protected $table = 'documentos_cargos';

    protected $fillable = [
        'cargos_id', 'documentos_cargos_id'
    ];
}
