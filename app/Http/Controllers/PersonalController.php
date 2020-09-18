<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Personal;
use App\Models\Cargos_personal;

class PersonalController extends Controller
{
    public function registro() {
        $personal = Personal::paginate(20);

        return view('personal.registro', ['personal' => $personal]);
    }

    public function create(Request $request) {

        if( Personal::create($request->all())->save() ) {
            return redirect()->route('personal')->with(['creado' => 1]);
        }

        return redirect()->route('personal')->with(['creado' => 0]);
    }

    public function ver_ajax(Request $request) {
        return Personal::find($request['id']);
    }

    public function agg_cargo_personal(Request $request) {
        if ( Cargos_personal::create(['personal_id' => $request['personal_id'], 'cargos_id' => $request['cargos_id']])->save() ) {
            return ['create' => 1, 'personal_id' => $request['personal_id']];
        }

        return ['create' => 0];
    }

    public function cargar_cargos_personal(Request $request) {
        return DB::table('cargos_personal')
                ->join('cargos', 'cargos.id', '=', 'cargos_personal.cargos_id')
                ->where('cargos_personal.personal_id', $request['id'])
                ->get();
    }
}
