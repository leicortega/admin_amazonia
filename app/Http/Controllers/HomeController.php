<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Cotizacion;
use App\Models\Correo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $cotizaciones = Cotizacion::whereNull('responsable_id')->count();
        $correos = Correo::whereNull('id_user_respuesta')->count();
        $notificaciones = Notification::whereNull('visto')->paginate(10);

        return view('welcome', ['cotizaciones' => $cotizaciones, 'correos' => $correos, 'notificaciones' => $notificaciones]);
    }
}
