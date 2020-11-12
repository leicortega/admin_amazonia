<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle_inspeccion extends Model
{
    protected $table = 'detalle_inspecciones';

    protected $fillable = [
        'campo', 'cantidad', 'estado', 'admin_inspecciones_id', 'inspecciones_id'
    ];

    public function inspeccion() {
        return $this->belongsTo('App\Models\Inspeccion');
    }

    public function admin_inspecciones() {
        return $this->belongsTo('App\Models\Sistema\Admin_inspeccion');
    }
}
