<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $fillable = [
        'fecha', 'vehiculo_id', 'conductor_uno_id', 'conductor_dos_id', 'conductor_tres_id', 'responsable_contrato_id', 'tipo_contrato', 'objeto_contrato', 'contrato_parte_uno', 'contrato_parte_dos', 'tercero_id', 'cotizacion_id'
    ];

    public function trayectos() {
        return $this->hasMany('App\Models\Trayectos_contrato', 'contratos_id');
    }
}
