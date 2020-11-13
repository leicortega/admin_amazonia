<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mail\NotificationMail;
use Carbon\Carbon;
use PDF;

use App\Models\Vehiculo;
use App\Models\Inspeccion;
use App\Models\Detalle_inspeccion;
use App\Models\Sistema\Admin_inspeccion;
use App\Models\Adjuntos_inspeccion;

class InspeccionesController extends Controller
{
    public $date;

    public function __construct() {
        $this->date = Carbon::now('America/Bogota');
    }

    public function index() {
        $vehiculos = Vehiculo::all();
        $inspecciones = Inspeccion::with('users')->with('vehiculo')->with('detalle')->with('adjuntos')->paginate(10);

        return view('vehiculos.inspecciones.index', ['vehiculos' => $vehiculos, 'inspecciones' => $inspecciones]);
    }

    public function inspecciones_vehiculo(Request $request, $id) {
        $vehiculos = Vehiculo::all();
        $inspecciones = Inspeccion::where('vehiculo_id', $id)->with('vehiculo')->with('detalle')->with('adjuntos')->paginate(10);

        return view('vehiculos.inspecciones.index', ['vehiculos' => $vehiculos, 'inspecciones' => $inspecciones]);
    }

    public function agregar_view(Request $request) {
        $vehiculos = Vehiculo::all();
        $admin_inspecciones = Admin_inspeccion::all();

        return view('vehiculos.inspecciones.agregar', ['vehiculos' => $vehiculos, 'admin_inspecciones' => $admin_inspecciones]);
    }

    public function agregar(Request $request) {
        $inspeccion = Inspeccion::create([
            'fecha_inicio' => $request->fecha_inicio,
            'kilometraje_inicio' => $request->kilometraje_inicio,
            'observaciones_inicio' => $request->observaciones_inicio,
            'users_id' => auth()->user()->id,
            'vehiculo_id' => $request->vehiculo_id,
        ]);

        if ($inspeccion->save()) {
            for ($i=0; $i <= $request->total; $i++) {
                Detalle_inspeccion::create([
                    'campo' => $request['campo_'.$i],
                    'cantidad' => $request['cantidad_'.$i],
                    'estado' => $request['estado_'.$i],
                    'admin_inspecciones_id' => $request['id_'.$i],
                    'inspecciones_id' => $inspeccion->id
                ]);
            }
        }

        $data = [
            'titulo' => 'NUEVA INSPECCIÓN AGREGADA',
            'link' => 'https://admin.amazoniacl.com/vehiculos/inspecciones/ver/'.$inspeccion->id
        ];

        Mail::to('leicortega@gmail.com')->send(new NotificationMail($data));
        // Mail::to('calidad@amazoniacl.com')->send(new NotificationMail($data));

        return redirect()->route('ver_inspeccion', $inspeccion->id)->with(['error' => 0, 'mensaje' => 'Inspeccion agregada correctamente']);
    }

    public function ver(Request $request) {
        $inspeccion = Inspeccion::with('users')->with('vehiculo')->with('detalle')->with('adjuntos')->find($request->id);

        return view('vehiculos.inspecciones.ver', ['inspeccion' => $inspeccion]);
    }

    public function agregar_adjunto(Request $request) {
        if ($request->file('adjunto')) {
            $extension_file_documento = pathinfo($request->file('adjunto')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/vehiculos/inspecciones/';
            $nombre_file_documento = 'inspeccion_'.$this->date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('adjunto')));

            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;
        }

        $adjunto = Adjuntos_inspeccion::create([
            'elemento' => $request->elemento,
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
}
