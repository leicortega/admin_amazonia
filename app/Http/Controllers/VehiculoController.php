<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Http\Request;

use App\Models\Documentos_legales_vehiculo;
use App\Models\Conductores_vehiculo;
use App\Models\Hallazgos_inspeccion;
use App\Models\Cargos_personal;
use App\Models\Personal;
use App\Models\Vehiculo;

class VehiculoController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $propietarios = Cargos_personal::join('cargos', 'cargos.id', '=', 'cargos_personal.cargos_id')
                        ->join('personal', 'personal.id', '=', 'cargos_personal.personal_id')
                        ->select('personal.id', 'personal.nombres', 'personal.primer_apellido', 'personal.segundo_apellido')
                        ->where('cargos.nombre', 'Propietario')
                        ->get();

        if (auth()->user()->hasRole('general')) {
            $vehiculos = Vehiculo::join('tipo_vehiculo', 'tipo_vehiculo.id', '=', 'vehiculos.tipo_vehiculo_id')
                        ->join('personal', 'personal.id', '=', 'vehiculos.personal_id')
                        ->join('marca', 'marca.id', '=', 'vehiculos.marca_id')
                        ->join('conductores_vehiculo', 'vehiculos.id', '=', 'conductores_vehiculo.vehiculo_id')
                        ->select('vehiculos.id as id_vehiculo', 'vehiculos.*', 'marca.nombre as nombre_marca', 'tipo_vehiculo.nombre as nombre_tipo_vehiculo', 'personal.*')
                        ->where('conductores_vehiculo.personal_id', \App\Models\Personal::where('identificacion', auth()->user()->identificacion)->first()->id)
                        ->paginate(10);
        } else {
            $vehiculos = Vehiculo::join('tipo_vehiculo', 'tipo_vehiculo.id', '=', 'vehiculos.tipo_vehiculo_id')
                        ->join('personal', 'personal.id', '=', 'vehiculos.personal_id')
                        ->join('marca', 'marca.id', '=', 'vehiculos.marca_id')
                        ->select('vehiculos.id as id_vehiculo', 'vehiculos.*', 'marca.nombre as nombre_marca', 'tipo_vehiculo.nombre as nombre_tipo_vehiculo', 'personal.*')
                        ->paginate(10);
        }


        return view('vehiculos.index', ['propietarios' => $propietarios, 'vehiculos' => $vehiculos]);
    }

    public function create(Request $request) {
        $vehiculo = Vehiculo::create($request->all());

        if ($vehiculo->save()) {
            return redirect()->route('ver-vehiculo', ['id' => $vehiculo->id]);
        }
    }

    public function update(Request $request) {
        $vehiculo = Vehiculo::find($request['id']);

        $vehiculo->update($request->all());

        return redirect()->route('ver-vehiculo', ['id' => $vehiculo->id, 'update' => 1]);
    }

    public function ver(Request $request) {
        $propietarios = DB::table('cargos_personal')
                        ->join('cargos', 'cargos.id', '=', 'cargos_personal.cargos_id')
                        ->join('personal', 'personal.id', '=', 'cargos_personal.personal_id')
                        ->select('personal.id', 'personal.nombres', 'personal.primer_apellido', 'personal.segundo_apellido')
                        ->where('cargos.nombre', 'Propietario')
                        ->get();

        $conductores = Cargos_personal::with('personal')->with('cargos')->whereHas('cargos', function($query) {
            $query->where('cargos.nombre', 'Conductor');
        })->get();

        $alerta_documentos = Documentos_legales_vehiculo::where('vehiculo_id', $request['id'])->whereNotNull('fecha_fin_vigencia')->where('ultimo', 1)->orderBy('fecha_fin_vigencia', 'desc')->get();

        return view('vehiculos.ver', ['vehiculo' => Vehiculo::find($request['id']), 'conductores' => $conductores, 'propietarios' => $propietarios, 'alerta_documentos' => $alerta_documentos]);
    }

    public function agg_conductor(Request $request) {
        Conductores_vehiculo::create([
            'personal_id' => $request['conductor'],
            'vehiculo_id' => $request['vehiculo_id'],
            'fecha_inicial' => $request['fecha_inicial'],
            'fecha_final' => $request['fecha_final']
        ])->save();

        return $request['vehiculo_id'];
    }

    public function cargar_conductores(Request $request) {
        $conductores = Conductores_vehiculo::with('personal')
            ->whereRaw('id IN (select MAX(id) FROM conductores_vehiculo where vehiculo_id = "' . $request->id. '" GROUP BY personal_id)')
            ->orderBy('created_at','desc')
            ->get();
        return $conductores;
    }

    public function ver_conductor_historial(Request $request) {
       return Conductores_vehiculo::with('personal')->where('personal_id', "$request->id")->where('vehiculo_id', "$request->vehiculo_id")->orderBy('id', 'desc')->get();
    }
    

    public function eliminar_conductor(Request $request) {
        Conductores_vehiculo::find($request['id'])->delete();
        return $request;
    }

    public function agg_targeta_propiedad(Request $request) {
        if ($request['id']) {
            $date = Carbon::now('America/Bogota');

            $documento = Documentos_legales_vehiculo::find($request['id']);

            $documento->update([
                'consecutivo' => $request['consecutivo'],
                'fecha_expedicion' => $request['fecha_expedicion'],
                'fecha_inicio_vigencia' => $request['fecha_inicio_vigencia'],
                'fecha_fin_vigencia' => $request['fecha_fin_vigencia'],
                'entidad_expide' => $request['entidad_expide'],
                'estado' => 'activo',
                'vehiculo_id' => $request['vehiculo_id'],
            ]);

            if ($request->file('documento_file')) {
                $extension_file_documento = pathinfo($request->file('documento_file')->getClientOriginalName(), PATHINFO_EXTENSION);
                $ruta_file_documento = 'docs/vehiculos/documentos/';
                $nombre_file_documento = 'documento_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
                Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('documento_file')));

                $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;

                $documento->update([
                    'documento_file' => $nombre_completo_file_documento
                ]);
            }

            return ['tipo' => $documento->tipo, 'vehiculo_id' => $request['vehiculo_id'], 'id_table' => $request['id_table']];
        } else {
            $date = Carbon::now('America/Bogota');

            Documentos_legales_vehiculo::where('tipo', $request['tipo'])->where('vehiculo_id', $request['vehiculo_id'])->update(['ultimo' => 0]);

            $documento = Documentos_legales_vehiculo::create([
                'tipo' => $request['tipo'],
                'consecutivo' => $request['consecutivo'],
                'fecha_expedicion' => $request['fecha_expedicion'],
                'fecha_inicio_vigencia' => $request['fecha_inicio_vigencia'],
                'fecha_fin_vigencia' => $request['fecha_fin_vigencia'],
                'entidad_expide' => $request['entidad_expide'],
                'estado' => 'activo',
                'ultimo' => 1,
                'vehiculo_id' => $request['vehiculo_id'],
            ]);

            if ($request->file('documento_file')) {
                $extension_file_documento = pathinfo($request->file('documento_file')->getClientOriginalName(), PATHINFO_EXTENSION);
                $ruta_file_documento = 'docs/vehiculos/documentos/';
                $nombre_file_documento = 'documento_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
                Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('documento_file')));

                $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;

                $documento['documento_file'] = $nombre_completo_file_documento;
            }

            if ( $documento->save() ) {
                return ['tipo' => $request['tipo'], 'vehiculo_id' => $request['vehiculo_id'], 'id_table' => $request['id_table']];
            }

            return 0;
        }
    }

    public function cargar_tarjeta_propiedad(Request $request) {
        return Documentos_legales_vehiculo::where('vehiculo_id', $request['vehiculo_id'])->where('tipo', $request['tipo'])->get();
    }

    public function eliminar_documento_legal(Request $request) {
        Documentos_legales_vehiculo::find($request['id'])->delete();
        return ['tipo' => $request['tipo'], 'vehiculo_id' => $request['vehiculo_id']];
    }

    public function get_documento_legal(Request $request) {
        return Documentos_legales_vehiculo::find($request['id']);
    }

    public function trazabilidad_inspecciones(Request $request, $id) {
        $trazabilidad = Hallazgos_inspeccion::where('vehiculos_id', $request['id'])->with('inspeccion')->with('mantenimiento')->orderBy('created_at', 'desc')->paginate(10);

        return view('vehiculos.trazabilidad_inspecciones', ['trazabilidad' => $trazabilidad, 'id' => $id]);
    }


    public function filtrar(){
        $propietarios = Cargos_personal::join('cargos', 'cargos.id', '=', 'cargos_personal.cargos_id')
                        ->join('personal', 'personal.id', '=', 'cargos_personal.personal_id')
                        ->select('personal.id', 'personal.nombres', 'personal.primer_apellido', 'personal.segundo_apellido')
                        ->where('cargos.nombre', 'Propietario')
                        ->get();

        if (auth()->user()->hasRole('general')) {
            $vehiculos = Vehiculo::join('tipo_vehiculo', 'tipo_vehiculo.id', '=', 'vehiculos.tipo_vehiculo_id')
                        ->join('personal', 'personal.id', '=', 'vehiculos.personal_id')
                        ->join('marca', 'marca.id', '=', 'vehiculos.marca_id')
                        ->join('conductores_vehiculo', 'vehiculos.id', '=', 'conductores_vehiculo.vehiculo_id')
                        ->select('vehiculos.id as id_vehiculo', 'vehiculos.*', 'marca.nombre as nombre_marca', 'tipo_vehiculo.nombre as nombre_tipo_vehiculo', 'personal.*')
                        ->where('conductores_vehiculo.personal_id', \App\Models\Personal::where('identificacion', auth()->user()->identificacion)->first()->id);
        } else {
            $vehiculos = Vehiculo::join('tipo_vehiculo', 'tipo_vehiculo.id', '=', 'vehiculos.tipo_vehiculo_id')
                        ->join('personal', 'personal.id', '=', 'vehiculos.personal_id')
                        ->join('marca', 'marca.id', '=', 'vehiculos.marca_id')
                        ->select('vehiculos.id as id_vehiculo', 'vehiculos.*', 'marca.nombre as nombre_marca', 'tipo_vehiculo.nombre as nombre_tipo_vehiculo', 'personal.*');
        }

        if(isset($_GET['propietario']) && $_GET['propietario'] != null){
            $vehiculos = $vehiculos->where('personal_id', $_GET['propietario']);
        }
        if(isset($_GET['tipo']) && ($_GET['tipo']) != null){
            $vehiculos = $vehiculos->where('tipo_vehiculo_id',$_GET['tipo']);
        }
        if(isset($_GET['marca']) && ($_GET['marca']) !=  null){
            $vehiculos = $vehiculos->where('marca_id',$_GET['marca']);
        }
        if(isset($_GET['search']) && ($_GET['search']) != null){
            $vehiculos = $vehiculos->where('placa', 'like', "%".$_GET['search']."%");
            $vehiculos = $vehiculos->orWhere('numero_interno', 'like', "%".$_GET['search']."%");
        }
        if(isset($_GET['ordenarpor']) && ($_GET['ordenarpor']) != null){
            $vehiculos = $vehiculos->orderBy($_GET['ordenarpor']);
        }

        $vehiculos = $vehiculos->paginate(10);

        return view('vehiculos.index', ['propietarios' => $propietarios, 'vehiculos' => $vehiculos]);
    }

}
