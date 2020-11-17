<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $connection = 'amazonia_mysql';
    protected $table = 'cotizaciones';

    protected $fillable = [
        'id','num_cotizacion','fecha','nombre','correo','telefono','departamento_origen','ciudad_origen','departamento_destino','ciudad_destino','fecha_ida','fecha_regreso','tipo_servicio','tipo_vehiculo','recorrido','descripcion','observaciones','combustible','conductor','peajes','cotizacion_por','valor_unitario','cantidad','total','trayecto_dos','responsable_id','tercero_id', 'contrato_generado', 'vehiculo_id', 'conductor_uno_id', 'conductor_dos_id', 'conductor_tres_id', 'responsable_contrato_id', 'tipo_contrato', 'objeto_contrato', 'contrato_parte_uno', 'contrato_parte_dos', 'cotizacion_parte_uno', 'cotizacion_parte_dos',
    ];
}
