<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trayectos_contrato extends Model
{
    protected $table = 'trayectos_contrato';

    protected $fillable = [
        'id','fecha','nombre','correo','telefono','departamento_origen','ciudad_origen','departamento_destino','ciudad_destino','fecha_ida','fecha_regreso','tipo_servicio','tipo_vehiculo','recorrido','observaciones','combustible','conductor','peajes','cotizacion_por','valor_unitario','cantidad','total','vehiculo_id', 'conductor_uno_id', 'conductor_dos_id', 'conductor_tres_id','contratos_id', 'descripcion_table'
    ];

    public function contratos() {
        return $this->belongsTo('App\Models\Contrato');
    }
}
