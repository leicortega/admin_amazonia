<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentos_tercero extends Model
{
    protected $fillable =[
        'tipo', 'descripcion', 'adjunto_file', 'terceros_id'
    ];

    public function tercero() {
        return $this->belongsTo('App\Models\Tercero');
    }
}
