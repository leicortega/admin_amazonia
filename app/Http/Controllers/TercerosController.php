<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tercero;
use App\Models\Perfiles_tercero;
use App\Models\Contactos_tercero;

class TercerosController extends Controller
{
    public function index() {
        $terceros = Tercero::paginate(20);

        return view('terceros.index', ['terceros' => $terceros]);
    }

    public function create(Request $request) {
        $this->validate($request, [
            'identificacion' => 'unique:terceros,identificacion'
        ], $messages = [
            'unique'    => 'La :attribute ya esta registrada'
        ]);

        $tercero = Tercero::create($request->all());
        $perfil = Perfiles_tercero::create(['nombre' => $request['tipo_tercero'], 'terceros_id' => $tercero->id]);

        if ( $tercero->save() ) {
            return redirect()->route('terceros')->with('tercero', 1);
        } else {
            return redirect()->route('terceros')->with('tercero', 0);
        }
    }

    public function ver(Request $request) {
        $tercero = Tercero::where('id', $request['id'])->with('perfiles_terceros')->get();

        return view('terceros.ver', ['tercero' => $tercero]);
    }

    public function agg_contacto(Request $request) {
        Contactos_tercero::create([
            'identificacion' => $request['identificacion_contacto'],
            'nombre' => $request['nombre_contacto'],
            'telefono' => $request['telefono_contacto'],
            'correo' => $request['correo_contacto'],
            'terceros_id' => $request['terceros_id'],
        ])->save();

        return $request['terceros_id'];
    }

    public function cargar_contactos(Request $request) {
        return Contactos_tercero::where('terceros_id', $request['id'])->orWhere('identificacion', $request['responsable'])->get();
    }

    // public function cargar_responsable_contrato(Request $request) {

    // }
}
