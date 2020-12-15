<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trayectos_contrato extends Model
{
    protected $table = 'trayectos_contrato';

    protected $fillable = [
        'id','fecha','nombre','correo','telefono','departamento_origen','ciudad_origen','departamento_destino','ciudad_destino','fecha_ida','fecha_regreso','tipo_servicio','tipo_vehiculo','recorrido','descripcion','observaciones','combustible','conductor','peajes','cotizacion_por','valor_unitario','cantidad','total','trayecto_dos','responsable_id','tercero_id','contratos_id'
    ];

    public function contratos() {
        return $this->belongsTo('App\Models\Contrato');
    }
}
