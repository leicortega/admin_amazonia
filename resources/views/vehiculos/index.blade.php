@section('title') Vehiculos @endsection

@section('Plugins')
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
@endsection

@extends('layouts.app')

@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row p-xl-5 p-md-3">
                            <div class="table-responsive mb-3" id="Resultados">

                                @if ($errors->any())
                                    <div class="alert alert-danger mb-0" role="alert">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (session()->has('respuesta') && session('respuesta') == 1)
                                    <div class="alert alert-success">
                                        El correo fue enviado correctamente.
                                    </div>
                                @endif

                                <button type="button" class="btn btn-primary btn-lg float-right mb-2" data-toggle="modal" data-target="#aggVehiculo">Agregar +</button>

                                <table class="table table-centered table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th colspan="12" class="text-center">
                                                <div class="d-inline-block icons-sm mr-2"><i class="uim uim-comment-alt-message"></i></div>
                                                <span class="header-title mt-2">Vehiculos</span>
                                            </th>
                                        </tr>
                                        <!--Parte de busqueda de datos-->
                                        <tr>
                                            <th colspan="12" class="text-center">
                                                {{-- <form action="/dashboard/programacion-viaje/get-ciudades" method="get" class="d-inline-block w-50">
                                                    @csrf

                                                    <div class="row col-12 text-center">
                                                        <div class="styled-select col-5">
                                                            <select class="form-control required" id="ciudad_origen" name="ciudad_origen" required onchange="ciudadDestino(this.value)">
                                                                <option value="">Ciudad Origen</option>
                                                            </select>
                                                        </div>
                                                        <div class="styled-select col-5">
                                                            <select class="form-control required" id="ciudad_destino" name="ciudad_destino" required>
                                                                <option value="">Ciudad Destino</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-2">
                                                            <button type="submit" class="btn btn-primary">Buscar</button>

                                                        </div>
                                                    </div>
                                                </form> --}}
                                            </th>
                                        </tr>
                                        <!--Fin parte de busqueda de datos-->
                                        <tr>
                                            <th scope="col">Placa</th>
                                            <th scope="col">N° Interno</th>
                                            <th scope="col">Propietario</th>
                                            <th scope="col">Tipo</th>
                                            <th scope="col">Marca</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vehiculos as $vehiculo)
                                            <tr>
                                                <th>{{ $vehiculo->placa }}</th>
                                                <td>{{ $vehiculo->numero_interno }}</td>
                                                <td>{{ $vehiculo->nombres .' '. $vehiculo->primer_apellido }}</td>
                                                <td>{{ $vehiculo->nombre_tipo_vehiculo }}</td>
                                                <td>{{ $vehiculo->nombre_marca }}</td>
                                                <td class="text-center">
                                                    <a href="/vehiculos/ver/{{ $vehiculo->id_vehiculo }}"><button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Vehiculo">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button></a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                            {{ $vehiculos->links() }}

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<div class="modal fade bs-example-modal-xl" id="aggVehiculo" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-correo">Agregar Vehiculo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/vehiculos/create" method="POST">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="placa" name="placa" required="">
                                    <label for="placa">Placa</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_vehiculo_id" class="form-control" id="tipo_vehiculo_id" required>
                                        <option value=""></option>
                                        @foreach (\App\Models\Sistema\Tipo_Vehiculo::all() as $tipo_vehiculo)
                                            <option value="{{ $tipo_vehiculo->id }}">{{ $tipo_vehiculo->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label for="tipo_vehiculo_id">Tipo Vehiculo</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" id="licencia_transito" name="licencia_transito" required="">
                                    <label for="licencia_transito">Licencia de Transito</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="marca_id" class="form-control" id="marca_id" required>
                                        <option value=""></option>
                                        @foreach (\App\Models\Sistema\Marca::all() as $marca)
                                            <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label for="marca_id">Marca</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" id="modelo" name="modelo" required="">
                                    <label for="modelo">Modelo</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" id="capacidad" name="capacidad" required="">
                                    <label for="capacidad">Capacidad</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control" id="numero_motor" name="numero_motor" required="">
                                        <label for="numero_motor">Numero de Motor</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="chasis" name="chasis" required="">
                                    <label for="chasis">Chasis</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" id="numero_interno" name="numero_interno" required="">
                                    <label for="numero_interno">Numero Interno</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <div class="form-group form-group-custom mb-4">
                                        <select name="personal_id" class="form-control" id="personal_id" required>
                                            <option value=""></option>
                                            @foreach ($propietarios as $propietario)
                                                <option value="{{ $propietario->id }}">{{ $propietario->nombres }} {{ $propietario->primer_apellido }} {{ $propietario->segundo_apellido }}</option>
                                            @endforeach
                                        </select>
                                        <label for="personal_id">Propietario</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_vinculacion_id" class="form-control" id="tipo_vinculacion_id" required>
                                        <option value=""></option>
                                        @foreach (\App\Models\Sistema\Tipo_Vinculacion::all() as $tipo_vinculacion)
                                            <option value="{{ $tipo_vinculacion->id }}">{{ $tipo_vinculacion->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label for="tipo_vinculacion_id">Tipo Vinculacion</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" id="tarjeta_operacion" name="tarjeta_operacion" required="">
                                    <label for="tarjeta_operacion">Tarjeta Operación</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group form-group-custom mb-4">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control" id="color" name="color" required="">
                                        <label for="color">Color</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="linea_id" class="form-control" id="linea_id" required>
                                        <option value=""></option>
                                        @foreach (\App\Models\Sistema\Linea::all() as $linea)
                                            <option value="{{ $linea->id }}">{{ $linea->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label for="linea_id">Linea</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_carroceria_id" class="form-control" id="tipo_carroceria_id" required>
                                        <option value=""></option>
                                        @foreach (\App\Models\Sistema\Tipo_Carroceria::all() as $tipo_carroceria)
                                            <option value="{{ $tipo_carroceria->id }}">{{ $tipo_carroceria->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label for="tipo_carroceria_id">Tipo de carroceria</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="estado" class="form-control" id="estado" required>
                                        <option value=""></option>
                                        <option value="Activo">Activo</option>
                                        <option value="inactivo">inactivo</option>
                                    </select>
                                    <label for="estado">Estado</label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn-submit-correo" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection







