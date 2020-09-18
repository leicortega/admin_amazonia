<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Http\Request;

use App\Models\Documentos_legales_vehiculo;
use App\Models\Conductores_vehiculo;
use App\Models\Cargos_personal;
use App\Models\Vehiculo;

class VehiculoController extends Controller
{
    public function index() {
        $propietarios = DB::table('cargos_personal')
                        ->join('cargos', 'cargos.id', '=', 'cargos_personal.cargos_id')
                        ->join('personal', 'personal.id', '=', 'cargos_personal.personal_id')
                        ->select('personal.id', 'personal.nombres', 'personal.primer_apellido', 'personal.segundo_apellido')
                        ->where('cargos.nombre', 'Propietario')
                        ->get();

        $vehiculos = DB::table('vehiculos')
                        ->join('tipo_vehiculo', 'tipo_vehiculo.id', '=', 'vehiculos.tipo_vehiculo_id')
                        ->join('personal', 'personal.id', '=', 'vehiculos.personal_id')
                        ->join('marca', 'marca.id', '=', 'vehiculos.marca_id')
                        ->select('vehiculos.*', 'marca.nombre as nombre_marca', 'tipo_vehiculo.nombre as nombre_tipo_vehiculo', 'personal.*')
                        ->get();

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

        return view('vehiculos.ver', ['vehiculo' => Vehiculo::find($request['id']), 'conductores' => $conductores, 'propietarios' => $propietarios]);
    }

    public function agg_conductor(Request $request) {
        Conductores_vehiculo::create([
            'personal_id' => $request['conductor'],
            'vehiculo_id' => $request['vehiculo_id'],
        ])->save();

        return $request['vehiculo_id'];
    }

    public function cargar_conductores(Request $request) {
        return Conductores_vehiculo::where('vehiculo_id', $request['id'])->with('personal')->get();
    }

    public function eliminar_conductor(Request $request) {
        Conductores_vehiculo::find($request['id'])->delete();
        return $request['vehiculo_id'];
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

            return ['tipo' => $documento->tipo, 'vehiculo_id' => $request['vehiculo_id']];
        } else {
            $date = Carbon::now('America/Bogota');

            $documento = Documentos_legales_vehiculo::create([
                'tipo' => $request['tipo'],
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

                $documento['documento_file'] = $nombre_completo_file_documento;
            }

            if ( $documento->save() ) {
                return ['tipo' => $request['tipo'], 'vehiculo_id' => $request['vehiculo_id']];
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
}
