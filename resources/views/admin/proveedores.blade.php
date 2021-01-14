@section('title') Administrar Proveedores @endsection

@extends('layouts.app')

@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
            
                        <h4 class="header-title mb-3">Proveedores</h4>
                        @if (session()->has('create') && session('create') == 1)
                            <div class="alert alert-primary" role="alert">
                                Proveedor creado correctamente
                            </div>
                        @endif

                        
                        <button type="button" data-toggle="modal" data-target="#modal-create-datos-vehiculo" class="btn btn-primary my-3 btn-lg">Agregar +</button>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proveedores as $proveedor)
                                    <tr>
                                        <td>{{ $proveedor->nombre }}</td>
                                        <td><button type="button" onclick="editar_datos_vehiculo({{ $proveedor->id }}, 'Clasificacion')" class="btn btn-primary"><i class="fas fa-edit"></i></button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $proveedores->links() }}

                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<div class="modal fade bs-example-modal-lg" id="modal-create-datos-vehiculo" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-create-datos-vehiculo-title">Agregar Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('add_proveedores')}}" method="POST">
                    @csrf
                
                    <div class="form-group row">
                        <label for="name" class="col-sm-12 col-form-label">Nombre del proveedor</label>
                        <div class="col-sm-12">
                            <input class="form-control" type="text" name="nombre" placeholder="Escriba el nombre" required />
                        </div>
                    </div>
                
                    <div class="mt-4 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Agregar</button>
                    </div> 
                
                </form>
            </div>
        </div>
    </div>
</div>
@endsection