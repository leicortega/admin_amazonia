<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hallazgos_inspeccion extends Model
{
    protected $table = 'hallazgos_inspecciones';

    protected $fillable = [
        'inspecciones_id', 'mantenimientos_id'
    ];

    public function inspeccion() {
        return $this->belongsTo('App\Models', 'inspecciones_id');
    }

    public function mantenimiento() {
        return $this->belongsTo('App\Models', 'mantenimientos_id');
    }
}
