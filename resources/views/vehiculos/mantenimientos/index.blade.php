@section('title') Mantenimiento Vehiculos @endsection

@section('Plugins')
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
    <script src="{{ asset('assets/libs/selectize/js/standalone/selectize.min.js') }}"></script>
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

                                @if (session()->has('error') && session()->has('mensaje'))
                                    <div class="alert {{ session('error') == 0 ? 'alert-success' : 'alert-danger' }}">
                                        {{ session('mensaje') }}
                                    </div>
                                @endif

                                {{-- botones de filtro --}}
                                <button type="button" class="btn btn-primary btn-lg float-left mb-2" data-toggle="modal" data-target="#modal-filtro">Filtrar <i class="fa fa-filter" aria-hidden="true"></i>
                                </button>


                                @if(request()->routeIs('mantenimientos_filtro'))
                                    <a href="{{route('mantenimientos')}}" class="btn btn-primary btn-lg mb-2 float-left ml-1">
                                        Limpiar <i class="fa fa-eraser" aria-hidden="true"></i>
                                    </a>
                                @endif
                                {{-- end botones de fitro --}}

                                {{-- <div class="container-fluid">
                                    <div class="row p-0">
                                        <div class="col-8">
                                            @role('admin')
                                                <div class="form-group mb-4">
                                                    <label>Seleccione el vehiculo</label>
                                                    <select class="selectize" onchange="window.location.href=this.value">
                                                        <option value="">Seleccione</option>
                                                        @foreach ($vehiculos as $vehiculo)
                                                            <option value="/vehiculos/{{ $vehiculo->id }}/mantenimientos"><a href="/vehiculos/{{ $vehiculo->id }}/mantenimientos">{{ $vehiculo->placa }}</a></option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endrole
                                        </div>

                                    </div>

                                </div> --}}
                                <div class="">
                                            <button type="button" class="btn btn-primary btn-lg float-right mb-2" data-toggle="modal" data-target="#solicitar_mantenimiento_modal">Solicitar</button>
                                </div>


                                <table class="table table-centered table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th colspan="12" class="text-center">
                                                <div class="d-inline-block icons-sm mr-2"><i class="uim uim-comment-alt-message"></i></div>
                                                <span class="header-title mt-2">Mantenimientos solicitados</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Placa</th>
                                            <th scope="col">Encargado</th>
                                            <th scope="col">Fecha y hora</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col">Motivo</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($solicitados as $solicitado)
                                            <tr>
                                                <th>{{ $solicitado->vehiculo->placa }}</th>
                                                <th>{{ $solicitado->personal->nombres }} {{ $solicitado->personal->primer_apellido }}</th>
                                                <th>{{ $solicitado->fecha }}</th>
                                                <th>{{ $solicitado->estado }}</th>
                                                <th>{{ $solicitado->descripcion_solicitud }}</th>
                                                <td class="text-center">
                                                    <a href="/vehiculos/ver/mantenimiento/{{ $solicitado->id }}"><button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Solicitud">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $solicitados->appends(request()->input())->links() }}

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<div class="modal fade bs-example-modal-xl" id="solicitar_mantenimiento_modal" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-correo">Solicitar Mantenimiento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/vehiculos/solicitar_mantenimiento" method="POST">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-6">
                                <label class="col-sm-12 col-form-label">Fecha Solicitud</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" type="text" name="fecha" id="fecha" placeholder="yyyy-mm-dd" required/>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="col-sm-12 col-form-label" for="tipo_vehiculo_id">Vehiculo</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select class="selectize" name="vehiculo_id" id="vehiculo_id" required>
                                        <option value="">Seleccione</option>
                                        @foreach ($vehiculos as $vehiculo)
                                            <option value="{{ $vehiculo->id }}">{{ $vehiculo->placa }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="w-100">

                        <div class="row">
                            <div class="col-sm-6">
                                <label class="col-sm-12 col-form-label" for="personal_id">Persona encargada</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="personal_id" class="form-control" id="personal_id" required>
                                        <option value="">Seleccione</option>
                                        @foreach (\App\Models\Personal::all() as $persona)
                                            <option value="{{ $persona->id }}">{{ $persona->nombres }} {{ $persona->primer_apellido }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="w-100">

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="descripcion_solicitud">Describa de manera clara el motivo del mantenimiento</label>
                                <textarea type="text" class="form-control" id="descripcion_solicitud" name="descripcion_solicitud" required="" rows="10"></textarea>
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

                <form action="{{route('mantenimientos_filtro')}}" id="form-create-tercero" method="GET">
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
                                        <option value="encargado">Encargado</option>
                                        <option value="fecha_hora">Fecha y hora</option>
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Encargado</label>
                                    <select name="encargado" id="propietario" class="form-control">
                                        <option value="">Selecciona</option>
                                        @foreach ($usuarios as $item)
                                            <option value="{{$item->id}}">{{$item->nombres . $item->primer_apellido . $item->segundo_apellido}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Estado</label>
                                    <select name="estado" id="tipo" class="form-control">
                                        <option value="">Selecciona</option>
                                        <option value="Aprobado">Aprobado</option>
                                        <option value="Cerrado">Cerrado</option>
                                        <option value="Solicitado">Solicitado</option>
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Fecha</label>
                                    <input type="text" class="form-control datepicker-here" name="fecha" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd">
                                </div>

                            </div>
                        </div>

                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">
                                <div class="col-sm-3">
                                    @role('admin')
                                        <div class="form-group mb-4">
                                            <label>Seleccione placa del vehiculo</label>
                                            <select class="selectize" name="placa">
                                                <option value="">Seleccione</option>
                                                @foreach ($vehiculos as $vehiculo)
                                                    <option value="{{ $vehiculo->id }}">{{ $vehiculo->placa }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endrole
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group mb-4">
                                        <label>Rango de fechas</label>
                                        <input type="text" class="form-control datepicker-here" name="fecha_range" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" data-range="true" data-multiple-dates-separator=" - ">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-4">
                                        <label>Buscar</label>
                                        <input type="text" class="form-control" placeholder="Buscar Motivos" name="search"/>
                                    </div>
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







