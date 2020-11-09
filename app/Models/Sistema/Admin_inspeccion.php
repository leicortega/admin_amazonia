<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Model;

class Admin_inspeccion extends Model
{
    protected $table = 'admin_inspecciones';

    protected $fillable = [
        'nombre', 'cantidad', 'vigencia', 'tipo'
    ];
}
