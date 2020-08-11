<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $fillable = [
        'fecha','temperatura','pregunta_one','pregunta_two','pregunta_three','fiebre','tos','gripa','malestar','dolor_cabeza','fatiga','secrecion_nasal','dificultad_respirar','dolor_garganta','olfato_gusto','diabetes','hipertension','mayor_edad','cancer','inmunodeficiencia', 'control_ingreso_id', 'sede'
    ];

    public function persona() {
        return $this->belongsTo('App\Models\Control_ingreso');
    }
}