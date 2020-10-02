<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargos_personal extends Model
{
    protected $table = 'cargos_personal';

    protected $fillable = [
        'id', 'personal_id', 'cargos_id'
    ];

    public function personal() {
        return $this->belongsTo('App\Models\Personal');
    }

    public function cargos() {
        return $this->belongsTo('App\Models\Sistema\Cargo');
    }
}
