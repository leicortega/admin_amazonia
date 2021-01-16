<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PDF;

use App\Models\sistema\Proveedor;;
use App\Models\Vehiculo;
use App\Models\Personal;
use App\Models\Mantenimiento;
use App\Models\Facturas_mantenimiento;
use App\Models\Actividad_mantenimiento;
use App\Models\Detalle_actividad_mantenimiento;

class MantenimientosController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $vehiculos = Vehiculo::all();
        $users=DB::table('personal')->get();
        $solicitados = Mantenimiento::with('vehiculo')->with('personal')->orderBy('fecha', 'desc')->paginate(10);
        // $solicitados = Mantenimiento::where('estado', 'Solicitado')->with('vehiculo')->with('personal')->orderBy('fecha', 'desc')->paginate(10);

        return view('vehiculos.mantenimientos.index', ['vehiculos' => $vehiculos, 'solicitados' => $solicitados, 'usuarios' => $users]);
    }

    public function solicitar_mantenimiento(Request $request) {
        $propietario = Personal::find(Vehiculo::find($request->vehiculo_id)->personal_id)->correo;

        $mantenimiento = Mantenimiento::create($request->all());

        $redirect = ($mantenimiento->save()) ? '/vehiculos/'.$request['vehiculo_id'].'/mantenimientos' : '/vehiculos/mantenimientos';
        $error    = ($mantenimiento->save()) ? 0 : 1;
        $mensaje  = ($mantenimiento->save()) ? 'Solicitud generada correctamente' : 'ERROR! Solicitud no generada correctamente';

        $data = [
            'titulo' => 'SOLICITUD DE MANTENIMIENTO',
            'link' => 'https://admin.amazoniacl.com/vehiculos/ver/mantenimiento/'.$mantenimiento->id
        ];

        Mail::to(['calidad@amazoniacl.com', 'gerencia@amazoniacl.com', $propietario])->send(new NotificationMail($data));

        return redirect($redirect)->with(['error' => $error, 'mensaje' => $mensaje]);
    }

    public function mantenimientos_vehiculo(Request $request, $id) {
        $vehiculos = Vehiculo::all();
        $users = DB::table('personal')->get();
        $solicitados = Mantenimiento::where('vehiculo_id', $id)->with('vehiculo')->with('personal')->paginate(10);

        return view('vehiculos.mantenimientos.index', ['vehiculos' => $vehiculos, 'solicitados' => $solicitados, 'usuarios' => $users]);
    }

    public function ver(Request $request) {
        $provedores = Proveedor::orderBy('nombre')->get();
        $mantenimiento = Mantenimiento::with('vehiculo')->with('personal')->with(['actividades' => function ($query) {
            $query->with('detalle_actividades');
        }])->with('facturas')->find($request['id']);

        return view('vehiculos.mantenimientos.ver', ['mantenimiento' => $mantenimiento, 'proveedores' => $provedores]);
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

        $mantenimiento->update([
            'estado' => $request['tipo'],
            'persona_cierre' => $request['persona_firma'],
            'fecha_cierre' => $date->format('Y-m-d H:m:s'),
            'observaciones_cierre' => $request['observaciones'],
        ]);

        return redirect()->back()->with(['error' => 0, 'mensaje' => 'Mantenimiento cerrado correctamente']);
    }

    public function print(Request $request) {
        $mantenimiento = Mantenimiento::with('vehiculo')->with('personal')->with(['actividades' => function ($query) {
            $query->with('detalle_actividades');
        }])->with('facturas')->find($request['id']);

        return PDF::loadView('vehiculos.mantenimientos.pdf', compact('mantenimiento'))->setPaper('A4')->stream('mantenimiento.pdf');
    }

    public function autorizar_view(Request $request) {
        $mantenimiento = Mantenimiento::with('vehiculo')->with('personal')->with(['actividades' => function ($query) {
            $query->with('detalle_actividades');
        }])->with('facturas')->find($request['id']);

        return view('vehiculos.mantenimientos.ver', ['mantenimiento' => $mantenimiento]);
    }

    public function autorizar(Request $request) {
        $date = Carbon::now('America/Bogota');
        $mantenimiento = Mantenimiento::find($request['mantenimientos_id']);

        $autorizado = ($request['btn_autorizar'] == 'Si') ? 'Aprobado' : 'No Aprobado';

        $mantenimiento->update([
            'estado' => $autorizado,
            'persona_autoriza' => auth()->user()->name,
            'fecha_autorizacion' => $date->format('Y-m-d H:m:s'),
            'observaciones_autorizacion' => $request['observaciones'],
            'asume' => $request['asume'],
        ]);

        $data = [
            'titulo' => 'SE AUTORIZO MANTENIMINETO',
            'link' => 'https://admin.amazoniacl.com/vehiculos/ver/mantenimiento/'.$mantenimiento->id
        ];

        Mail::to(['contabilidad@amazoniacl.com', 'calidad@amazoniacl.com'])->send(new NotificationMail($data));

        return redirect()->back()->with(['error' => 0, 'mensaje' => 'Firma agregada correctamente, mantenimiento '.$autorizado]);
    }

    public function autorizar_contabilidad(Request $request) {
        $date = Carbon::now('America/Bogota');
        $mantenimiento = Mantenimiento::find($request['mantenimientos_id']);

        $mantenimiento->update([
            'persona_contabilidad' => auth()->user()->name,
            'fecha_contabilidad' => $date->format('Y-m-d H:m:s'),
            'observaciones_contabilidad' => $request['observaciones'],
        ]);

        $data = [
            'titulo' => 'CONTABILIDAD AUTORIZO UN MANTENIMIENTO',
            'link' => 'https://admin.amazoniacl.com/vehiculos/ver/mantenimiento/'.$mantenimiento->id
        ];

        Mail::to('calidad@amazoniacl.com')->send(new NotificationMail($data));

        return redirect()->back()->with(['error' => 0, 'mensaje' => 'Firma agregada correctamente, autorizacion de contabilidad']);
    }

    public function eliminar_factura(Request $request) {
        Facturas_mantenimiento::find($request['id'])->delete();

        return redirect()->back()->with(['error' => 0, 'mensaje' => 'Factura eliminada correctamente']);
    }

    public function filtrar(){
        $vehiculos = Vehiculo::all();
        $users = DB::table('personal')->get();
        $solicitados = Mantenimiento::with('vehiculo')->with('personal');

        if(isset($_GET['encargado']) && $_GET['encargado'] != null){
            $solicitados = $solicitados->where('personal_id', $_GET['encargado']);
        }
        if(isset($_GET['estado']) && $_GET['estado'] != null){
            $solicitados=$solicitados->where('estado', $_GET['estado']);
        }
        if(isset($_GET['fecha']) && $_GET['fecha'] != null){
            $solicitados = $solicitados->where('fecha', 'like', $_GET['fecha']."%");
        }
        if(isset($_GET['placa']) && $_GET['placa'] != null){
            $solicitados=$solicitados->where('vehiculo_id', $_GET['placa']);
        }
        if(isset($_GET['fecha_range']) && $_GET['fecha_range'] != null){
            $desde = Str::before($_GET['fecha_range'], ' - ').' 00:00:00';
            $hasta = Str::after($_GET['fecha_range'], ' - ').' 23:59:00';
            $solicitados = $solicitados->whereBetween('fecha', [$desde, $hasta]);
        }

        if(isset($_GET['search']) && $_GET['search']!=null){
            $solicitados = $solicitados->where('descripcion_solicitud', 'like', '%'.$_GET['search'] . '%');
        }

        if(isset($_GET['ordenarpor']) && $_GET['ordenarpor']!=null){
            if($_GET['ordenarpor'] == 'placa'){
                $solicitados = $solicitados->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.vehiculo_id');
                $solicitados = $solicitados->orderBy('vehiculos.placa');
            }else if($_GET['ordenarpor'] == 'encargado'){
                $solicitados = $solicitados->join('personal', 'personal.id', '=', 'mantenimientos.personal_id');
                $solicitados = $solicitados->orderBy('personal.nombres');
            }else{
                $solicitados = $solicitados->orderBy('fecha');
            }
        }

        $solicitados=$solicitados->paginate(10);

        return view('vehiculos.mantenimientos.index', ['vehiculos' => $vehiculos, 'solicitados' => $solicitados, 'usuarios' => $users]);
    }
}
