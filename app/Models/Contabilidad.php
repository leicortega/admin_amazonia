<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contabilidad extends Model
{
    protected $table = 'contabilidad';

    protected $fillable = [
        'persona_creo', 'fecha', 'concepto', 'valor_pagar', 'valor_cobrar', 'anexo', 'vehiculos_id'
    ];

    public function vehiculo() {
        return $this->belongsTo('App\Models\Vehiculo', 'vehiculos_id');
    }
}
