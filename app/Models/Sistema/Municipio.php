<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $fillable = [
        'nombre', 'departamentos_id'
    ];

    public function departamentos() {
        return $this->belongsTo('App\Models\Sistema\Departamento');
    }
}
