@section('title','Permisos')

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
                        
                        <h4 class="header-title mb-3">Permisos</h4>
                        @if (session()->has('msg'))
                            <div class="alert alert-primary">
                                {{session('msg')}}
                            </div>
                        @endif

                        <!-- Tab panes -->
                        <div class="tab-content p-3">
                            <button type="button" data-toggle="modal" onclick="createpermiso()" data-target="#modal-permiso" class="btn btn-primary my-3 btn-lg">Agregar +</button>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permisos as $permiso)
                                        <tr>
                                            <td scope="row">{{$permiso->name}}</td>
                                            <td>
                                                <button type="button" data-toggle="modal" data-target="#modal-permiso" class="btn btn-primary" onclick="cargarpermiso({{$permiso->id}},'{{$permiso->name}}')"><i class="fas fa-edit"></i></button>
                                                <button type="button" id="deletepermiso{{$permiso->id}}" class="btn btn-danger" onclick="deletepermiso({{$permiso->id}})"><i class="fas fa-trash"></i></button>
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

<div class="modal fade bs-example-modal-lg" id="modal-permiso" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-permisos" onclick="createpermiso()">Agregar Permisos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="limipiarmodal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" id="form-permisos" method="POST" >
                    @csrf
                
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="name" id="name" placeholder="Escriba el nombre" required />
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="button-form-permisos" type="submit">Agregar</button>
                    </div> 
                
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
