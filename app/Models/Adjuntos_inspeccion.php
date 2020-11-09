<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjuntos_inspeccion extends Model
{
    protected $table = 'adjuntos_inspecciones';

    protected $fillable = [
        'elemento', 'observaciones', 'adjunto', 'inspecciones_id'
    ];

    public function inspeccion() {
        return $this->belongsTo('App\Models\Inspeccion');
    }
}
