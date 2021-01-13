<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mail\NotificationMail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PDF;

use App\Models\Personal;
use App\Models\Vehiculo;
use App\Models\Inspeccion;
use App\Models\Mantenimiento;
use App\Models\Detalle_inspeccion;
use App\Models\Sistema\Admin_inspeccion;
use App\Models\Adjuntos_inspeccion;
use App\Models\Hallazgos_inspeccion;

class InspeccionesController extends Controller
{
    public $date;

    public function __construct() {
        $this->date = Carbon::now('America/Bogota');
        $this->middleware('auth');
    }

    public function index() {
        $vehiculos = Vehiculo::all();
        $users=DB::table('users')->get();
        $inspecciones = Inspeccion::with('users')->with('vehiculo')->with('detalle')->with('adjuntos')->paginate(10);
        

        return view('vehiculos.inspecciones.index', ['vehiculos' => $vehiculos, 'inspecciones' => $inspecciones, 'usuarios' => $users]);
    }

    // public function inspecciones_vehiculo(Request $request, $id) {
    //     $vehiculos = Vehiculo::all();
    //     $users=DB::table('users')->get();
    //     $inspecciones = Inspeccion::where('vehiculo_id', $id)->with('vehiculo')->with('detalle')->with('adjuntos')->paginate(10);

    //     return view('vehiculos.inspecciones.index', ['vehiculos' => $vehiculos, 'inspecciones' => $inspecciones, 'usuarios' => $users]);
    // }

    public function agregar_view(Request $request) {
        $vehiculos = Vehiculo::all();
        $admin_inspecciones = Admin_inspeccion::all();

        return view('vehiculos.inspecciones.agregar', ['vehiculos' => $vehiculos, 'admin_inspecciones' => $admin_inspecciones]);
    }

    public function agregar(Request $request) {
        $novedades = [];
        $count = 0;

        $inspeccion = Inspeccion::create([
            'fecha_inicio' => $request->fecha_inicio,
            'kilometraje_inicio' => $request->kilometraje_inicio,
            'observaciones_inicio' => $request->observaciones_inicio,
            'users_id' => auth()->user()->id,
            'vehiculo_id' => $request->vehiculo_id,
        ]);

        if ($inspeccion->save()) {
            for ($i=0; $i <= $request->total; $i++) {
                if ($request['estado_'.$i] == 'Regular' || $request['estado_'.$i] == 'Malo') {
                    array_push($novedades, ['elemento' => $request['campo_'.$i], 'estado' => $request['estado_'.$i]]);
                    $count++;
                }
                Detalle_inspeccion::create([
                    'campo' => $request['campo_'.$i],
                    'cantidad' => $request['cantidad_'.$i],
                    'estado' => $request['estado_'.$i],
                    'admin_inspecciones_id' => $request['id_'.$i],
                    'inspecciones_id' => $inspeccion->id
                ]);
            }
        }

        if ($count > 0) {
            $descripcion = 'Mantenimiento generado automaticamente de acuerdo a la inspeccion #'.$inspeccion->id.' en la cual se encontraron las siguientes novedades: ';

            foreach ($novedades as $novedad) {
                $descripcion .= $novedad['elemento'].' en estado '.$novedad['estado'].', ';
            }

            $descripcion .= 'y con las siguientes observaciones: '.$request->observaciones_inicio;

            $mantenimiento = Mantenimiento::create([
                'fecha' => $this->date->format('Y-m-d'),
                'vehiculo_id' => $request->vehiculo_id,
                'personal_id' => Personal::where('identificacion', auth()->user()->identificacion)->first()->id,
                'descripcion_solicitud' => $descripcion
            ]);

            $hallazgos_inspeccion = Hallazgos_inspeccion::create([
                'inspecciones_id' => $inspeccion->id,
                'mantenimientos_id' => $mantenimiento->id,
                'vehiculos_id' => $request->vehiculo_id
            ]);

            // $data_mantenimiento = [
            //     'titulo' => 'NUEVA SOLICITUD DE MANTENIMIENTO',
            //     'link' => 'https://admin.amazoniacl.com/vehiculos/mantenimientos/autorizar/'.$mantenimiento->id
            // ];

            // $propietario = Personal::find(Vehiculo::find($request->vehiculo_id)->personal_id)->correo;

            // Mail::to([$propietario, 'gerencia@amazoniacl.com', 'calidad@amazoniacl.com'])->send(new NotificationMail($data_mantenimiento));
        }

        // $data = [
        //     'titulo' => 'NUEVA INSPECCIÓN AGREGADA',
        //     'link' => 'https://admin.amazoniacl.com/vehiculos/inspecciones/ver/'.$inspeccion->id
        // ];

        // Mail::to('calidad@amazoniacl.com')->send(new NotificationMail($data));

        return redirect()->route('ver_inspeccion', $inspeccion->id)->with(['error' => 0, 'mensaje' => 'Inspeccion agregada correctamente']);
    }

    public function ver(Request $request) {
        $inspeccion = Inspeccion::with('users')->with('vehiculo')->with('detalle')->with('adjuntos')->find($request->id);
        $novedad = [];

        foreach ($inspeccion->detalle as $detalle) {
            if ($detalle->estado == 'Regular' || $detalle->estado == 'Malo') {
                array_push($novedad, ['elemento' => $detalle->campo, 'estado' => $detalle->estado]);
            }
        }

        // dd($inspeccion);

        return view('vehiculos.inspecciones.ver', ['inspeccion' => $inspeccion, 'novedad' => $novedad]);
    }

    public function agregar_adjunto(Request $request) {
        if ($request->file('adjunto')) {
            $extension_file_documento = pathinfo($request->file('adjunto')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/vehiculos/inspecciones/';
            $nombre_file_documento = 'inspeccion_'.$this->date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('adjunto')));

            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;
        }

        $elemento = ($request['input_elemento']) ? $request['input_elemento'] : $request['elemento'];

        $adjunto = Adjuntos_inspeccion::create([
            'elemento' => $elemento,
            'observaciones' => $request->observaciones,
            'adjunto' => $nombre_completo_file_documento,
            'inspecciones_id' => $request->inspeccion_id,
        ]);

        if ($adjunto->save()) {
            return redirect()->back()->with(['error' => 0, 'mensaje' => 'Adjunto agregado correctamente']);
        }

        return redirect()->back()->with(['error' => 1, 'mensaje' => 'Ocurrio un problema, intente de nuevo']);
    }

    public function cerrar(Request $request) {
        $inspeccion = Inspeccion::find($request->inspeccion_id);

        $inspeccion->update([
            'fecha_final' => $this->date->format('Y-m-d H:i:s'),
            'kilometraje_final' => $request->kilometraje_final,
            'observaciones_final' => $request->observaciones_final
        ]);

        if ($inspeccion->save()) {
            return redirect()->back()->with(['error' => 0, 'mensaje' => 'Inspeccion cerrada correctamente']);
        }

        return redirect()->back()->with(['error' => 1, 'mensaje' => 'Ocurrio un problema, intente de nuevo']);
    }

    public function pdf(Request $request) {
        $inspeccion = Inspeccion::with('users')->with('vehiculo')->with(array('detalle' => function ($query) {
            $query->with('admin_inspecciones');
        }))->with('adjuntos')->find($request->id);

        return PDF::loadView('vehiculos.inspecciones.pdf', compact('inspeccion'))->setPaper('A4')->stream('inspeccion.pdf');
    }

    // public function filter(Request $request) {
    //     $desde = Str::before($request['rango'], ' - ').' 00:00:00';
    //     $hasta = Str::after($request['rango'], ' - ').' 23:59:00';

    //     $vehiculos = Vehiculo::all();
    //     $users=DB::table('users')->get();
    //     $inspecciones = Inspeccion::whereBetween('fecha_inicio', [$desde, $hasta])->with('vehiculo')->with('detalle')->with('adjuntos')->paginate(10);

    //     return view('vehiculos.inspecciones.index', ['vehiculos' => $vehiculos, 'inspecciones' => $inspecciones, 'usuarios' => $users]);
    // }

    public function certificado(Request $request) {
        Inspeccion::find($request['inspeccion_id'])->update([
            'certificado' => $request['area']
        ]);

        $contenido = $request['area'];

        return PDF::loadView('vehiculos.inspecciones.certificado_inspeccion', compact('contenido'))->setPaper('A4')->stream('certificado_inspeccion.pdf');
    }

    public function certificado_view(Request $request) {
        $contenido = Inspeccion::find($request['id'])->certificado;

        return PDF::loadView('vehiculos.inspecciones.certificado_inspeccion', compact('contenido'))->setPaper('A4')->stream('certificado_inspeccion.pdf');
    }

    public function filtro(){

        $vehiculos = Vehiculo::all();
        $users=DB::table('users')->get();
        $inspecciones = Inspeccion::with('users')->with('vehiculo')->with('detalle')->with('adjuntos');

        

        if(isset($_GET['ordenarpor']) && $_GET['ordenarpor']!=null){
            $inspecciones=$inspecciones->orderBy($_GET['ordenarpor']);
        }
        if(isset($_GET['encargado']) && $_GET['encargado']!=null){
            $inspecciones=$inspecciones->where('users_id',$_GET['encargado']);
        }
        if(isset($_GET['estado']) && $_GET['estado']!=null){
            if($_GET['estado']=='true'){
                $inspecciones=$inspecciones->whereNotNull('fecha_final');
            }else{
                $inspecciones=$inspecciones->whereNull('fecha_final');
            }
        }
        if(isset($_GET['fecha']) && $_GET['fecha']!=null){
            $inspecciones=$inspecciones->where('fecha_inicio', 'like', $_GET['fecha']."%");
        }
        if(isset($_GET['placa']) && $_GET['placa']!=null){
            $inspecciones=$inspecciones->where('vehiculo_id', $_GET['placa']);
        }
        if(isset($_GET['fecha_range']) && $_GET['fecha_range']!=null){
            $desde = Str::before($_GET['fecha_range'], ' - ').' 00:00:00';
            $hasta = Str::after($_GET['fecha_range'], ' - ').' 23:59:00';
            $inspecciones=$inspecciones->whereBetween('fecha_inicio', [$desde, $hasta]);
        }
        
        $inspecciones=$inspecciones->paginate(10);
        
        return view('vehiculos.inspecciones.index', ['vehiculos' => $vehiculos, 'inspecciones' => $inspecciones, 'usuarios' => $users]);

    }
}
