@section('title') Vehiculos @endsection

@section('Plugins')
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
@endsection

@section('jsMain')
    <script src="{{ asset('assets/js/vehiculos.js') }}"></script>
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

                                <a href="{{ route('index') }}"><button type="button" class="btn btn-dark btn-lg mb-2 float-left">Atras</button></a>

                                {{-- botones de filtro --}}

                                <button type="button" class="btn btn-primary btn-lg float-left ml-2 mb-2" data-toggle="modal" data-target="#modal-filtro">Filtrar <i class="fa fa-filter" aria-hidden="true"></i>
                                </button>



                                @if(request()->routeIs('vehiculos_filtro'))
                                    <a href="{{route('vehiculos')}}" class="btn btn-primary btn-lg mb-2 float-left ml-1">
                                        Limpiar <i class="fa fa-eraser" aria-hidden="true"></i>
                                    </a>
                                @endif

                                {{-- end botones de fitro --}}

                                @role('admin')<a href="{{route('agregar_vehiculo')}}"> <button type="button" class="btn btn-primary btn-lg float-right mb-2">Agregar +</button></a> @endrole

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
                                            <?php

                                                // if (auth()->user()->hasRole('general')) {

                                                //     if(\App\Models\Conductores_vehiculo::where('personal_id', \App\Models\Personal::where('identificacion', auth()->user()->identificacion)->first()->id)->where('vehiculo_id', $vehiculo->id_vehiculo)->count() == 0) {
                                                //         continue;
                                                //     }
                                                // }

                                            ?>
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

                            {{ $vehiculos->appends(request()->input())->links() }}

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>



{{-- AGREGAR FILTRO --}}
<div class="modal fade bs-example-modal-xl" id="modal-filtro" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-cotizacion">Agregar Filtros</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{route('vehiculos_filtro')}}" id="form-create-tercero" method="GET">
                    @csrf
                    <h5 class="modal-title" id="modal-title-cotizacion">Filtros</h5>
                    <div class="container">
                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Ordenar Por</label>
                                    <select name="ordenarpor" class="form-control">
                                        <option value="">Selecciona </option>
                                        <option value="placa">Placa</option>
                                        <option value="numero_interno">Nº Interno</option>
                                        <option value="personal.nombres">Propietario</option>
                                        <option value="tipo_vehiculo.nombre">Tipo</option>
                                        <option value="marca.nombre">Marca</option>
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Propietario</label>
                                    <select name="propietario" id="prpietario" class="form-control">
                                        <option value="">Selecciona</option>
                                        @foreach ($propietarios as $propietario)
                                            <option value="{{ $propietario->id }}">{{ $propietario->nombres }} {{ $propietario->primer_apellido }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Tipo</label>
                                    <select name="tipo" id="tipo" class="form-control">
                                        <option value="">Selecciona</option>
                                        @foreach (\App\Models\Sistema\Tipo_Vehiculo::all() as $tipo_vehiculo)
                                            <option value="{{ $tipo_vehiculo->id }}">{{ $tipo_vehiculo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Marca</label>
                                    <select name="marca" id="marca" class="form-control">
                                        <option value="">Selecciona</option>
                                        @foreach (\App\Models\Sistema\Marca::all() as $marca)
                                            <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>

                        <hr>
                        <h5 class="modal-title" id="modal-title-cotizacion">Buscar</h5>
                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" placeholder="Buscar por placa o nymero interno" name="search"/>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="mt-5 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Aplicar Filtros</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>



@endsection







