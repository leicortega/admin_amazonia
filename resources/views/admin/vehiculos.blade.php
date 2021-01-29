@section('title') Usuarios @endsection

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
            
                        <h4 class="header-title mb-3">Datos Vehiculos</h4>
                        @if (session()->has('create') && session('create') == 1)
                            <div class="alert alert-primary" role="alert">
                                Dato creado correctamente
                            </div>
                        @endif

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#Clasificacion" role="tab">
                                    <span class="d-none d-md-inline-block">Clasificacion</span> 
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#Marca" role="tab">
                                    <span class="d-none d-md-inline-block">Marca</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#Tipo_Vinculacion" role="tab">
                                    <span class="d-none d-md-inline-block">Tipo Vinculacion</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#Tipo_Carroceria" role="tab">
                                    <span class="d-none d-md-inline-block">Tipo Carroceria</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#Linea" role="tab">
                                    <span class="d-none d-md-inline-block">Linea</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3">
                            <div class="tab-pane active" id="Clasificacion" role="tabpanel">
                                <button type="button" onclick="datos_vehiculos('Clasificacion')" class="btn btn-primary my-3 btn-lg">Agregar +</button>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($clasificacion as $clasificacion_item)
                                            <tr>
                                                <td scope="row">{{ $clasificacion_item->nombre }}</td>
                                                <td><button type="button" onclick="editar_datos_vehiculo({{ $clasificacion_item->id }}, 'Clasificacion')" class="btn btn-primary"><i class="fas fa-edit"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="Marca" role="tabpanel">
                                <button type="button" onclick="datos_vehiculos('Marca')" class="btn btn-primary my-3 btn-lg">Agregar +</button>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($marca as $marca_item)
                                            <tr>
                                                <td scope="row">{{ $marca_item->nombre }}</td>
                                                <td><button type="button" onclick="editar_datos_vehiculo({{ $marca_item->id }}, 'Marca')" class="btn btn-primary"><i class="fas fa-edit"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="Tipo_Vinculacion" role="tabpanel">
                                <button type="button" onclick="datos_vehiculos('Tipo Vinculacion')" class="btn btn-primary my-3 btn-lg">Agregar +</button>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vinculacion as $vinculacion_item)
                                            <tr>
                                                <td scope="row">{{ $vinculacion_item->nombre }}</td>
                                                <td><button type="button" onclick="editar_datos_vehiculo({{ $vinculacion_item->id }}, 'Tipo Vinculacion')" class="btn btn-primary"><i class="fas fa-edit"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="Tipo_Carroceria" role="tabpanel">
                                <button type="button" onclick="datos_vehiculos('Tipo Carroceria')" class="btn btn-primary my-3 btn-lg">Agregar +</button>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($carroceria as $carroceria_item)
                                            <tr>
                                                <td scope="row">{{ $carroceria_item->nombre }}</td>
                                                <td><button type="button" onclick="editar_datos_vehiculo({{ $carroceria_item->id }}, 'Tipo Carroceria')" class="btn btn-primary"><i class="fas fa-edit"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="Linea" role="tabpanel">
                                <button type="button" onclick="datos_vehiculos('Linea')" class="btn btn-primary my-3 btn-lg">Agregar +</button>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($linea as $linea_item)
                                            <tr>
                                                <td scope="row">{{ $linea_item->nombre }}</td>
                                                <td><button type="button" onclick="editar_datos_vehiculo({{ $linea_item->id }}, 'Linea')" class="btn btn-primary"><i class="fas fa-edit"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

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
                <h5 class="modal-title mt-0" id="modal-create-datos-vehiculo-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/admin/sistema/vehiculos/agg_datos_vehiculo" method="POST" onsubmit="cargarbtn('#agregar_dat_vehiculos')">
                    @csrf
                
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="nombre" placeholder="Escriba el nombre" required />
                        </div>
                    </div>

                    <input type="hidden" name="tipo" id="datos_vehiculo_tipo">
                
                    <div class="mt-4 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="agregar_dat_vehiculos" type="submit">Agregar</button>
                    </div> 
                
                </form>
            </div>
        </div>
    </div>
</div>
@endsection







