<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentacion extends Model
{
    protected $table = 'documentacion';

    protected $fillable = [
        'nombre'
    ];

    public function documentos_documentacion() {
        return $this->hasMany('App\Models\Documentos_documentacion', 'documentacion_id');
    }
}
