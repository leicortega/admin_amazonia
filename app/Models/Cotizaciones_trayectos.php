<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizaciones_trayectos extends Model
{
    protected $table = 'cotizaciones_trayectos';

    protected $fillable = [
        'id','departamento_origen','ciudad_origen','departamento_destino','ciudad_destino','fecha_ida','fecha_regreso','tipo_servicio','tipo_vehiculo','recorrido','observaciones','combustible','conductor','peajes','cotizacion_por','valor_unitario','cantidad','total','responsable_id', 'vehiculo_id', 'conductor_uno_id', 'conductor_dos_id', 'conductor_tres_id', 'aceptado', 'cotizacion_id', 'descripcion_table'
    ];
}
