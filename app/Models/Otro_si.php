<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otro_si extends Model
{
    protected $table = 'otro_si';

    protected $fillable = [
        'fecha', 'descripcion', 'contratos_personal_id'
    ];

    public function contratos_personal() {
        return $this->belongsTo('App\Models\Contratos_personal');
    }
}
