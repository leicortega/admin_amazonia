<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conductores_vehiculo extends Model
{
    protected $table = 'conductores_vehiculo';

    protected $fillable = [
        'id', 'personal_id', 'vehiculo_id'
    ];
    
    public function personal() {
        return $this->belongsTo('App\Models\Personal');
    }

    public function vehiculos() {
        return $this->belongsTo('App\Models\Vehiculo');
    }
}
