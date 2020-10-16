<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Tercero;
use App\Models\Perfiles_tercero;
use App\Models\Contactos_tercero;
use App\Models\Documentos_tercero;
use App\Models\Cotizacion;
use App\Models\Personal;
use App\Models\Vehiculo;
use Carbon\Carbon;
use PDF;

class TercerosController extends Controller
{
    public function index() {
        $terceros = Tercero::paginate(20);

        return view('terceros.index', ['terceros' => $terceros]);
    }

    public function create(Request $request) {
        $this->validate($request, [
            'identificacion' => 'unique:terceros,identificacion'
        ], $messages = [
            'unique'    => 'La :attribute ya esta registrada'
        ]);

        $tercero = Tercero::create($request->all());
        $perfil = Perfiles_tercero::create(['nombre' => $request['tipo_tercero'], 'terceros_id' => $tercero->id]);

        if ( $tercero->save() ) {
            return redirect()->route('terceros')->with('tercero', 1);
        } else {
            return redirect()->route('terceros')->with('tercero', 0);
        }
    }

    public function ver(Request $request) {
        $tercero = Tercero::where('id', $request['id'])->with('perfiles_terceros')->get();

        $personal = Personal::where('identificacion', auth()->user()->identificacion)->with(array('cargos_personal' => function ($query) {
            $query->with('cargos');
        }))->first();

        // dd($personal);

        return view('terceros.ver', ['tercero' => $tercero, 'personal' => $personal]);
    }

    public function agg_contacto(Request $request) {
        Contactos_tercero::create([
            'identificacion' => $request['identificacion_contacto'],
            'nombre' => $request['nombre_contacto'],
            'telefono' => $request['telefono_contacto'],
            'correo' => $request['correo_contacto'],
            'terceros_id' => $request['terceros_id'],
        ])->save();

        return $request['terceros_id'];
    }

    public function cargar_contactos(Request $request) {
        return Contactos_tercero::where('terceros_id', $request['id'])->orWhere('identificacion', $request['responsable'])->get();
    }

    public function eliminar_contacto(Request $request) {
        $id = Contactos_tercero::find($request['id'])->terceros_id;

        Contactos_tercero::find($request['id'])->delete();

        return $id;
    }

    public function cargar_responsable_contrato(Request $request) {
        return Contactos_tercero::where('identificacion', $request['responsable'])->first();
    }

    public function agg_perfil_tercero(Request $request) {
        if ( Perfiles_tercero::create($request->all())->save() ) {
            return redirect()->back()->with(['response' => 1, 'mensaje' => 'Perfil agregado correctamente']);
        }

        return redirect()->back()->with(['response' => 0, 'mensaje' => 'Ocurrio un error, intente nuevamente']);
    }

    public function delete_perfil_tercero(Request $request) {
        if ( Perfiles_tercero::find($request['id'])->delete() ) {
            return redirect()->back()->with(['response' => 1, 'mensaje' => 'Perfil eliminado correctamente']);
        }

        return redirect()->back()->with(['response' => 0, 'mensaje' => 'Ocurrio un error, intente nuevamente']);
    }

    public function cargar_documentos(Request $request) {
        return Documentos_tercero::where('terceros_id', $request['terceros_id'])->get();
    }

    public function agg_documento(Request $request) {
        $date = Carbon::now('America/Bogota');

        if ($request['id']) {

            $documento = Documentos_tercero::find($request['id']);

            $documento->update([
                'tipo' => $request['tipo'],
                'descripcion_documento' => $request['descripcion_documento'],
            ]);

            if ($request->file('adjunto_file')) {
                $extension_file_documento = pathinfo($request->file('adjunto_file')->getClientOriginalName(), PATHINFO_EXTENSION);
                $ruta_file_documento = 'docs/terceros/documentos/';
                $nombre_file_documento = 'documento_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
                Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('adjunto_file')));

                $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;

                $documento->update([
                    'adjunto_file' => $nombre_completo_file_documento
                ]);
            }

            return ['terceros_id' => $request['terceros_id']];

        } else {
            $documento = Documentos_tercero::create([
                'tipo' => $request['tipo'],
                'descripcion_documento' => $request['descripcion_documento'],
                'terceros_id' => $request['terceros_id'],
                'adjunto_file' => 'nom_temp',
            ]);

            if ($request->file('adjunto_file')) {
                $extension_file_documento = pathinfo($request->file('adjunto_file')->getClientOriginalName(), PATHINFO_EXTENSION);
                $ruta_file_documento = 'docs/terceros/documentos/';
                $nombre_file_documento = 'documento_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
                Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('adjunto_file')));

                $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;

                $documento['adjunto_file'] = $nombre_completo_file_documento;
            }

            if ( $documento->save() ) {
                return ['terceros_id' => $request['terceros_id']];
            }

            return 0;
        }
    }

    public function delete_documento(Request $request) {
        Documentos_tercero::find($request['id'])->delete();

        return $request['terceros_id'];
    }

    public function editar_documento(Request $request) {
        return Documentos_tercero::find($request['id']);
    }

    public function cargar_cotizaciones(Request $request) {
        return Cotizacion::where('tercero_id', $request['terceros_id'])->get();
    }

    public function crear_cotizacion(Request $request) {
        $date = Carbon::now('America/Bogota');

        if ($request['cotizacion_creada']) {
            $cotizacion = Cotizacion::find($request['cotizacion_creada']);

            $cotizacion->update([
                'departamento_origen' => $request['departamento_origen'],
                'ciudad_origen' => $request['ciudad_origen'],
                'departamento_destino' => $request['departamento_destino'],
                'ciudad_destino' => $request['ciudad_destino'],
                'fecha_ida' => $request['fecha_ida'],
                'fecha_regreso' => $request['fecha_regreso'],
                'tipo_servicio' => $request['tipo_servicio'],
                'tipo_vehiculo' => $request['tipo_vehiculo'],
                'recorrido' => $request['recorrido'],
                'descripcion' => $request['descripcion'],
                'observaciones' => $request['observaciones'],
                'combustible' => $request['combustible'],
                'conductor' => $request['conductor'],
                'peajes' => $request['peajes'],
                'cotizacion_por' => $request['cotizacion_por'],
                'valor_unitario' => $request['valor_unitario'],
                'cantidad' => $request['cantidad'],
                'total' => ($request['valor_unitario'] * $request['cantidad']),
                'trayecto_dos' => $request['trayecto_dos'],
                'cotizacion_parte_uno' => $request['cotizacion_parte_uno'],
                'cotizacion_parte_dos' => $request['cotizacion_parte_dos']
            ]);

            return $cotizacion;
        } else {
            $tercero = Tercero::find($request['terceros_id']);

            $num = 'COT'.$date->format('Y').$date->format('m').$date->format('d').$date->format('H').$date->format('i').'-'.$date->format('s');

            $cotizacion = Cotizacion::create([
                'num_cotizacion' => $num,
                'fecha' => $date->format('Y-m-d H:m:s'),
                'nombre' => $tercero->nombre,
                'correo' => $tercero->correo,
                'telefono' => $tercero->telefono,
                'departamento_origen' => $request['departamento_origen'],
                'ciudad_origen' => $request['ciudad_origen'],
                'departamento_destino' => $request['departamento_destino'],
                'ciudad_destino' => $request['ciudad_destino'],
                'fecha_ida' => $request['fecha_ida'],
                'fecha_regreso' => $request['fecha_regreso'],
                'tipo_servicio' => $request['tipo_servicio'],
                'tipo_vehiculo' => $request['tipo_vehiculo'],
                'recorrido' => $request['recorrido'],
                'descripcion' => $request['descripcion'],
                'observaciones' => $request['observaciones'],
                'combustible' => $request['combustible'],
                'conductor' => $request['conductor'],
                'peajes' => $request['peajes'],
                'cotizacion_por' => $request['cotizacion_por'],
                'valor_unitario' => $request['valor_unitario'],
                'cantidad' => $request['cantidad'],
                'total' => ($request['valor_unitario'] * $request['cantidad']),
                'trayecto_dos' => $request['trayecto_dos'],
                'responsable_id' => auth()->user()->id,
                'tercero_id' => $tercero->identificacion,
                'cotizacion_parte_uno' => $request['cotizacion_parte_uno'],
                'cotizacion_parte_dos' => $request['cotizacion_parte_dos']
            ]);

            return $cotizacion;
        }

    }

    public function print_cotizacion(Request $request) {
        $cotizacion = Cotizacion::find($request['id']);

        return PDF::loadView('cotizaciones.pdf', compact('cotizacion'))->setPaper('A4')->stream();
    }

    public function eliminar_cotizacion(Request $request) {
        Cotizacion::find($request['cotizacion_id'])->delete();

        return $request['tercero_id'];
    }

    public function cargar_contratos(Request $request) {
        return Cotizacion::where('tercero_id', $request['terceros_id'])->where('contrato_generado', 1)->get();
    }

    public function generar_contrato(Request $request) {
        if ( $request['select_responsable'] == 'Nuevo' ) {
            Contactos_tercero::create([
                'identificacion' => $request['identificacion_responsable'],
                'nombre' => $request['nombre_responsable'],
                'correo' => $request['correo_responsable'],
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

        return ['tercero' => $request['tercero_id_contrato'], 'cotizacion' => $request['cotizacion_id_contrato']];

    }

    public function print_contrato(Request $request) {
        $cotizacion = Cotizacion::find($request['id']);

        $tercero = Tercero::where('identificacion', $cotizacion->tercero_id)->first();
        $responsable = Contactos_tercero::where('identificacion', $cotizacion->responsable_contrato_id)->first();
        $vehiculo = Vehiculo::find($cotizacion->vehiculo_id);
        $conductor = Personal::find($cotizacion->conductor_id);

        $data = [
            'cotizacion' => $cotizacion,
            'tercero' => $tercero,
            'responsable' => $responsable,
            'vehiculo' => $vehiculo,
            'conductor' => $conductor
        ];

        return PDF::loadView('cotizaciones.contrato', compact('data'))->setPaper('A4')->stream('cotizacion.pdf');
    }

    public function editar_cotizacion(Request $request) {
        return Cotizacion::find($request['id']);
    }

    public function editar_contrato(Request $request) {
        $cotizacion = Cotizacion::find($request['id']);

        $responsable = Contactos_tercero::where('identificacion', $cotizacion->responsable_contrato_id)->first();

        return [
            'cotizacion' => $cotizacion,
            'responsable' => $responsable,
        ];
    }
}
