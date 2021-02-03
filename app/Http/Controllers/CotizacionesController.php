<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\CotizacionMail;
use Illuminate\Http\Request;
use App\Models\Contactos_tercero;
use App\Models\Cotizaciones;
use App\Models\Cotizaciones_trayectos;
use App\Models\Tercero;
use App\Models\Vehiculo;
use App\Models\Personal;
use PDF;

class CotizacionesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function nuevas() {
        $cotizaciones = Cotizaciones_trayectos::whereNull('responsable_id')->paginate(10);

        return view('cotizaciones.index', ['cotizaciones' => $cotizaciones]);
    }

    public function aceptadas() {
        $cotizaciones = Cotizaciones::where('aceptada', "1")->whereNull('contrato_generado')->join('terceros', 'terceros.id', '=','cotizaciones.tercero_id')->paginate(10);

        return view('cotizaciones.index', ['cotizaciones' => $cotizaciones]);
    }

    public function respondidas() {
        $cotizaciones = Cotizaciones::whereNotNull('responsable_id')->paginate(10);

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

    public function buscar_tercero(Request $request) {
        $tercero = Tercero::where('identificacion', $request['id'])->get();

        return ['tercero' => $tercero];
    }

    public function crear_tercero(Request $request) {
        $tercero = Tercero::create([
            'tipo_identificacion' => $request['tipo_identificacion'],
            'identificacion' => $request['identificacion'],
            'nombre' => $request['nombre'],
            'correo' => $request['correo'],
            'telefono' => $request['telefono'],
            'tipo_tercero' => $request['tipo_tercero'],
            'regimen' => $request['regimen'],
            'departamento' => $request['departamento'],
            'municipio' => $request['municipio'],
            'direccion' => $request['direccion'],
        ]);

        if ($tercero->save()) {
            $cotizacion = Cotizacion::find($request['cotizacion_id']);

            $cotizacion->update([
                'tercero_id' => $request['identificacion'],
            ]);

            return redirect()->route('cotizaciones-aceptadas')->with('tercero', 1);
        } else {
            return redirect()->route('cotizaciones-aceptadas')->with('tercero', 0);
        }
    }

    public function add_tercero(Request $request) {
        $cotizacion = Cotizacion::find($request['cotizacion_id']);

        $cotizacion->update([
            'tercero_id' => $request['identificacion_add'],
        ]);

        return redirect()->route('cotizaciones-aceptadas')->with('tercero_add', 1);
    }

    public function generar_contrato(Request $request) {
        if ( $request['select_responsable'] == 'Nuevo' ) {
            Contactos_tercero::create([
                'identificacion' => $request['identificacion_responsable'],
                'nombre' => $request['nombre_responsable'],
                'direccion' => $request['direccion_responsable'],
                'telefono' => $request['telefono_responsable'],
                'terceros_id' => $request['tercero_id_contrato'],
            ])->save();
        }

        $cotizacion = Cotizacion::find($request['cotizacion_id_contrato']);

        $cotizacion->update([
            'contrato_generado' => 1,
            'tipo_contrato' => $request['tipo_contrato'],
            'objeto_contrato' => $request['objeto_contrato'],
            'vehiculo_id' => $request['vehiculo_id'],
            'conductor_id' => $request['conductor_id'],
            'responsable_contrato_id' => $request['identificacion_responsable'],
            'contrato_parte_uno' => $request['contrato_parte_uno'],
            'contrato_parte_dos' => $request['contrato_parte_dos'],
        ]);

        $tercero = Tercero::where('identificacion', $request['tercero_id_contrato'])->first();
        $responsable = Contactos_tercero::where('identificacion', $request['identificacion_responsable'])->first();
        $vehiculo = Vehiculo::find($request['vehiculo_id']);
        $conductor = Personal::find($request['conductor_id']);

        $data = [
            'cotizacion' => $cotizacion,
            'tercero' => $tercero,
            'responsable' => $responsable,
            'vehiculo' => $vehiculo,
            'conductor' => $conductor
        ];

        return PDF::loadView('cotizaciones.contrato', compact('data'))->setPaper('A4')->stream('cotizacion.pdf');

    }

}
