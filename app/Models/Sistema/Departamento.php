<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = [
        'nombre'
    ];

    public function municipios() {
        return $this->hasMany('App\Models\Sistema\Municipio', 'departamentos_id');
    }
}
