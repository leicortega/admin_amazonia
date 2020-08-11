<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\CotizacionMail;
use Illuminate\Http\Request;
use App\Models\Cotizacion;
use PDF;

class CotizacionesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function nuevas() {
        $cotizaciones = Cotizacion::whereNull('responsable_id')->paginate(10);

        return view('cotizaciones.index', ['cotizaciones' => $cotizaciones]);
    }

    public function respondidas() {
        $cotizaciones = Cotizacion::whereNotNull('responsable_id')->paginate(10);

        return view('cotizaciones.index', ['cotizaciones' => $cotizaciones]);
    }

    public function show(Request $request) {
        $cotizacion = Cotizacion::find($request['id']);
        
        return ['cotizacion' => $cotizacion];
    }

    public function responder(Request $request) {
        $cotizacion = Cotizacion::find($request['id']);
        
        $cotizacion->update([
            "fecha_ida" => $request['fecha_ida'],
            "fecha_regreso" => $request['fecha_regreso'],
            "tipo_servicio" => $request['tipo_servicio'],
            "tipo_vehiculo" => $request['tipo_vehiculo'],
            "departamento_origen" => $request['departamento_origen'],
            "ciudad_origen" => $request['ciudad_origen'],
            "departamento_destino" => $request['departamento_destino'],
            "ciudad_destino" => $request['ciudad_destino'],
            "descripcion" => $request['descripcion'],
            "observaciones" => $request['observaciones'],
            "combustible" => $request['combustible'],
            "conductor" => $request['conductor'],
            "peajes" => $request['peajes'],
            "cotizacion_por" => $request['cotizacion_por'],
            "recorrido" => $request['recorrido'],
            "valor_unitario" => $request['valor_unitario'],
            "cantidad" => $request['cantidad'],
            "total" => $request['valor_unitario']*$request['cantidad'],
            "trayecto_dos" => $request['trayecto_dos'],
            "responsable_id" => \Auth::user()->id,
        ]);

        $pdf = PDF::loadView('cotizaciones.pdf', compact('cotizacion'))->setPaper('A4')->output();

        Mail::to($cotizacion->correo)->send(new CotizacionMail($cotizacion, $pdf));

        return redirect()->route('cotizaciones')->with('enviado', 1);
        
    }

    public function aceptar(Request $request) {

        $cotizacion = Cotizacion::find($request['id']);
        
        $cotizacion->update([
            "fecha_ida" => $request['fecha_ida'],
            "fecha_regreso" => $request['fecha_regreso'],
            "tipo_servicio" => $request['tipo_servicio'],
            "tipo_vehiculo" => $request['tipo_vehiculo'],
            "departamento_origen" => $request['departamento_origen'],
            "ciudad_origen" => $request['ciudad_origen'],
            "departamento_destino" => $request['departamento_destino'],
            "ciudad_destino" => $request['ciudad_destino'],
            "descripcion" => $request['descripcion'],
            "observaciones" => $request['observaciones'],
            "combustible" => $request['combustible'],
            "conductor" => $request['conductor'],
            "peajes" => $request['peajes'],
            "cotizacion_por" => $request['cotizacion_por'],
            "recorrido" => $request['recorrido'],
            "valor_unitario" => $request['valor_unitario'],
            "cantidad" => $request['cantidad'],
            "total" => $request['valor_unitario']*$request['cantidad'],
            "trayecto_dos" => $request['trayecto_dos'],
            "responsable_id" => \Auth::user()->id,
        ]);

        $pdf = PDF::loadView('cotizaciones.pdf', compact('cotizacion'))->setPaper('A4')->output();

        // $pdf;

        Mail::to($cotizacion->correo)->send(new CotizacionMail($cotizacion, $pdf));

        return redirect()->route('cotizaciones')->with('enviado', 1);
        
    }
}
