<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contratos_personal extends Model
{
    protected $table = 'contratos_personal';

    protected $fillable = [
        'salario', 'estado', 'cargo', 'tipo_contrato', 'fecha_inicio', 'fecha_fin', 'clausulas_parte_uno', 'clausulas_parte_dos', 'personal_id'
    ];

    public function personal() {
        return $this->belongsTo('App\Models\Personal');
    }

    public function otro_si() {
        return $this->hasMany('App\Models\Otro_si', 'contratos_personal_id');
    }
}
