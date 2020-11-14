<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormCreateUserRequest;
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
use App\User;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function users() {
        $users = User::paginate(10);

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
        $clasificacion = Tipo_Vehiculo::all();
        $vinculacion = Tipo_Vinculacion::all();
        $carroceria = Tipo_Carroceria::all();
        $marca = Marca::all();
        $linea = Linea::all();

        return view('admin.vehiculos', [
            'clasificacion' => $clasificacion,
            'vinculacion' => $vinculacion,
            'carroceria' => $carroceria,
            'marca' => $marca,
            'linea' => $linea,
        ]);
    }

    public function agg_datos_vehiculo(Request $request) {

        switch ($request['tipo']) {
            case 'Clasificacion':
                Tipo_Vehiculo::create(['nombre' => $request['nombre']])->save();

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
        return view('admin.cargos', ['cargos' => Cargo::paginate(20)]);
    }

    public function agg_cargo(Request $request) {
        if (Cargo::create($request->all())->save()) {
            return redirect()->route('cargos')->with(['create' => 1]);
        }

        return redirect()->route('cargos')->with(['create' => 0]);
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
}
