<?php

namespace App\Http\Controllers;

use App\Mail\CotizacionMail;
use App\Mail\RespuestaCorreoMail;
use App\Models\Cargos_personal;
use App\Models\Conductores_vehiculo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tercero;
use App\Models\Perfiles_tercero;
use App\Models\Contactos_tercero;
use App\Models\Documentos_personal;
use App\Models\Documentos_tercero;
use App\Models\Trayectos_contrato;
use App\Models\Contrato;
use App\Models\Correspondencia;
use App\Models\Cotizacion;
use App\Models\Cotizaciones;
use App\Models\Cotizaciones_trayectos;
use App\Models\Personal;
use App\Models\Vehiculo;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use PDF;

class TercerosController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $terceros = Tercero::paginate(20);

        return view('terceros.index', ['terceros' => $terceros]);
    }

    public function filtrar() {

        $terceros = Tercero::select('terceros.*');

        if(isset($_GET['departamento']) && ($_GET['departamento']) != null) {
            $terceros = $terceros->where('departamento',$_GET['departamento']);
        }
        if(isset($_GET['municipio']) && $_GET['municipio'] != null){
            $terceros = $terceros->where('municipio',$_GET['municipio']);
        }
        if(isset($_GET['tipo_tercero']) && $_GET['tipo_tercero']){
            $terceros = $terceros->where('tipo_tercero',$_GET['tipo_tercero']);
        }
        if(isset($_GET['search']) && $_GET['search'] != null) {
            $terceros = $terceros->where('identificacion', 'like', "%".$_GET['search']."%");
            $terceros = $terceros->orWhere('nombre', 'like', "%".$_GET['search']."%");
            $terceros = $terceros->orWhere('correo', 'like', "%".$_GET['search']."%");
            $terceros = $terceros->orWhere('telefono', 'like', "%".$_GET['search']."%");
        }
        if(isset($_GET['ordenarpor']) && $_GET['ordenarpor'] != null){
            $terceros = $terceros->orderBy($_GET['ordenarpor']);
        }

        $terceros = $terceros->paginate(20);

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
            'direccion' => $request['direccion_contacto'],
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
        return Contactos_tercero::where('identificacion', $request['responsable'])->where('terceros_id', $request['tercero'])->first();
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
                'descripcion' => $request['descripcion_documento'],
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
                'descripcion' => $request['descripcion_documento'],
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
        return Cotizaciones::where('tercero_id', $request['terceros_id'])->get();
    }

    public function crear_cotizacion(Request $request) {
        $date = Carbon::now('America/Bogota');
        if ($request['cotizacion_creada']) {
            $cotizacion = Cotizaciones::find($request['cotizacion_creada']);
            
            $cotizacion->update([
                'cotizacion_parte_uno' => $request['cotizacion_parte_uno'],
                'cotizacion_parte_dos' => $request['cotizacion_parte_dos']
            ]);

            $a=0;
            foreach ($request['coti_id'] as $coti) {
                Cotizaciones_trayectos::find($coti)->update([
                    'descripcion_table' => $request['descripcion_table'][$a],
                ]);
                $a++;
            }

            


            return $cotizacion;
        } else {
            $tercero = Tercero::find($request['terceros_id']);

            $num = 'COT'.$date->format('Y').$date->format('m').$date->format('d').$date->format('H').$date->format('i').'-'.$date->format('s');


            $cotizacion = Cotizaciones::create([
                'num_cotizacion' => $request['numero_cotizacion'],
                'fecha' => $date->format('Y-m-d H:m:s'),
                'tercero_id' => $tercero->id,
                'cotizacion_parte_uno' => $request['cotizacion_parte_uno'],
                'cotizacion_parte_dos' => $request['cotizacion_parte_dos']
            ]);

            $cotizacion_trayecto = Cotizaciones_trayectos::create([
                'departamento_origen' => $request['departamento_origen'],
                'ciudad_origen' => $request['ciudad_origen'],
                'departamento_destino' => $request['departamento_destino'],
                'ciudad_destino' => $request['ciudad_destino'],
                'fecha_ida' => $request['fecha_ida'],
                'fecha_regreso' => $request['fecha_regreso'],
                'tipo_servicio' => $request['tipo_servicio'],
                'tipo_vehiculo' => $request['tipo_vehiculo'],
                'recorrido' => $request['recorrido'],
                'descripcion_table' => $request['descripcion_table'],
                'observaciones' => $request['observaciones'],
                'combustible' => $request['combustible'],
                'conductor' => $request['conductor'],
                'peajes' => $request['peajes'],
                'cotizacion_por' => $request['cotizacion_por'],
                'valor_unitario' => $request['valor_unitario'],
                'cantidad' => $request['cantidad'],
                'total' => ($request['valor_unitario'] * $request['cantidad']),
                'responsable_id' => auth()->user()->id,
                'cotizacion_id' => $cotizacion->id
            ]);

            return $cotizacion;
        }

    }

    public function print_cotizacion(Request $request) {
        $cotizacion = Cotizaciones::find($request['id']);
        $cotizaciones = Cotizaciones_trayectos::where('cotizacion_id', $request['id'])->get();
        return PDF::loadView('cotizaciones.pdf', ['cotizacion' => $cotizacion, 'cotiza' => $cotizaciones])->setPaper('A4')->stream();
    }

    public function enviar_cotizacion(Request $request) {
        $cotizacion = Cotizaciones::join('terceros', 'terceros.id', '=', 'cotizaciones.tercero_id')
        ->select('cotizaciones.*', 'terceros.correo', 'terceros.nombre')->find($request['id']);
        $cotizaciones = Cotizaciones_trayectos::where('cotizacion_id', $request['id'])->get();
       
        $pdf = PDF::loadView('cotizaciones.pdf', ['cotizacion' => $cotizacion, 'cotiza' => $cotizaciones])->setPaper('A4')->output();

        $mail = Mail::to($cotizacion->correo)->send(new CotizacionMail($cotizacion, $pdf, 0));
        
        return $mail;
    }

    public function eliminar_cotizacion(Request $request) {

        Cotizaciones::find($request['cotizacion_id'])->delete();
        Cotizaciones_trayectos::where('cotizacion_id', $request['cotizacion_id'])->delete();

        return $request['tercero_id'];
    }

    public function eliminar_contrato(Request $request) {
        Contrato::find($request['contrato_id'])->delete();

        return $request['tercero_id'];
    }

    public function cargar_contratos(Request $request) {
        return DB::table('contratos')
            ->join('contactos_terceros', 'contactos_terceros.identificacion', '=', 'contratos.responsable_contrato_id')
            ->select('contratos.id', 'contratos.fecha', 'contratos.tipo_contrato', 'contratos.objeto_contrato', 'contactos_terceros.nombre')
            ->where('tercero_id', $request['terceros_id'])
            ->groupBy('contratos.id')
            ->get();
    }

    public function generar_contrato(Request $request) {
        $date = Carbon::now('America/Bogota');

        if ( $request['select_responsable'] == 'Nuevo' ) {
            Contactos_tercero::create([
                'identificacion' => $request['identificacion_responsable'],
                'nombre' => $request['nombre_responsable'],
                'direccion' => $request['direccion_responsable'],
                'telefono' => $request['telefono_responsable'],
                'terceros_id' => $request['tercero_id_contrato'],
            ]);
        }

        $cotizacion = Cotizaciones::find($request['cotizacion_id_contrato'])->join('terceros', 'terceros.id', '=', 'cotizaciones.tercero_id')->get();
        $trayecto_cotizacion = Cotizaciones_trayectos::where('cotizacion_id', $request['cotizacion_id_contrato']);

        $contrato = Contrato::create([
            'fecha' => $date->format('Y-m-d'),
            'responsable_contrato_id' => $request['identificacion_responsable'],
            'tipo_contrato' => $request['tipo_contrato'],
            'objeto_contrato' => $request['objeto_contrato'],
            'contrato_parte_uno' => $request['contrato_parte_uno'],
            'contrato_parte_dos' => $request['contrato_parte_dos'],
            'tercero_id' => $cotizacion[0]['identificacion'],
            'cotizacion_id' => $request['cotizacion_id_contrato'],
        ]);

        $a=0;
        $tercero = Tercero::find($cotizacion[0]['tercero_id']);
        foreach ($request['id_cotizacion_trayecto'] as $cotizacionn) {
            Cotizaciones_trayectos::find($cotizacionn)->update([
                'vehiculo_id' => $request['vehiculo_id'][$a],
                'conductor_uno_id' => $request['conductor_uno_id'][$a],
                'conductor_dos_id' => $request['conductor_dos_id'][$a],
                'conductor_tres_id' => $request['conductor_tres_id'][$a],
            ]);



            $cot = Cotizaciones_trayectos::find($cotizacionn);
            Conductores_vehiculo::create([
                'fecha_inicial' => $cot['fecha_ida'],
                'fecha_final' => $cot['fecha_regreso'],
                'personal_id' => $request['conductor_uno_id'][$a],
                'vehiculo_id' => $request['vehiculo_id'][$a]
            ]);
            Conductores_vehiculo::create([
                'fecha_inicial' => $cot['fecha_ida'],
                'fecha_final' => $cot['fecha_regreso'],
                'personal_id' => $request['conductor_dos_id'][$a],
                'vehiculo_id' => $request['vehiculo_id'][$a]
            ]);
            Conductores_vehiculo::create([
                'fecha_inicial' => $cot['fecha_ida'],
                'fecha_final' => $cot['fecha_regreso'],
                'personal_id' => $request['conductor_tres_id'][$a],
                'vehiculo_id' => $request['vehiculo_id'][$a]
            ]);
            $trayecto = Trayectos_contrato::create([
                'fecha' => $cotizacion[0]['fecha'],
                'nombre' => $tercero['nombre'],
                'correo' => $tercero['correo'],
                'telefono' => $tercero['telefono'],
                'departamento_origen' => $cot['departamento_origen'],
                'ciudad_origen' => $cot['ciudad_origen'],
                'departamento_destino' => $cot['departamento_destino'],
                'ciudad_destino' => $cot['ciudad_destino'],
                'fecha_ida' => $cot['fecha_ida'],
                'fecha_regreso' => $cot['fecha_regreso'],
                'tipo_servicio' => $cot['tipo_servicio'],
                'tipo_vehiculo' => $cot['tipo_vehiculo'],
                'recorrido' => $cot['recorrido'],
                'descripcion_table' => $cot['descripcion_table'],
                'observaciones' => $cot['observaciones'],
                'combustible' => $cot['combustible'],
                'conductor' => $cot['conductor'],
                'peajes' => $cot['peajes'],
                'cotizacion_por' => $cot['cotizacion_por'],
                'valor_unitario' => $cot['valor_unitario'],
                'cantidad' => $cot['cantidad'],
                'total' => $cot['total'],
                'vehiculo_id' => $cot['vehiculo_id'],
                'conductor_uno_id' => $cot['conductor_uno_id'],
                'conductor_dos_id' => $cot['conductor_dos_id'],
                'conductor_tres_id' => $cot['conductor_tres_id'],
                'contratos_id' => $contrato->id
            ]);
            $a++;
        }


        if ($contrato->save() && $trayecto->save()) {
            return ['tercero' => $request['tercero_id_return'], 'trayecto' => $trayecto['id']];
        } else {
            return ['error' => true];
        }

    }

    public function generar_vehiculos_contratos(Request $request){
        $personal = Cargos_personal::with('personal')->with('cargos')->whereHas('cargos', function($query) {
            $query->where('cargos.nombre', 'Conductor');
             })->get();

             $cotizaciones = Cotizaciones_trayectos::where('cotizacion_id', $request->id)->get();

        return ['vehiculos' => Vehiculo::all(), 'personal' => $personal, 'cotizaciones' => $cotizaciones];
    }

    public function actualizar_contrato(Request $request) {
        if ( $request['select_responsable_update'] == 'Nuevo' ) {
            Contactos_tercero::create([
                'identificacion' => $request['identificacion_responsable'],
                'nombre' => $request['nombre_responsable'],
                'direccion' => $request['direccion_responsable'],
                'telefono' => $request['telefono_responsable'],
                'terceros_id' => $request['tercero_id_contrato'],
            ]);
        }

        $contrato = Contrato::find($request['contrato_id']);

        $contrato->update([
            'responsable_contrato_id' => $request['identificacion_responsable'],
            'tipo_contrato' => $request['tipo_contrato'],
            'objeto_contrato' => $request['objeto_contrato'],
            'contrato_parte_uno' => $request['contrato_parte_uno'],
            'contrato_parte_dos' => $request['contrato_parte_dos'],
            'tercero_id' => $request['tercero_id_return']
        ]);

        if ($contrato->save()) {
            return ['tercero' => $request['tercero_id_return'], 'contrato' => $contrato->id];
        } else {
            return ['error' => true];
        }

    }

    public function agregar_trayecto(Request $request) {
        $contrato = Contrato::find($request['contratos_id']);
        $cotizacion = Cotizacion::find($contrato->cotizacion_id);

        if ($request['trayecto_creado']) {
            $trayecto = Trayectos_contrato::find($request['trayecto_creado']);

            if($trayecto->vehiculo_id != $request->vehiculo_id){
                Conductores_vehiculo::create([
                    'fecha_inicial' => $request['fecha_ida'],
                    'fecha_final' => $request['fecha_regreso'],
                    'personal_id' => $request['conductor_uno_id'],
                    'vehiculo_id' => $request['vehiculo_id']
                ]);
                Conductores_vehiculo::create([
                    'fecha_inicial' => $request['fecha_ida'],
                    'fecha_final' => $request['fecha_regreso'],
                    'personal_id' => $request['conductor_dos_id'],
                    'vehiculo_id' => $request['vehiculo_id']
                ]);
                Conductores_vehiculo::create([
                    'fecha_inicial' => $request['fecha_ida'],
                    'fecha_final' => $request['fecha_regreso'],
                    'personal_id' => $request['conductor_tres_id'],
                    'vehiculo_id' => $request['vehiculo_id']
                ]);
            }else{
                if($trayecto->conductor_uno_id != $request->conductor_uno_id){
                    Conductores_vehiculo::create([
                        'fecha_inicial' => $request['fecha_ida'],
                        'fecha_final' => $request['fecha_regreso'],
                        'personal_id' => $request['conductor_uno_id'],
                        'vehiculo_id' => $request['vehiculo_id']
                    ]);
                }

                if($trayecto->conductor_dos_id != $request->conductor_dos_id){
                    Conductores_vehiculo::create([
                        'fecha_inicial' => $request['fecha_ida'],
                        'fecha_final' => $request['fecha_regreso'],
                        'personal_id' => $request['conductor_dos_id'],
                        'vehiculo_id' => $request['vehiculo_id']
                    ]);
                }

                if($trayecto->conductor_tres_id != $request->conductor_tres_id){
                    Conductores_vehiculo::create([
                        'fecha_inicial' => $request['fecha_ida'],
                        'fecha_final' => $request['fecha_regreso'],
                        'personal_id' => $request['conductor_tres_id'],
                        'vehiculo_id' => $request['vehiculo_id']
                    ]);
                }
            }
 

            $trayecto->update([
                'departamento_origen' => $request['departamento_origen'],
                'ciudad_origen' => $request['ciudad_origen'],
                'departamento_destino' => $request['departamento_destino'],
                'ciudad_destino' => $request['ciudad_destino'],
                'fecha_ida' => $request['fecha_ida'],
                'fecha_regreso' => $request['fecha_regreso'],
                'tipo_servicio' => $request['tipo_servicio'],
                'tipo_vehiculo' => $request['tipo_vehiculo'],
                'recorrido' => $request['recorrido_trayecto'],
                'observaciones' => $request['observaciones'],
                'combustible' => $request['combustible_trayecto'],
                'conductor' => $request['conductor_trayecto'],
                'peajes' => $request['peajes_trayecto'],
                'cotizacion_por' => $request['cotizacion_por_trayecto'],
                'valor_unitario' => $request['valor_unitario'],
                'cantidad' => $request['cantidad'],
                'total' => $request['total'],
                'vehiculo_id' => $request['vehiculo_id'],
                'conductor_uno_id' => $request['conductor_uno_id'],
                'conductor_dos_id' => $request['conductor_dos_id'],
                'conductor_tres_id' => $request['conductor_tres_id'],
            ]);
        } else {
            Conductores_vehiculo::create([
                'fecha_inicial' => $request['fecha_ida'],
                'fecha_final' => $request['fecha_regreso'],
                'personal_id' => $request['conductor_tres_id'],
                'vehiculo_id' => $request['vehiculo_id']
            ]);
            Conductores_vehiculo::create([
                'fecha_inicial' => $request['fecha_ida'],
                'fecha_final' => $request['fecha_regreso'],
                'personal_id' => $request['conductor_uno_id'],
                'vehiculo_id' => $request['vehiculo_id']
            ]);
            Conductores_vehiculo::create([
                'fecha_inicial' => $request['fecha_ida'],
                'fecha_final' => $request['fecha_regreso'],
                'personal_id' => $request['conductor_dos_id'],
                'vehiculo_id' => $request['vehiculo_id']
            ]);
            $trayecto = Trayectos_contrato::create([
                'fecha' => $cotizacion['fecha'],
                'nombre' => $cotizacion['nombre'],
                'correo' => $cotizacion['correo'],
                'telefono' => $cotizacion['telefono'],
                'departamento_origen' => $request['departamento_origen'],
                'ciudad_origen' => $request['ciudad_origen'],
                'departamento_destino' => $request['departamento_destino'],
                'ciudad_destino' => $request['ciudad_destino'],
                'fecha_ida' => $request['fecha_ida'],
                'fecha_regreso' => $request['fecha_regreso'],
                'tipo_servicio' => $request['tipo_servicio'],
                'tipo_vehiculo' => $request['tipo_vehiculo'],
                'recorrido' => $request['recorrido_trayecto'],
                'observaciones' => $request['observaciones'],
                'combustible' => $request['combustible_trayecto'],
                'conductor' => $request['conductor_trayecto'],
                'peajes' => $request['peajes_trayecto'],
                'cotizacion_por' => $request['cotizacion_por_trayecto'],
                'valor_unitario' => $request['valor_unitario'],
                'cantidad' => $request['cantidad'],
                'total' => $request['total'],
                'vehiculo_id' => $request['vehiculo_id'],
                'conductor_uno_id' => $request['conductor_uno_id'],
                'conductor_dos_id' => $request['conductor_dos_id'],
                'conductor_tres_id' => $request['conductor_tres_id'],
                'contratos_id' => $request['contratos_id']
            ]);
        }

        if ($trayecto->save()) {
            return ['contrato' => $request['contratos_id'], 'trayecto' => $trayecto->id];
        } else {
            return ['error' => true];
        }

    }

    public function agregar_trayecto_cotizacion(Request $request) {

        if ($request['trayecto_creado']) {
            $trayecto = Cotizaciones_trayectos::find($request['trayecto_creado']);

            $trayecto->update([
                'departamento_origen' => $request['departamento_origen'],
                'ciudad_origen' => $request['ciudad_origen'],
                'departamento_destino' => $request['departamento_destino'],
                'ciudad_destino' => $request['ciudad_destino'],
                'fecha_ida' => $request['fecha_ida'],
                'fecha_regreso' => $request['fecha_regreso'],
                'tipo_servicio' => $request['tipo_servicio'],
                'tipo_vehiculo' => $request['tipo_vehiculo'],
                'recorrido' => $request['recorrido_trayecto_cotizacion'],
                'observaciones' => $request['observaciones'],
                'combustible' => $request['combustible_trayecto_cotizacion'],
                'conductor' => $request['conductor_trayecto_cotizacion'],
                'peajes' => $request['peajes_trayecto_cotizacion'],
                'cotizacion_por' => $request['cotizacion_por_trayecto_cotizacion'],
                'valor_unitario' => $request['valor_unitario'],
                'cantidad' => $request['cantidad'],
                'total' => $request['total'],
            ]);
        } else {
            $trayecto = Cotizaciones_trayectos::create([
                'departamento_origen' => $request['departamento_origen'],
                'ciudad_origen' => $request['ciudad_origen'],
                'departamento_destino' => $request['departamento_destino'],
                'ciudad_destino' => $request['ciudad_destino'],
                'fecha_ida' => $request['fecha_ida'],
                'fecha_regreso' => $request['fecha_regreso'],
                'tipo_servicio' => $request['tipo_servicio'],
                'tipo_vehiculo' => $request['tipo_vehiculo'],
                'recorrido' => $request['recorrido_trayecto_cotizacion'],
                'observaciones' => $request['observaciones'],
                'combustible' => $request['combustible_trayecto_cotizacion'],
                'conductor' => $request['conductor_trayecto_cotizacion'],
                'peajes' => $request['peajes_trayecto_cotizacion'],
                'cotizacion_por' => $request['cotizacion_por_trayecto_cotizacion'],
                'valor_unitario' => $request['valor_unitario'],
                'cantidad' => $request['cantidad'],
                'total' => $request['total'],
                'cotizacion_id' => $request['cotizacion_id']
            ]);
        }

        if ($trayecto->save()) {
            return ['cotizacion_id' => $trayecto->cotizacion_id, 'trayecto' => $trayecto->id];
        } else {
            return ['error' => true];
        }

    }

    public function print_contrato(Request $request) {
        $trayecto = Trayectos_contrato::with('contratos')->find($request['id']);

        $tercero = Tercero::where('identificacion', $trayecto->contratos->tercero_id)->first();
        $responsable = Contactos_tercero::where('identificacion', $trayecto->contratos->responsable_contrato_id)->first();
        $vehiculo = Vehiculo::find($trayecto->vehiculo_id);
        $conductor = Personal::find($trayecto->conductor_uno_id);
        $vigencia = Documentos_personal::where('tipo', 'LICENCIA DE CONDUCCIÓN')->where('personal_id', $trayecto->conductor_uno_id)->orderBy('id', 'desc')->first()->fecha_fin_vigencia ?? NULL;
        $conductor_dos = Personal::find($trayecto->conductor_dos_id);
        $vigencia_dos = Documentos_personal::where('tipo', 'LICENCIA DE CONDUCCIÓN')->where('personal_id', $trayecto->conductor_dos_id)->orderBy('id', 'desc')->first()->fecha_fin_vigencia ?? NULL;
        $conductor_tres = Personal::find($trayecto->conductor_tres_id);
        $vigencia_tres = Documentos_personal::where('tipo', 'LICENCIA DE CONDUCCIÓN')->where('personal_id', $trayecto->conductor_tres_id)->orderBy('id', 'desc')->first()->fecha_fin_vigencia ?? NULL;

        $anio = Carbon::now()->format('Y');

        if ($trayecto->id > 999) {
            $extracto_numero = $trayecto->id;
        } else if($trayecto->id > 99 && $trayecto->id < 999) {
            $extracto_numero = '0'.$trayecto->id;
        } else if($trayecto->id > 9 && $trayecto->id < 99) {
            $extracto_numero = '00'.$trayecto->id;
        } else {
            $extracto_numero = '000'.$trayecto->id;
        }

        if ($trayecto->contratos->id > 999) {
            $contrato_numero = $trayecto->contratos->id;
        } else if($trayecto->contratos->id > 99 && $trayecto->contratos->id < 999) {
            $contrato_numero = '0'.$trayecto->contratos->id;
        } else if($trayecto->contratos->id > 9 && $trayecto->contratos->id < 99) {
            $contrato_numero = '00'.$trayecto->contratos->id;
        } else {
            $contrato_numero = '000'.$trayecto->contratos->id;
        }


        $data = [
            'trayecto' => $trayecto,
            'contrato' => $trayecto->contratos,
            'tercero' => $tercero,
            'responsable' => $responsable,
            'vehiculo' => $vehiculo,
            'conductor' => $conductor,
            'vigencia' =>$vigencia,
            'conductor_dos' => $conductor_dos,
            'vigencia_dos' => $vigencia_dos,
            'conductor_tres' => $conductor_tres,
            'vigencia_tres' => $vigencia_tres,
            'anio' => $anio,
            'extracto_numero' => $extracto_numero,
            'contrato_numero' => $contrato_numero
        ];

        return PDF::loadView('cotizaciones.contrato', compact('data'))->setPaper('A4')->stream('cotizacion.pdf');
    }

    public function print_contrato_contrato(Request $request) {
        $contrato = Contrato::find($request['id']);
        $tercero = Tercero::where('identificacion', $contrato->tercero_id)->first();

        $data = [
            'contrato' => $contrato,
            'tercero' => $tercero,
        ];

        return PDF::loadView('terceros.contrato_pdf', compact('data'))->setPaper('A4')->stream('contrato.pdf');
    }

    public function editar_cotizacion(Request $request) {
        return Cotizaciones_trayectos::where('cotizacion_id', $request->id)->join('cotizaciones', 'cotizaciones.id', '=', 'cotizaciones_trayectos.cotizacion_id')->select('cotizaciones.*', 'cotizaciones_trayectos.*', 'cotizaciones_trayectos.id as coti_id', 'cotizaciones.id as id')->get();
    }

    public function editar_contrato(Request $request) {
        $contrato = Contrato::find($request['id']);

        $responsable = Contactos_tercero::where('identificacion', $contrato->responsable_contrato_id)->first();

        return [
            'contrato' => $contrato,
            'responsable' => $responsable,
        ];
    }

    public function get_tercero(Request $request) {
        return Tercero::find($request['id']);
    }

    public function update(Request $request) {
        if(Tercero::find($request['tercero_id'])->update($request->all())) {
            return redirect()->back()->with('update', 1);
        }

        return redirect()->back()->with('update', 0);
    }

    public function ver_trayectos(Request $request) {
        return Trayectos_contrato::where('contratos_id', $request['id'])->get();
    }

    public function ver_trayectos_cotizacion(Request $request) {
        return Cotizaciones_trayectos::where('cotizacion_id', $request['id'])->get();
    }

    public function eliminar_trayecto(Request $request) {
        Trayectos_contrato::find($request['id'])->delete();

        return $request['contrato'];
    }

    public function eliminar_trayecto_cotizacion(Request $request) {
        Cotizaciones_trayectos::find($request['id'])->delete();

        return $request['cotizacion'];
    }

    public function editar_trayecto(Request $request) {
        return Trayectos_contrato::with('contratos')->find($request['id']);
    }

    public function editar_trayecto_cotizacion(Request $request) {
        return Cotizaciones_trayectos::find($request['id']);
    }

    public function correspondencia($id){
        $correspondencia = Correspondencia::
        join('tipo_radicacion_correspondencia', 'tipo_radicacion_correspondencia.id', '=', 'correspondencia.tipo_radicacion_id')
        ->join('dependencia_correspondencia', 'dependencia_correspondencia.id', '=', 'correspondencia.dependencia_id')
        ->join('origen_correspondencia', 'origen_correspondencia.id','=', 'correspondencia.origen_id')
        ->where('tercero_id', $id)
        ->select('correspondencia.*', 'tipo_radicacion_correspondencia.nombre as nombre_radicacion', 'dependencia_correspondencia.nombre as nombre_dependencia', 'origen_correspondencia.nombre as nombre_origen')
        ->get();
        $tercero = Tercero::find($id);
        return view('terceros.correspondencia', ['correspondencias' => $correspondencia, 'tercero' => $tercero]);
    }

    public function correspondencia_create(Request $request){
        $date = Carbon::now('America/Bogota');

        $correspondencia = Correspondencia::create($request->except('adjunto'));

        if ($request->file('adjunto')) {
            $extension_file_documento = pathinfo($request->file('adjunto')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/terceros/documentos/';
            $nombre_file_documento = 'correspondencia_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('adjunto')));
    
            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;
            $correspondencia->adjunto = $nombre_completo_file_documento;
        }

        if($correspondencia->save()){
            return redirect()->route('correspondencia_index', $correspondencia->tercero_id);
        }

        return "Error En la creacion";
    }

    public function correspondencia_editar(Request $request){
        $date = Carbon::now('America/Bogota');

        $correspondencia = Correspondencia::find($request->id);

        $correspondencia->update($request->except('adjunto'));

        if ($request->file('adjunto')) {
            $extension_file_documento = pathinfo($request->file('adjunto')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/terceros/documentos/';
            $nombre_file_documento = 'correspondencia_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('adjunto')));
    
            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;
            if($correspondencia->adjunto){
                Storage::disk('public')->delete($correspondencia->adjunto);
            }
            $correspondencia->adjunto = $nombre_completo_file_documento;
        }

        if($correspondencia->save()){
            return redirect()->route('correspondencia_index', $correspondencia->tercero_id)->with('correspondencia', 1);
        }

        return redirect()->route('correspondencia_index', $correspondencia->tercero_id)->with('correspondencia', 0);
    }

}
