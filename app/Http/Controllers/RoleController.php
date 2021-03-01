<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\FormCreateRolRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles=Role::get();
        return view('admin.roles.roles',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //enviar los permisos

        $permisos=Permission::get();
        return $permisos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormCreateRolRequest $request)
    {
        $role=Role::create(['name' => $request['name']]);
        $role->givePermissionTo($request['permisos']);
        return 'Creado correctamente';
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // mostrar los permisos del rol
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //cosultando datos del rol
        $role=Role::findorfail($id);
        $permissions=Permission::get();
        
        return view('admin.roles.edit',compact(['role','permissions']));
        return $role;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role=Role::findorfail($id);
        $role->update($request->all());
        $role->revokePermissionTo(Permission::all());
        $role->givePermissionTo($request['permisos']);
        return redirect()->back()->with(['update' => 2]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role=Role::findorfail($id);
        $role->revokePermissionTo(Permission::all());
        $role->delete();
        return ['msg' => 'eliminado con exito'];
        
    }
}
