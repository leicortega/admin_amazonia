<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormCreateUserRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
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

            dd($request);
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
}
