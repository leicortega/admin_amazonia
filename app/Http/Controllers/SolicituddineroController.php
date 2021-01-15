<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Solicitudes_dinero;
use App\Models\Conceptos_solicitud;
use App\Models\Estados_solicitud;
use App\Models\Archivos_soportados;
use App\Models\Personal;
use Illuminate\Support\Str;
use PDF;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SolicituddineroController extends Controller
{
    public function index(){
        $beneficiarios = Personal::all();
        $solicitudes = Solicitudes_dinero::join('personal', 'personal.id', '=', 'solicitudes_dinero.beneficiario')
                ->join('users', 'users.id', '=', 'solicitudes_dinero.personal_crea')
                ->select('solicitudes_dinero.*', 'users.name', 'personal.nombres', 'personal.primer_apellido', 'personal.segundo_apellido')
                ->paginate(10);

        return view('contabilidad.solicitud',['beneficiarios' => $beneficiarios, 'solicitudes' => $solicitudes]);
    }

    public function create(Request $request){
        $solicitud = Solicitudes_dinero::create([
            'tipo_solicitud' => $request['tipo'],
            'fecha_solicitud' => $request['fecha'],
            'descripcion' => $request['descripcion_solicitud'],
            'beneficiario' => $request['beneficiario'],
            'personal_crea' => auth()->user()->id,
        ]);

        for ($i=0; $i < sizeof($request['concepto']); $i++) { 
            $conceptos = Conceptos_solicitud::create([
                'nombre' => $request['concepto'][$i], 
                'valor_entregado' => $request['precio'][$i],
                'saldo' => $request['precio'][$i],
                'solicitud_id' => $solicitud->id
            ]);
            Estados_solicitud::create([
                'estado' => 'Solicitado',
                'descripcion' => 'Solicitud de recursos',
                'users_id' => auth()->user()->id,
                'conceptos_id' => $conceptos->id
            ]);
        }

        if ($solicitud->save()) {
            return redirect()->back()->with(['create' => 1, 'mensaje' => 'Registro agregado correctamente']);
        }

        return redirect()->back()->with(['create' => 0, 'mensaje' => 'Ocurrio un error, intente de nuevo']);

    }

    public function ver($id){
        $conceptos = Conceptos_solicitud::where('solicitud_id', $id);
        $solicitud = Solicitudes_dinero::join('personal', 'personal.id', '=', 'solicitudes_dinero.beneficiario')
                ->join('users', 'users.id', '=', 'solicitudes_dinero.personal_crea')
                ->where('solicitudes_dinero.id',$id)
                ->select('solicitudes_dinero.*', 'personal.nombres', 'personal.primer_apellido', 'personal.segundo_apellido',  'users.name')->first();


        return view('contabilidad.ver_solicitud', ['solicitud' => $solicitud, 'conceptos' => $conceptos->get()]);
    }

    public function add_soporte(Request $request){
             $date = Carbon::now('America/Bogota');

            $extension_file_documento = pathinfo($request->file('imagen_soporte')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/Solicitud_dinero/';
            $nombre_file_documento = 'solicitud_concepto'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('imagen_soporte')));

            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;

            $detalle_actividad = Archivos_soportados::create([
                'archivo' => $nombre_completo_file_documento,
                'valor_soporte' => $request->soporte,
                'conceptos_solicitud_id' =>$request->id
            ]);

            $solicitud = Conceptos_solicitud::find($request->id);

            $solicitud->update([
                'valor_soportado' => ($solicitud->valor_soportado + $request->soporte),
                'saldo' => ($solicitud->saldo - $request->soporte)
            ]);

            if ($detalle_actividad->save()) {
                return redirect()->back()->with(['error' => 0, 'mensaje' => 'Soporte agregado correctamente']);
            }

            return redirect()->back()->with(['error' => 1, 'mensaje' => 'Eror en el registro']);
        
    }
    public function ver_soporte(Request $request){
        $archivos = Archivos_soportados::where('conceptos_solicitud_id', $request->id)->get();
        return $archivos;
    }

    public function add_estado(Request $request){
        $estado = Estados_solicitud::create([
            'estado' => $request->estado,
            'descripcion' => $request->descripcion,
            'users_id' => auth()->user()->id,
            'conceptos_id' => $request->id_estado
        ]);


        if ($estado->save()) {
            return redirect()->back()->with(['error' => 0, 'mensaje' => 'Estado agregado correctamente']);
        }

        return redirect()->back()->with(['error' => 1, 'mensaje' => 'Eror en el registro']);

    }

    public function ver_estado(Request $request){
        $estados = Estados_solicitud::where('conceptos_id', $request->id)
            ->join('users', 'users.id', '=', 'estados_solicitud.users_id')
            ->select('estados_solicitud.*', 'users.name')
            ->get();
        return $estados;
    }

    public function print($id) {
        $conceptos = Conceptos_solicitud::where('solicitud_id', $id)->get();
        $solicitud = Solicitudes_dinero::join('personal', 'personal.id', '=', 'solicitudes_dinero.beneficiario')
                ->join('users', 'users.id', '=', 'solicitudes_dinero.personal_crea')->where('solicitudes_dinero.id',$id)->first();
                
        return PDF::loadView('contabilidad.solicitudes_pdf', compact('solicitud', 'conceptos'))->setPaper('A4')->stream('solicitud.pdf');
    }


    public function filtro(){
        $beneficiarios = Personal::all();
        $solicitud = Solicitudes_dinero::join('personal', 'personal.id', '=', 'solicitudes_dinero.beneficiario')
                ->join('users', 'users.id', '=', 'solicitudes_dinero.personal_crea')
                ->select('solicitudes_dinero.*', 'users.name', 'personal.nombres', 'personal.primer_apellido', 'personal.segundo_apellido');
        

        if(isset($_GET['tipo']) && $_GET['tipo'] != null){
            $solicitud = $solicitud->where('tipo_solicitud', $_GET['tipo']);
        }
        if(isset($_GET['solicitante']) && $_GET['solicitante'] != null){
            $solicitud = $solicitud->where('users.id', $_GET['solicitante']);
        }
        if(isset($_GET['beneficiario']) && $_GET['beneficiario'] != null){
            $solicitud = $solicitud->where('personal.id', $_GET['beneficiario']);
        }
        if(isset($_GET['fecha']) && $_GET['fecha'] != null){
            $solicitud = $solicitud->where('fecha_solicitud', $_GET['fecha'] . "%");
        }
        if(isset($_GET['fecha_range']) && $_GET['fecha_range'] != null){
            $desde = Str::before($_GET['fecha_range'], ' - ').' 00:00:00';
            $hasta = Str::after($_GET['fecha_range'], ' - ').' 23:59:00';
            $solicitud = $solicitud->whereBetween('fecha_solicitud', [$desde, $hasta]);
        }

        if(isset($_GET['search']) && $_GET['search']!=null){
            $solicitud = $solicitud->where('descripcion', 'like', '%'.$_GET['search'] . '%');
            $solicitud = $solicitud->orwhere('personal.nombres', 'like', '%'.$_GET['search'] . '%');
            $solicitud = $solicitud->orwhere('users.name', 'like', '%'.$_GET['search'] . '%');
            $solicitud = $solicitud->orwhere('personal.primer_apellido', 'like', '%'.$_GET['search'] . '%');
            $solicitud = $solicitud->orwhere('personal.segundo_apellido', 'like', '%'.$_GET['search'] . '%');
            $solicitud = $solicitud->orwhere('fecha_solicitud', 'like', '%'.$_GET['search'] . '%');
        }

        if(isset($_GET['ordenarpor']) && $_GET['ordenarpor']!=null){
                $solicitud = $solicitud->orderBy($_GET['ordenarpor']);
        }

        $solicitud = $solicitud->paginate(10);

        return view('contabilidad.solicitud', ['solicitudes' => $solicitud, 'beneficiarios' => $beneficiarios]);

    }
    

}
