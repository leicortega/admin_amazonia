<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentos_legales_vehiculo extends Model
{
    protected $fillable = [
        'tipo', 'consecutivo', 'fecha_expedicion', 'fecha_inicio_vigencia', 'fecha_fin_vigencia', 'entidad_expide', 'estado', 'documento_file', 'vehiculo_id'
    ];

    public function vehiculo() {
        return $this->belongsTo('App\Models\Vehiculo');
    }
}