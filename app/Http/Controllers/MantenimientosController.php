<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

use App\Models\Vehiculo;
use App\Models\Mantenimiento;
use App\Models\Facturas_mantenimiento;
use App\Models\Actividad_mantenimiento;
use App\Models\Detalle_actividad_mantenimiento;

class MantenimientosController extends Controller
{
    public function index() {
        $vehiculos = Vehiculo::all();
        $solicitados = Mantenimiento::where('estado', 'Solicitado')->with('vehiculo')->with('personal')->paginate(10);

        return view('vehiculos.mantenimientos.index', ['vehiculos' => $vehiculos, 'solicitados' => $solicitados]);
    }

    public function solicitar_mantenimiento(Request $request) {
        $mantenimiento = Mantenimiento::create($request->all())->save();

        $redirect = ($mantenimiento) ? '/vehiculos/'.$request['vehiculo_id'].'/mantenimientos' : '/vehiculos/mantenimientos';
        $error    = ($mantenimiento) ? 0 : 1;
        $mensaje  = ($mantenimiento) ? 'Solicitud generada correctamente' : 'ERROR! Solicitud no generada correctamente';

        return redirect($redirect)->with(['error' => $error, 'mensaje' => $mensaje]);
    }

    public function mantenimientos_vehiculo(Request $request, $id) {
        $vehiculos = Vehiculo::all();
        $solicitados = Mantenimiento::where('vehiculo_id', $id)->with('vehiculo')->with('personal')->paginate(10);

        return view('vehiculos.mantenimientos.index', ['vehiculos' => $vehiculos, 'solicitados' => $solicitados]);
    }

    public function ver(Request $request) {
        $mantenimiento = Mantenimiento::find($request['id'])->with('vehiculo')->with('personal')->with(['actividades' => function ($query) {
            $query->with('detalle_actividades');
        }])->with('facturas')->first();

        return view('vehiculos.mantenimientos.ver', ['mantenimiento' => $mantenimiento]);
    }

    public function agregar_actividad(Request $request) {
        $date = Carbon::now('America/Bogota');

        $actividad = Actividad_mantenimiento::create([
            'fecha' => $request['fecha'],
            'tipo' => $request['tipo'],
            'observaciones' => $request['observaciones'],
            'mantenimientos_id' => $request['id'],
        ]);

        if ($actividad->save()) {
            $extension_file_documento = pathinfo($request->file('imagen_soporte')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/vehiculos/mantenimientos/';
            $nombre_file_documento = 'actividad_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('imagen_soporte')));

            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;

            $detalle_actividad = Detalle_actividad_mantenimiento::create([
                'descripcion' => $request['descripcion'],
                'imagen_soporte' => $nombre_completo_file_documento,
                'actividad_mantenimientos_id' => $actividad->id,
            ]);

            if ($detalle_actividad->save()) {
                return redirect()->back()->with(['error' => 0, 'mensaje' => 'Actividad agregada correctamente']);
            }
        }

        return redirect()->back()->with(['error' => 1, 'mensaje' => 'ERROR! Actividad no agregada correctamente']);
    }

    public function agregar_detalle_actividad(Request $request) {
        $date = Carbon::now('America/Bogota');

        $extension_file_documento = pathinfo($request->file('imagen_soporte')->getClientOriginalName(), PATHINFO_EXTENSION);
        $ruta_file_documento = 'docs/vehiculos/mantenimientos/';
        $nombre_file_documento = 'actividad_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
        Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('imagen_soporte')));

        $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;

        $detalle_actividad = Detalle_actividad_mantenimiento::create([
            'descripcion' => $request['descripcion'],
            'imagen_soporte' => $nombre_completo_file_documento,
            'actividad_mantenimientos_id' => $request['id_actividad'],
        ]);

        if ($detalle_actividad->save()) {
            return redirect()->back()->with(['error' => 0, 'mensaje' => 'Actividad agregada correctamente']);
        }

        return redirect()->back()->with(['error' => 1, 'mensaje' => 'ERROR! Actividad no agregada correctamente']);
    }

    public function agregar_facruta(Request $request) {
        $date = Carbon::now('America/Bogota');

        $extension_file_documento = pathinfo($request->file('factura_imagen')->getClientOriginalName(), PATHINFO_EXTENSION);
        $ruta_file_documento = 'docs/vehiculos/facturas/';
        $nombre_file_documento = 'factura_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
        Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('factura_imagen')));

        $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;

        $factura = Facturas_mantenimiento::create([
            'proveedor' => $request['proveedor'],
            'valor' => $request['valor'],
            'factura_imagen' => $nombre_completo_file_documento,
            'mantenimientos_id' => $request['mantenimientos_id'],
        ]);

        if ($factura->save()) {
            return redirect()->back()->with(['error' => 0, 'mensaje' => 'Factura agregada correctamente']);
        }

        return redirect()->back()->with(['error' => 1, 'mensaje' => 'ERROR! Factura no agregada correctamente']);
    }

    public function agregar_firma(Request $request) {
        $date = Carbon::now('America/Bogota');
        $mantenimiento = Mantenimiento::find($request['mantenimientos_id_firma']);

        if ($request['tipo'] == 'Autorizar') {
            $mantenimiento->update([
                'persona_autoriza' => $request['persona_firma'],
                'fecha_autorizacion' => $date->format('Y-m-d H:m:s'),
                'observaciones_autorizacion' => $request['observaciones'],
            ]);
        } else {
            $mantenimiento->update([
                'persona_contabilidad' => $request['persona_firma'],
                'fecha_contabilidad' => $date->format('Y-m-d H:m:s'),
                'observaciones_contabilidad' => $request['observaciones'],
            ]);
        }

        return redirect()->back()->with(['error' => 0, 'mensaje' => 'Firma agregada correctamente']);
    }

    public function print(Request $request) {
        $mantenimiento = Mantenimiento::find($request['id'])->with('vehiculo')->with('personal')->with(['actividades' => function ($query) {
            $query->with('detalle_actividades');
        }])->with('facturas')->first();

        return PDF::loadView('vehiculos.mantenimientos.pdf', compact('mantenimiento'))->setPaper('A4')->stream('mantenimiento.pdf');
    }
}
