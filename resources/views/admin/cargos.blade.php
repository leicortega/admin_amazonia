@section('title') Administrar Cargos @endsection

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
            
                        <h4 class="header-title mb-3">Cargos</h4>
                        @if (session()->has('create') && session('create') == 1)
                            <div class="alert alert-primary" role="alert">
                                Cargo creado correctamente
                            </div>
                        @endif

                        
                        <button type="button" data-toggle="modal" data-target="#modal-create-datos-vehiculo" class="btn btn-primary my-3 btn-lg">Agregar +</button>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Funciones</th>
                                    <th>Obligaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cargos as $cargo)
                                    <tr>
                                        <td>{{ $cargo->nombre }}</td>
                                        <td style="font-size: 11px;">{{ $cargo->funciones }}</td>
                                        <td style="font-size: 11px;">{{ $cargo->obligaciones }}</td>
                                        <td><button type="button" onclick="editar_datos_vehiculo({{ $cargo->id }}, 'Clasificacion')" class="btn btn-primary"><i class="fas fa-edit"></i></button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $cargos->links() }}

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
                <h5 class="modal-title mt-0" id="modal-create-datos-vehiculo-title">Agregar Cargo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/admin/sistema/agg_cargo" method="POST" onsubmit="cargarbtn('#crear_datos_vehivulo')">
                    @csrf
                
                    <div class="form-group row">
                        <label for="name" class="col-sm-12 col-form-label">Nombre del cargo</label>
                        <div class="col-sm-12">
                            <input class="form-control" type="text" name="nombre" placeholder="Escriba el nombre" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-12 col-form-label">Funciones del cargo</label>
                        <div class="col-sm-12">
                            <textarea name="funciones" class="form-control" id="" cols="30" rows="5"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-12 col-form-label">Obligaciones</label>
                        <div class="col-sm-12">
                            <textarea name="obligaciones" class="form-control" id="" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                
                    <div class="mt-4 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="crear_datos_vehivulo" type="submit">Agregar</button>
                    </div> 
                
                </form>
            </div>
        </div>
    </div>
</div>
@endsection







