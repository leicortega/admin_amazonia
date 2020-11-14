<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detalle_tarea;
use App\Models\Tarea;
Use  App\User;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;
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
        if ($request->file('adjunto')) {
            $extension_file_documento = pathinfo($request->file('adjunto')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/tareas/adjuntos/';
            $nombre_file_documento = 'tarea_'.$this->date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('adjunto')));

            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;
        }

        $tarea = Tarea::create([
            'fecha' => $this->date->format('Y-m-d'),
            'tarea' => $request['tarea'],
            'fecha_limite' => $request['fecha_limite'],
            'estado' => 'Asignada',
            'adjunto' => $nombre_completo_file_documento ?? null,
            'supervisor' => auth()->user()->id,
            'asignado' => $request['asignado'],
        ]);

        $data = [
            'titulo' => 'NUEVA TAREA ASIGNADA',
            'link' => 'https://admin.amazoniacl.com/tareas/ver/'.$tarea->id
        ];

        Mail::to(User::find($request['asignado'])->email)->send(new NotificationMail($data));

        if ($tarea->save()) {
            return redirect()->back()->with(['create' => 1, 'mensaje' => 'Tarea asignada correctamente']);
        }

        return redirect()->back()->with(['create' => 0, 'mensaje' => 'Ocurrio un error, intente de nuevo']);

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
}
