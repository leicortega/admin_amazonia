<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormCreateUserRequest;
use App\Models\Admin_documentos_categoria_vehiculo;
use App\Models\Admin_documentos_vehiculo;
use App\Models\Documentos_cargos;
use App\Models\Documentos_cargos_admin;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Sistema\Tipo_Vehiculo;
use App\Models\Sistema\Tipo_Vinculacion;
use App\Models\Sistema\Tipo_Carroceria;
use App\Models\Sistema\Admin_inspeccion;
use App\Models\Sistema\Marca;
use App\Models\Sistema\Linea;
use App\Models\Sistema\Cargo;
use App\Models\Sistema\Proveedor;
use App\Models\Sistema\Departamento;
use App\Models\Sistema\Municipio;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    // public function __construct() {
    //     $this->middleware('auth');
    // }

    public function users() {
        $users = User::paginate(10);

        return view('admin.users', ['users' => $users]);
    }

    public function filtro(){
        $users = User::select('users.*');

        if(isset($_GET['estado']) && $_GET['estado'] != null){
            if($_GET['estado'] == "true"){
                $users = $users->where('estado','Activo');
            }else{
                $users = $users->where('estado','Inactivo');
            }
        }
        if(isset($_GET['search']) && $_GET['search'] != null) {
            $users = $users->where('identificacion', 'like', "%".$_GET['search']."%");
            $users = $users->orWhere('name', 'like', "%".$_GET['search']."%");
            $users = $users->orWhere('email', 'like', "%".$_GET['search']."%");
        }
        if(isset($_GET['ordenarpor']) && $_GET['ordenarpor'] != null){
            $users = $users->orderBy($_GET['ordenarpor']);
        }
        $users = $users->paginate(10);

        return view('admin.users', ['users' => $users]);
    }

    public function createUser(FormCreateUserRequest $request) {

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
            return redirect()->route('users')->with('create', 1);
        } else {
            return redirect()->route('users')->with('create', 0);
        }

    }

    public function showUser(Request $request) {
        $user = User::find($request['id']);
        $permisos = array();

        foreach ($user->permissions as $permiso) {
            array_push($permisos, $permiso->name);
        }

        return [
            'user' => $user,
            'rol' => $user->roles()->first()->name,
            'permisos' => $permisos
        ];
    }

    public function updateUser(Request $request) {

        $user = User::find($request['id']);

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

        if ($request['tipo'] == 'admin') {
            $user->assignRole($request['tipo']);
            $user->removeRole('general');
        } else {
            $user->assignRole($request['tipo']);
            $user->removeRole('admin');
            $user->revokePermissionTo(Permission::all());
            $user->givePermissionTo($request['permisos']);
        }
        return redirect()->route('users')->with('update', 1);

    }

    public function admin_vehiculos() {
        $clasificacion_carga = Tipo_Vehiculo::where('categoria_vehiculo', 'Carga')->get();
        $clasificacion_especial = Tipo_Vehiculo::where('categoria_vehiculo', 'Especial')->get();
        $vinculacion = Tipo_Vinculacion::all();
        $carroceria = Tipo_Carroceria::all();
        $marca = Marca::all();
        $linea = Linea::all();

        return view('admin.vehiculos', [
            'clasificacion_carga' => $clasificacion_carga,
            'clasificacion_especial' => $clasificacion_especial,
            'vinculacion' => $vinculacion,
            'carroceria' => $carroceria,
            'marca' => $marca,
            'linea' => $linea,
        ]);
    }

    public function agg_datos_vehiculo(Request $request) {

        switch ($request['tipo']) {
            case 'Clasificacion':
                Tipo_Vehiculo::create(['nombre' => $request['nombre'], 'categoria_vehiculo' => $request['rama']])->save();

                return redirect()->route('datos-vehiculos')->with(['create' => 1]);
                break;

            case 'Marca':
                Marca::create(['nombre' => $request['nombre']])->save();

                return redirect()->route('datos-vehiculos')->with(['create' => 1]);
                break;

            case 'Tipo Vinculacion':
                Tipo_Vinculacion::create(['nombre' => $request['nombre']])->save();

                return redirect()->route('datos-vehiculos')->with(['create' => 1]);
                break;

            case 'Tipo Carroceria':
                Tipo_Carroceria::create(['nombre' => $request['nombre']])->save();

                return redirect()->route('datos-vehiculos')->with(['create' => 1]);
                break;

            case 'Linea':
                Linea::create(['nombre' => $request['nombre']])->save();

                return redirect()->route('datos-vehiculos')->with(['create' => 1]);
                break;
        }

    }

    public function cargos() {
        $cargo = Cargo::paginate(20);
        foreach ($cargo as $key => $carg) {
            $arrayId = Documentos_cargos::where('cargos_id', $carg->id)->select('documentos_cargos.documentos_cargos_id')->get();
            $arrayId = Arr::pluck($arrayId, 'documentos_cargos_id');
            $cargo[$key]->documentos = Documentos_cargos_admin::whereIn('id', $arrayId)->get();
        }
        
        return view('admin.cargos', ['cargos' => $cargo]);

    }

    public function agg_cargo(Request $request) {
        if($request['id_cargo'] != '' && $request['id_cargo'] != null){
            $cargo = Cargo::find($request['id_cargo']);
            $cargo->update($request->except('documentos_cargos'));
            $Noteliminar = [];
            foreach ($request['documentos_cargos'] as $key => $doc) {
                $Noteliminar[] = Documentos_cargos::where('cargos_id', $cargo->id)->where('documentos_cargos_id', $doc)->first()['id'] ?? 0;
            }
            
            Documentos_cargos::where('cargos_id', $cargo->id)->whereNotIn('id', $Noteliminar)->delete();

            $Noteliminar = Documentos_cargos::where('cargos_id', $cargo->id)->whereIn('id', $Noteliminar)->get();
            
            foreach ($request['documentos_cargos'] as $key => $doc) {
                $crear = 0;
                foreach ($Noteliminar as $key => $ntli) {
                    if($doc == $ntli->documentos_cargos_id){
                        $crear = 1;
                    }
                }
                if($crear == 0){
                    Documentos_cargos::create([
                        'cargos_id' => $cargo->id,
                        'documentos_cargos_id' => $doc
                    ]);
                }
            }

            return redirect()->route('cargos')->with(['create_doc' => 1, 'mensaje' => 'Se ha editado correctamente el cargo']);
        }else{
            $cargo = Cargo::create($request->all());
            if ($cargo->save()) {
                foreach ($request['documentos_cargos'] as $key => $doc) {
                    Documentos_cargos::create([
                        'cargos_id' => $cargo->id,
                        'documentos_cargos_id' => $doc
                    ]);
                }
                return redirect()->route('cargos')->with(['create' => 1]);
            }
        }

        return redirect()->route('cargos')->with(['create' => 0]);
    }

    public function agg_categoria_documentos_vehiculo(Request $request){
        $create = Admin_documentos_categoria_vehiculo::create([
            'categoria' => "$request->nombre"
        ]);

        return redirect()->route('admin_documentos_vehiculos')->with(['create' => 1]);

    }

    public function agg_documentos_vehiculo(Request $request){
        Admin_documentos_vehiculo::create([
            'name' => $request->nombre,
            'vigencia' => $request->vigencia,
            'categoria_id' => $request->categoria,
            'tipo_tercero' => $request->tipo_tercero,
            'proceso' => $request->proceso,
        ]);

        return redirect()->route('admin_documentos_vehiculos')->with(['create' => 2]);
    }

    public function edit_documentos_vehiculo(Request $request){
        Admin_documentos_vehiculo::find($request->id)->update([
            'name' => $request->nombre,
            'vigencia' => $request->vigencia,
            'tipo_tercero' => $request->tipo_tercero,
            'proceso' => $request->proceso,
        ]);

        return redirect()->route('admin_documentos_vehiculos')->with(['edit' => 1]);
    }

    function eliminar_doc_vehiculo(Request $request){
        Admin_documentos_vehiculo::find($request->id)->delete();
        return redirect()->route('admin_documentos_vehiculos')->with(['delete' => 1]);
    }




    public function inspecciones() {
        $generalidades = Admin_inspeccion::where('tipo', 'Generalidades')->get();
        $botiquin = Admin_inspeccion::where('tipo', 'Botiquin')->get();
        $luces = Admin_inspeccion::where('tipo', 'Luces y estado mecanico')->get();
        $equipos = Admin_inspeccion::where('tipo', 'Equipos de carretera')->get();

        return view('admin.inspecciones', [
            'generalidades' => $generalidades,
            'botiquin' => $botiquin,
            'luces' => $luces,
            'equipos' => $equipos,
        ]);
    }

    public function agg_admin_inspeccion(Request $request) {
        if (Admin_inspeccion::create($request->all())) {
            return redirect()->route('inspecciones')->with(['create' => 1]);
        }

        return redirect()->route('inspecciones')->with(['create' => 0]);
    }

    public function departamentos() {
        return Departamento::all();
    }

    public function municipios(Request $request) {
        return Departamento::where('nombre', $request['dpt'])->with('municipios')->first();
    }

    public function admin_documentos_vehiculos(){
        
        $categorias = Admin_documentos_categoria_vehiculo::all();
        $clasificacion = Tipo_Vehiculo::all();
        $vinculacion = Tipo_Vinculacion::all();
        $carroceria = Tipo_Carroceria::all();
        $marca = Marca::all();
        $linea = Linea::all();

        return view('admin.documentos_vehiculos', [
            'clasificacion' => $clasificacion,
            'vinculacion' => $vinculacion,
            'carroceria' => $carroceria,
            'marca' => $marca,
            'linea' => $linea,
            'categorias' => $categorias,
        ]);
    }


    public function agg_documeto_cargo(Request $request){
        $documento = 0;
        if($request['id_doc'] == '' || $request['id_doc'] == null){
            $documento = Documentos_cargos_admin::create($request->except('id_doc'));
            if($documento->save()){
                return redirect()->back()->with(['create_doc' => 1, 'mensaje' => 'Documento agregado correctamente']);
            }
        }else{
            $documento = Documentos_cargos_admin::find($request['id_doc'])->update($request->except('id_doc'));
            if($documento){
                return redirect()->back()->with(['create_doc' => 1, 'mensaje' => 'Documento editado correctamente']);
            }
        }


        return redirect()->back()->with(['error' => 1, 'mensaje' => 'Ocurrio un error con el registro']);


    }


    public function eliminar_documento_cargos($id){
        Documentos_cargos_admin::find($id)->delete();
        return redirect()->back()->with(['create_doc' => 1, 'mensaje' => 'Documento eliminado correctamente']);
    }




}
