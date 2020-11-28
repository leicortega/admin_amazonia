<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contabilidad;
use App\Models\Personal;

class ContabilidadController extends Controller
{
    public function index() {

        $persona = Personal::where('identificacion', auth()->user()->identificacion)->first();

        if (auth()->user()->hasRole('general')) {
            $registros = Contabilidad::with(['vehiculo' => function ($query, $persona) {
                $query->where('personal_id', $persona->id);
            }])->paginate(10);
        } else {
            $registros = Contabilidad::paginate(10);
        }

        return view('contabilidad.index', ['registros' => $registros]);
    }
}
