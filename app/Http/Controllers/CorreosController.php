<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\RespuestaCorreoMail;
use Illuminate\Http\Request;
use App\Models\Correo;
use Carbon\Carbon;
use App\User;

class CorreosController extends Controller
{
    public function nuevos() {
        $correos = Correo::whereNull('id_user_respuesta')->paginate(10);

        return view('correos.correos', ['correos' => $correos]);
    }

    public function respondidos() {
        $correos = Correo::whereNotNull('id_user_respuesta')->paginate(10);

        return view('correos.correos', ['correos' => $correos]);
    }

    public function show(Request $request) {
        $correo = Correo::find($request['id']);
        $user = User::find($correo->id_user_respuesta);

        return ['correo' => $correo, 'user' => $user];
    }

    public function responder(Request $request) {

        $date = Carbon::now('America/Bogota');

        $correo = Correo::find($request['id']);

        $data = ['mensaje' => $request['area'], 'id' => $request['id']];

        Mail::to($correo->email)->send(new RespuestaCorreoMail($data));

        $correo->update([
            'fecha_respuesta' => $date->isoFormat('Y-MM-D'),
            'id_user_respuesta' => \Auth::user()->id,
            'respuesta' => $request['area']
        ]);

        return redirect()->route('correos')->with('respuesta', 1);
    }
}
