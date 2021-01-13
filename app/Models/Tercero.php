<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tercero extends Model
{
    protected $fillable = [
        'tipo_identificacion', 'identificacion', 'nombre', 'correo', 'telefono', 'tipo_tercero', 'regimen', 'departamento', 'municipio', 'direccion'
    ];

    public function perfiles_terceros() {
        return $this->hasMany('App\Models\Perfiles_tercero', 'terceros_id');
    }


    //funciones de filtros

    public function scopeDepartamento($query, $departamento){
        if ($departamento!=null) {
    		return $query->where('departamento',"$departamento");
    	}
    }

    public function scopeCiudad($query, $municipio){
        if ($municipio!=null) {
    		return $query->where('municipio',"$municipio");
    	}
    }
    public function scopeOrden($query, $orden){
        if ($orden!=null) {
    		return $query->orderBy("$orden");
    	}
    }

    public function scopeBuscapor($query, $busca, $por){
        if($busca!=null && $por!=null){
            return $query->where("$por", 'like', "%$busca%");
        }
    }

}

