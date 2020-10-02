<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentos_personal extends Model
{
    protected $table = 'documentos_personal';

    protected $fillable = [
        'tipo', 'fecha_expedicion', 'fecha_inicio_vigencia', 'fecha_fin_vigencia', 'observaciones', 'adjunto', 'personal_id'
    ];

    public function personal() {
        return $this->belongsTo('App\Models\Personal');
    }
}
