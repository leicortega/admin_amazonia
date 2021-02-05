<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin_documentos_vehiculo extends Model
{
    protected $table = 'admin_documentos_vehiculo';

    protected $fillable = [
        'name', 'vigencia', 'categoria_id', 'tipo_tercero', 'proceso'
    ];
}
