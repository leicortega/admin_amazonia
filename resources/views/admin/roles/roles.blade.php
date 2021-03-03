@section('title','Roles')

@extends('layouts.app')

@section('jsMain')
    <script src="{{ asset('assets/js/admin.js') }}"></script>
@endsection

@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
            
                        <h4 class="header-title mb-3">Roles</h4>

                        @if (session()->has('create') && session('create') == 2)
                            <div class="alert alert-primary" role="alert">
                                Documento creado correctamente
                            </div>
                        @endif

                        @if (session()->has('delete') && session('delete') == 1)
                        <div class="alert alert-primary" role="alert">
                            Documento Eliminado correctamente
                        </div>
                    @endif

                        @if (session()->has('edit') && session('edit') == 1)
                        <div class="alert alert-primary" role="alert">
                            Documento editado correctamente
                        </div>
                    @endif

                        @if (session()->has('create') && session('create') == 1)
                        <div class="alert alert-primary" role="alert">
                            Categoria Creada correctamente
                        </div>
                        @endif

                        <!-- Nav tabs -->
                        

                        <!-- Tab panes -->
                        <div class="tab-content p-3">
                            <button type="button" data-toggle="modal" onclick="createrol()" data-target="#modal-create-documento-vehiculo" class="btn btn-primary my-3 btn-lg">Agregar +</button>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $rol)
                                        <tr>
                                            <td scope="row">{{$rol->name}}</td>
                                            <td>
                                                <a href="{{route('roles.edit',$rol->id)}}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                <button type="button" id="deleterol{{$rol->id}}" class="btn btn-danger" onclick="deleterol({{$rol->id}})"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<div class="modal fade bs-example-modal-lg" id="modal-create-documento-vehiculo" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-create-datos-vehiculo-title">Agregar Rol</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="limipiarmodal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" id="form_agregar_rol" method="POST" onsubmit="storerol(event)">
                    @csrf
                
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="name" id="name" placeholder="Escriba el nombre" required />
                        </div>
                    </div>
                    <div class="form-group row divPermisos">
                        <label for="permiso" class="col-sm-2 col-form-label">Permiso</label>
                        <div class="col-sm-10 mt-2"  id="permisos">

                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="crear_documento_veh" type="submit">Agregar</button>
                    </div> 
                
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
