<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Models\Personal;
use App\Models\Cargos_personal;
use App\Models\Contratos_personal;
use App\Models\Documentos_cargos;
use App\Models\Documentos_cargos_admin;
use App\Models\Documentos_personal;
use App\Models\Otro_si;
use Carbon\Carbon;
use App\User;
use PDF;

class PersonalController extends Controller
{
    public function registro() {
        $personal = Personal::paginate(20);

        return view('personal.registro', ['personal' => $personal]);
    }
    public function filtro(){
        $personal = Personal::select('personal.*');

        if(isset($_GET['ordenarpor']) && $_GET['ordenarpor'] != null){
            $personal = $personal->orderBy($_GET['ordenarpor']);
        }
        if(isset($_GET['fecha']) && $_GET['fecha'] != null){
            $personal = $personal->where('fecha_ingreso', 'like', $_GET['fecha']."%");
        }
        if(isset($_GET['fecha_range']) && $_GET['fecha_range']!=null){
            $desde = Str::before($_GET['fecha_range'], ' - ');
            $hasta = Str::after($_GET['fecha_range'], ' - ');
            $personal = $personal->whereBetween('fecha_ingreso', [$desde, $hasta]);
        }
        if(isset($_GET['search']) && $_GET['search'] != null) {
            $personal = $personal->where('nombres', 'like', "%" . $_GET['search'] . "%");
            $personal = $personal->orwhere('primer_apellido', 'like', "%" . $_GET['search'] . "%");
            $personal = $personal->orwhere('identificacion', 'like', "%" . $_GET['search'] . "%");
            $personal = $personal->orwhere('correo', 'like', "%" . $_GET['search'] . "%");
            $personal = $personal->orwhere('telefonos', 'like', "%" . $_GET['search'] . "%");
        }

        $personal = $personal->paginate(20);

        return view('personal.registro', ['personal' => $personal]);
    }

    public function create(Request $request) {
                $date = Carbon::now('America/Bogota');
        if($request->firma != null && $request->firma != ''){

            $extension_file_documento = pathinfo($request->file('firma')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/personal/documentos/firmas/';
            $nombre_file_documento = 'firma_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('firma')));
            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;
            $request->firma = $nombre_completo_file_documento;

        }else{
            $request->firma='';
        }

        if($request->imagen != null && $request->imagen != ''){
            $extension_file_documento = pathinfo($request->file('imagen')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/personal/documentos/imagen/';
            $nombre_file_documento = 'imagen_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('imagen')));
            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;
            $request->imagen = $nombre_completo_file_documento;
        }else{
            $request->imagen='';
        }

        $personal = Personal::create($request->all());

        $personal->firma=$request->firma;
        $personal->imagen=$request->imagen;


        if($personal->save()) {
            return redirect()->route('personal')->with(['creado' => 1]);
        }

        return redirect()->route('personal')->with(['creado' => 0]);
    }

    public function ver_ajax(Request $request) {
        return Personal::find($request['id']);
    }

    public function agg_cargo_personal(Request $request) {
        if ( Cargos_personal::create(['personal_id' => $request['personal_id'], 'cargos_id' => $request['cargos_id']])->save() ) {
            if ($request['view_ver']) {
                return redirect()->back()->with(['cargo' => 1]);
            } else {
                return ['create' => 1, 'personal_id' => $request['personal_id']];
            }
        }

        return ['create' => 0];
    }

    public function delete_cargo_personal(Request $request) {
        Cargos_personal::find($request['id'])->delete();

        return redirect()->back()->with(['cargo_delete' => 1]);
    }

    public function cargar_cargos_personal(Request $request) {
        return DB::table('cargos_personal')
                ->join('cargos', 'cargos.id', '=', 'cargos_personal.cargos_id')
                ->where('cargos_personal.personal_id', $request['id'])
                ->get();
    }

    public function ver(Request $request) {
        $personal = Personal::with(array('cargos_personal' => function ($query) {
            $query->with('cargos');
        }))->find($request['id']);

        $cargos= [];
        foreach ($personal->cargos_personal as $key => $cargo) {
            $cargos[] = $cargo->cargos_id;
        }
        
        $documentos = Documentos_cargos::whereIn('cargos_id', $cargos)->groupBy('documentos_cargos_id')->get();
        $arrDoc = [];
        foreach ($documentos as $key => $doc) {
            $arrDoc[] = $doc->documentos_cargos_id;
        }

        $documentos = Documentos_cargos_admin::whereIn('id', $arrDoc)->get();

        $contratos = 0;

        foreach ($documentos as $key => $doc) {
            if(strcasecmp($doc->nombre, 'contratos') == 0 || strcasecmp($doc->nombre, 'contrato') == 0 || strcasecmp($doc->nombre, 'contratos laborales') == 0 || strcasecmp($doc->nombre, 'contrato laboral') == 0){
                $contratos = 1;
            }
        }

        // dd($personal);
        return view('personal.ver', ['personal' => $personal, 'documentos' => $documentos, 'contratos' => $contratos]);
    }

    public function edit($id)
    {
        $personal = Personal::with(array('cargos_personal' => function ($query) {
            $query->with('cargos');
        }))->find($id);

        $user = User::where('identificacion',$personal->identificacion)->firstorfail();
        $permisos=\Spatie\Permission\Models\Permission::all();

        $permisosuser = array();
        foreach ($user->permissions as $permiso) {
            array_push($permisosuser, $permiso->name);
        }

        return view('personal.edit', ['personal' => $personal,'permisos'=>$permisos,'permisosuser'=>$permisosuser,'user'=>$user]);

    }

    public function createclave($id)
    {
        $personal = Personal::with(array('cargos_personal' => function ($query) {
            $query->with('cargos');
        }))->find($id);

        $permisos=\Spatie\Permission\Models\Permission::all();

        return view('personal.create', ['personal' => $personal,'permisos'=>$permisos]);

    }

    public function update(Request $request) {
        $personal = Personal::find($request['id']);

        $date = Carbon::now('America/Bogota');

        if($request->firma != null && $request->firma != ''){

            $extension_file_documento = pathinfo($request->file('firma')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/personal/documentos/firmas/';
            $nombre_file_documento = 'firma_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('firma')));
            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;
            $request->firma = $nombre_completo_file_documento;
            Storage::disk('public')->delete($personal->firma);
        }else{
            $request->firma=$personal->firma;
        }

        if($request->imagen != null && $request->imagen != '' && $request->file('imagen')){
            Storage::disk('public')->delete($personal->imagen);
            $extension_file_documento = pathinfo($request->file('imagen')->getClientOriginalName(), PATHINFO_EXTENSION);
            $ruta_file_documento = 'docs/personal/documentos/imagen/';
            $nombre_file_documento = 'imagen_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
            Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('imagen')));
            $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;
            $request->imagen = $nombre_completo_file_documento;

        }else{
            $request->imagen=$personal->imagen;
        }

        $personal->update([
            'tipo_identificacion' => $request['tipo_identificacion'],
            'identificacion' => $request['identificacion'],
            'nombres' => $request['nombres'],
            'primer_apellido' => $request['primer_apellido'],
            'segundo_apellido' => $request['segundo_apellido'],
            'fecha_ingreso' => $request['fecha_ingreso'],
            'direccion' => $request['direccion'],
            'sexo' => $request['sexo'],
            'firma' => $request->firma,
            'estado' => $request['estado'],
            'rh' => $request['rh'],
            'tipo_vinculacion' => $request['tipo_vinculacion'],
            'correo' => $request['correo'],
            'telefonos' => $request['telefonos'],
            'sede'=>$request['sede'],
            'imagen'=>$request->imagen
        ]);

        return redirect()->back()->with(['update' => 1]);
    }

    public function crear_contrato(Request $request) {
        Contratos_personal::create($request->all())->save();

        return $request['personal_id'];
    }

    public function cargar_contratos(Request $request) {
        return Contratos_personal::where('personal_id', $request['id'])->with('otro_si')->get();
    }

    public function agg_otro_si(Request $request) {
        Otro_si::create($request->all())->save();

        return $request['contratos_personal_id'];
    }

    public function editar_contrato(Request $request) {
        return Contratos_personal::find($request['id']);
    }

    public function agg_documento(Request $request) {
        $date = Carbon::now('America/Bogota');

        if ($request['id']) {

            $documento = Documentos_personal::find($request['id']);

            $documento->update([
                'tipo' => $request['tipo'],
                'fecha_expedicion' => $request['fecha_expedicion'],
                'fecha_inicio_vigencia' => $request['fecha_inicio_vigencia'] ?? NULL,
                'fecha_fin_vigencia' => $request['fecha_fin_vigencia'] ?? NULL,
                'observaciones' => $request['observaciones'],
                'personal_id' => $request['personal_id'],
            ]);

            if ($request->file('adjunto')) {
                $extension_file_documento = pathinfo($request->file('adjunto')->getClientOriginalName(), PATHINFO_EXTENSION);
                $ruta_file_documento = 'docs/personal/documentos/';
                $nombre_file_documento = 'documento_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
                Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('adjunto')));

                $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;

                $documento->update([
                    'adjunto' => $nombre_completo_file_documento
                ]);
            }

            return ['tipo' => $request['tipo'], 'id_table' => $request['id_table'], 'personal_id' => $request['personal_id'], 'vigencia' => $request['vigencia']];

        } else {
            $documento = Documentos_personal::create([
                'tipo' => $request['tipo'],
                'fecha_expedicion' => $request['fecha_expedicion'],
                'fecha_inicio_vigencia' => $request['fecha_inicio_vigencia'] ?? NULL,
                'fecha_fin_vigencia' => $request['fecha_fin_vigencia'] ?? NULL,
                'observaciones' => $request['observaciones'],
                'adjunto' => 'nombre_temp',
                'personal_id' => $request['personal_id'],
            ]);

            if ($request->file('adjunto')) {
                $extension_file_documento = pathinfo($request->file('adjunto')->getClientOriginalName(), PATHINFO_EXTENSION);
                $ruta_file_documento = 'docs/personal/documentos/';
                $nombre_file_documento = 'documento_'.$date->isoFormat('YMMDDHmmss').'.'.$extension_file_documento;
                Storage::disk('public')->put($ruta_file_documento.$nombre_file_documento, File::get($request->file('adjunto')));

                $nombre_completo_file_documento = $ruta_file_documento.$nombre_file_documento;

                $documento['adjunto'] = $nombre_completo_file_documento;
            }

            if ( $documento->save() ) {
                return ['tipo' => $request['tipo'], 'id_table' => $request['id_table'], 'personal_id' => $request['personal_id'], 'vigencia' => $request['vigencia']];
            }

            return 0;
        }

    }

    public function cargar_documentos(Request $request) {
        return Documentos_personal::where('tipo', $request['tipo'])->where('personal_id', $request['personal_id'])->get();
    }

    public function editar_documento(Request $request) {
        return Documentos_personal::find($request['id']);
    }

    public function eliminar_documento(Request $request) {
        Documentos_personal::find($request['id'])->delete();
        return ['tipo' => $request['tipo'], 'personal_id' => $request['personal_id']];
    }

    public function print_otrosi(Request $request) {
        $otro_si = Otro_si::with(array('contratos_personal' => function ($query) {
            $query->with('personal');
        }))->find($request['id']);

        return PDF::loadView('personal.otro_si', compact('otro_si'))->setPaper('A4')->stream('otro_si.pdf');
    }

    public function print_certificado(Request $request) {
        $contrato = Contratos_personal::with('personal')->find($request['id']);

        return PDF::loadView('personal.certificado', compact('contrato'))->setPaper('A4')->stream('certificado.pdf');
    }

    public function print_contrato(Request $request) {
        $contrato = Contratos_personal::with('personal')->find($request['id']);

        return PDF::loadView('personal.contrato', compact('contrato'))->setPaper('A4')->stream('certificado.pdf');

        dd($contrato);
    }

    public function buscar_usuario(Request $request) {
        $user = User::where('identificacion', $request['identificacion'])->first();

        if ($user == null) {
            return null;
        }

        $permisos = array();

        foreach ($user->permissions as $permiso) {
            array_push($permisos, $permiso->name);
        }

        return [
            'user' => $user,
            // 'rol' => $user->roles()->first()->name,
            // 'permisos' => $permisos
        ];
    }

    public function crear_clave(Request $request) {
        $user = User::create([
            'name' => $request['name'],
            'identificacion' => $request['identificacion'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'estado' => $request['estado'],
        ]);

        if ($user->save()) {
            if ($request['tipo'] == 'admin') {
                $user->assignRole($request['tipo']);
            } else {
                $user->assignRole($request['tipo']);
                $user->givePermissionTo($request['permisos']);
            }
            return redirect()->route('persona.edit',$request['user_id'])->with('create', 1);
        } else {
            return redirect()->back()->with('create', 0);
        }

    }

    public function update_clave(Request $request) {
        // return $request;
        $user = User::where('identificacion', $request['identificacion'])->first();

        if ($request['password']) {
            $user->update([
                'password' => Hash::make($request['password'])
            ]);
        }

        $user->update([
            'name' => $request['name'],
            'identificacion' => $request['identificacion'],
            'email' => $request['email'],
            'estado' => $request['estado'],
        ]);

        $user->removeRole($user->roles()->first()->name);
        $user->revokePermissionTo(Permission::all());
        $user->assignRole($request['tipo']);
        if ($request['tipo'] == 'general') {
            $user->givePermissionTo($request['permisos']);
        }


        return redirect()->back()->with('update', 1);
    }

    public function eliminar_contrato(Request $request) {
        Contratos_personal::find($request['id'])->delete();

        return $request['personal_id'];
    }
}
