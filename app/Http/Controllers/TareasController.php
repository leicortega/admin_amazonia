<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documentos_legales_vehiculo;
use App\Models\Detalle_tarea;
use App\Models\Tarea;
Use App\User;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;
use App\Models\Documentos_documentacion;
use Carbon\Carbon;

class TareasController extends Controller
{
    public $date;

    public function __construct() {
        $this->date = Carbon::now('America/Bogota');
        $this->middleware('auth');
    }

    public function index() {
        $tareas = Tarea::where('estado', '<>', 'Completada')->where('asignado', auth()->user()->id)->with('supervisor_id')->with('asignado_id')->paginate(10);

        return view('tareas.index', ['tareas' => $tareas]);
    }

    public function asignadas() {
        $tareas = Tarea::where('estado', '<>', 'Completada')->where('supervisor', auth()->user()->id)->with('supervisor_id')->with('asignado_id')->paginate(10);

        return view('tareas.index', ['tareas' => $tareas]);
    }

    public function completadas() {
        $tareas = Tarea::where('estado', 'Completada')->where(function ($q) {
            $q->where('supervisor', auth()->user()->id)->orWhere('asignado', auth()->user()->id);
        })->with('supervisor_id')->with('asignado_id')->paginate(10);

        return view('tareas.index', ['tareas' => $tareas]);
    }

    public function agregar(Request $request) {

        $nombre_completo_file_documento='';

        if ($request->file('adjunto')) {
            $extension_file_documento = pathinfo($request->file('adjunto')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/tareas/adjuntos/';
            $nombre_file_documento = 'tarea_'.$this->date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('adjunto')));

            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;
        }
        $fecha='';

            if(isset($request['fecha'])){
                $fecha=$request['fecha'] . ' ' . ($request['time_fecha'] ?? '00:00') . ':00';
            }else{
                $fecha= Carbon::now()->format('Y-m-d H:m:s');
            }

            if(isset($request['id_editar']) && $request['id_editar'] != null){

                $tarea = Tarea::find($request['id_editar']);

                $tarea->update([
                    'fecha' => $fecha,
                    'name_tarea' => $request['name_tarea'],
                    'tarea' => $request['tarea'],
                    'fecha_limite' => $request['fecha_limite'] . ' ' . ($request['time_fecha_final'] ?? '00:00')  . ':00',
                    'supervisor' => auth()->user()->id,
                    'asignado' => $request['asignado'] ?? auth()->user()->id,
                ]);

                if($nombre_completo_file_documento){
                    Storage::disk('public')->delete($tarea->adjunto);
                    $tarea->update([
                        'adjunto' => $nombre_completo_file_documento
                    ]);
                }

                $data = [
                    'titulo' => 'TAREA ACTUALIZADA',
                    'link' => 'https://admin.amazoniacl.com/tareas/ver/'.$tarea->id
                ];

                Mail::to(User::find($request['asignado'])->email ?? auth()->user()->email)->send(new NotificationMail($data));

                if ($tarea->save()) {
                    return redirect()->back()->with(['create' => 1, 'mensaje' => 'Tarea Actualizada correctamente']);
                }

                return redirect()->back()->with(['create' => 0, 'mensaje' => 'Ocurrio un error, intente de nuevo']);

            }else{

                $tarea = Tarea::create([
                    'fecha' => $fecha,
                    'name_tarea' => $request['name_tarea'],
                    'tarea' => $request['tarea'],
                    'fecha_limite' => $request['fecha_limite'] . ' ' . ($request['time_fecha_final'] ?? '00:00')  . ':00',
                    'estado' => 'Asignada',
                    'adjunto' => $nombre_completo_file_documento ?? null,
                    'tipos_tareas_id'=>'1',
                    'supervisor' => auth()->user()->id,
                    'asignado' => $request['asignado'] ?? auth()->user()->id,
                ]);

                $data = [
                    'titulo' => 'NUEVA TAREA ASIGNADA',
                    'link' => 'https://admin.amazoniacl.com/tareas/ver/'.$tarea->id
                ];

                Mail::to(User::find($request['asignado'])->email ?? auth()->user()->email)->send(new NotificationMail($data));

                if ($tarea->save()) {
                    return redirect()->back()->with(['create' => 1, 'mensaje' => 'Tarea asignada correctamente']);
                }

                return redirect()->back()->with(['create' => 0, 'mensaje' => 'Ocurrio un error, intente de nuevo']);
            }




    }

    public function ver(Request $request) {
        $tarea = Tarea::with('detalle_tareas')->with('supervisor_id')->with('asignado_id')->find($request['id']);

        return view('tareas.ver', ['tarea' => $tarea]);
    }

    public function agregar_estado (Request $request) {
        if ($request->file('adjunto')) {
            $extension_file_documento = pathinfo($request->file('adjunto')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/tareas/adjuntos/';
            $nombre_file_documento = 'tarea_'.$this->date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('adjunto')));

            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;
        }

        $tarea = Detalle_tarea::create([
            'fecha' => $this->date->format('Y-m-d H:i:s'),
            'estado' => $request['estado'],
            'observaciones' => $request['observaciones'],
            'adjunto' => $nombre_completo_file_documento ?? null,
            'tareas_id' => $request['tarea_id'],
            'users_id' => auth()->user()->id,
        ]);

        Tarea::find($request['tarea_id'])->update([
            'estado' => $request['estado']
        ]);

        $data = [
            'titulo' => 'NUEVO ESTADO EN TAREA',
            'link' => 'https://admin.amazoniacl.com/tareas/ver/'.$request['tarea_id']
        ];

        Mail::to(User::find(Tarea::find($request['tarea_id'])->supervisor)->email)->send(new NotificationMail($data));

        if ($tarea->save()) {
            return redirect()->back()->with(['create' => 1, 'mensaje' => 'Estado agregado correctamente']);
        }

        return redirect()->back()->with(['create' => 0, 'mensaje' => 'Ocurrio un error, intente de nuevo']);

    }

    public function calendario(){
        return view('tareas.Calendario.index');
    }

    public function cambiar_tipo_eventos_calendario(Request $request) {
        return session(['tipo_tarea' => $request['tipo']]);
    }

    public function cargar_calendario(Request $request){

        $response = [];

        if(session('tipo_tarea')) {
            switch (session('tipo_tarea')) {
                case 'tareas':
                    $response['tipo'] = 'tarea';
                    $response['documentos'] = Tarea::where('asignado', auth()->user()->id)->orwhere('supervisor', auth()->user()->id)->get();
                    return $response;
                    break;
                case 'Documentos Vehiculos':
                    $response['documentos'] = DB::table('documentos_legales_vehiculos')
                                ->join('admin_documentos_vehiculo', 'admin_documentos_vehiculo.id', 'documentos_legales_vehiculos.tipo_id')
                                ->join('vehiculos', 'vehiculos.id', 'documentos_legales_vehiculos.vehiculo_id')
                                ->select('documentos_legales_vehiculos.*', 'admin_documentos_vehiculo.name', 'vehiculos.placa')
                                ->whereNotNull('fecha_fin_vigencia')
                                ->where('ultimo', 1)
                                ->orderBy('fecha_fin_vigencia', 'desc')
                                ->get();

                    $response['tipo'] = 'Documentos Vehiculos';

                    return $response;
                    break;
                case 'Documentos Administración':
                    $response['documentos'] = Documentos_documentacion::join('documentacion', 'documentacion.id', '=', 'documentos_documentacion.documentacion_id')
                        ->select('documentos_documentacion.*', 'documentacion.nombre as name')
                        ->whereNotNull('fecha_fin_vigencia')
                        ->orderBy('fecha_fin_vigencia', 'desc')->get();
                    $response['tipo'] = 'Documentos Administración';
                    return $response;
                    break;
                default:
                    # code...
                    break;
            }
        } else {
            switch ($request['list']) {
                case 0:
                    $response['tipo'] = 'tarea';
                    $response['documentos'] = Tarea::where('asignado', auth()->user()->id)->orwhere('supervisor', auth()->user()->id)->get();
                    return $response;
                    break;

                case 1:
                    $response['tipo'] = 'tarea';
                    $response['documentos'] = Tarea::where([['supervisor', auth()->user()->id], ['asignado', auth()->user()->id]])->get();
                    return $response;
                    break;

                case 2:
                    $response['tipo'] = 'tarea';
                    $response['documentos'] = Tarea::where([['supervisor', auth()->user()->id], ['asignado', '<>',auth()->user()->id]])->get();
                    return $response;
                    break;

                case 3:
                    $response['tipo'] = 'tarea';

                    $response['documentos'] = Tarea::where([['asignado', auth()->user()->id], ['supervisor', '<>',auth()->user()->id]])->get();
                    return $response;
                    break;
                default:
                    $response['tipo'] = 'tarea';
                    $response['documentos'] = Tarea::where('asignado', auth()->user()->id)->orwhere('supervisor', auth()->user()->id)->get();
                    return $response;
                    break;
            }
        }
    }

    public function eliminate_tarea(Request $request){
        $tarea=Tarea::find($request['id']);
        Storage::disk('public')->delete($tarea->adjunto);
        $tarea->delete();
        return redirect()->back()->with(['create' => 1, 'mensaje' => 'Tarea Eliminada correctamente']);

    }

    public function vercalendario_tarea(Request $request){
        switch($request['tipo']){
            case 'tarea':
                return Tarea::with('supervisor_id')->with('asignado_id')->find($request['id']);
                break;
            case 'Documentos Vehiculos':
                return Documentos_legales_vehiculo::with('vehiculo')->with('tipo')->find($request['id']);
                break;
            case 'Documentos Administración':
                return Documentos_documentacion::with('documentacion')->find($request['id']);
                break;
        }

    }
}
