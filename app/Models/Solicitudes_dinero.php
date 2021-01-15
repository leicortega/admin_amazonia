<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitudes_dinero extends Model
{
    protected $table = 'solicitudes_dinero';
    protected $fillable = [
        'id', 'tipo_solicitud', 'fecha_solicitud', 'descripcion', 'personal_crea', 'beneficiario'
    ];
}
