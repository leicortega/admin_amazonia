<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle_inspeccion extends Model
{
    protected $table = 'detalle_inspecciones';

    protected $fillable = [
        'campo', 'cantidad', 'estado', 'inspecciones_id'
    ];

    public function inspeccion() {
        return $this->belongsTo('App\Models\Inspeccion');
    }
}
