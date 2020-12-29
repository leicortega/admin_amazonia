<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentos_documentacion extends Model
{
    protected $table = 'documentos_documentacion';

    protected $fillable = [
        'nombre', 'file', 'documentacion_id'
    ];

    public function documentacion() {
        return $this->belongsTo('App\Models\Documentacion', 'documentacion_id');
    }
}
