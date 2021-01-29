@section('title') Solicitud De Dinero @endsection

@section('Plugins')
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
    <script src="{{ asset('assets/libs/selectize/js/standalone/selectize.min.js') }}"></script>
    <script src="{{ asset('assets/js/solicitud_dinero.js') }}"></script>
@endsection

@section('jsMain') <script src="{{ asset('assets/js/solicitud_dinero.js') }}"></script> @endsection

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

                                @if (session()->has('create'))
                                    <div class="alert {{ (session()->has('create') == 1) ? 'alert-success' : 'alert-danger' }}">
                                        {{ session('mensaje') }}
                                    </div>
                                @endif

                                <a href="{{ route('index') }}"><button type="button" class="btn btn-dark btn-lg mb-2 float-left" onclick="cargarbtn(this)">Atras</button></a>

                                {{-- botones de filtro --}}
                                <button type="button" class="btn btn-primary btn-lg float-left ml-2 mb-2" data-toggle="modal" data-target="#modal-filtro">Filtrar <i class="fa fa-filter" aria-hidden="true"></i>
                                </button>


                                @if(request()->routeIs('solicitud_filtro'))
                                    <a href="{{route('solicitud_dinero')}}" class="btn btn-primary btn-lg mb-2 float-left ml-1" onclick="cargarbtn(this)">
                                        Limpiar <i class="fa fa-eraser" aria-hidden="true"></i>
                                    </a>
                                @endif
                                {{-- end botones de fitro --}}


                                <div class="">
                                            <button type="button" class="btn btn-primary btn-lg float-right mb-2" data-toggle="modal" data-target="#solicitar_dinero_modal">Solicitar <i class="fas fa-hand-holding-usd"></i></button>
                                </div>


                                <table class="table table-centered table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th colspan="12" class="text-center">
                                                <div class="d-inline-block icons-sm mr-2"><i class="fas fa-comment-dollar c-green"></i></div>
                                                <span class="header-title mt-2">Solicitudes De Dinero</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-center"><b>Solicitante</b></th>
                                            <th class="text-center"><b>Beneficiario</b></th>
                                            <th class="text-center"><b>Fecha</b></th>
                                            <th class="text-center"><b>Tipo</b></th>
                                            <th class="text-center"><b>Descripción</b></th>
                                            <th class="text-center"><b>Acciones</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($solicitudes as $solicitud)
                                            <tr>
                                                <th>{{ $solicitud->name }}</th>
                                                <th>{{ $solicitud->nombres}} {{$solicitud->primer_apellido}}</th>
                                                <th>{{ Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d-m-Y') }}</th>
                                                <th>{{ $solicitud->tipo_solicitud }}</th>
                                                <th>{{ $solicitud->descripcion }}</th>
                                                <td class="text-center">
                                                    <a href="{{route('solicitud_dinero_ver', $solicitud->id)}}"><button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Solicitud" onclick="cargarbtn(this)">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $solicitudes->appends(request()->input())->links() }}

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<div class="modal fade bs-example-modal-xl" id="solicitar_dinero_modal" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-solicitud">Solicitar Dinero</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{route('solicitud_dinero_create')}}" method="POST" onsubmit="cargarbtn('#solicitar_dinero_btn')">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-4">
                                <label class="col-sm-12 col-form-label">Fecha Solicitud</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" type="text" name="fecha" id="fecha" placeholder="yyyy-mm-dd" required/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="col-sm-12 col-form-label" for="tipo_solicitud">Tipo De Solicitud</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo" class="form-control" id="tipo_solicitud" required>
                                        <option value="">Seleccione</option>
                                        <option value="Viaticos">Viáticos</option>
                                        <option value="Mantenimientos">Mantenimiento</option>
                                        <option value="Otros">Otros</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <label class="col-sm-12 col-form-label" for="beneficiario">Beneficiario</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="beneficiario" class="form-control" id="beneficiario" >
                                        <option value="">Seleccione</option>
                                        @foreach ($beneficiarios as $beneficiario)
                                            <option value="{{$beneficiario->id}}">{{$beneficiario->nombres}} {{$beneficiario->primer_apellido}} {{$beneficiario->segundo_apellido}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="w-100">

                        <div class="row">
                            <div class="col-sm-5">
                                <label class="col-sm-12 col-form-label">Conceptos</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" placeholder="Defina un concepto" name="concepto[]" required/>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <label class="col-sm-12 col-form-label" >Valor</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" placeholder="Precio" name="precio[]" required/>
                                </div>
                            </div>
                                                            
                            <div class="col-sm-2 mt-4">
                                <div class="form-group form-group-custom mb-4 mt-2">
                                    <a href="javascript:void(0)" onclick="agrega_concepto()" class="add_concepto btn btn-primary btn-lg mb-2 float-left mt-1" data-toggle="tooltip" data-placement="top" title="Agregar Concepto"><i class="fas fa-plus"></i> Conceptos</a>
                                </div>
                            </div>
                        </div>

                        <div class="add_concept_campo">
                        </div>

                        <hr class="w-100">

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="descripcion_solicitud">Describa de manera clara el motivo de la solicitud de dinero</label>
                                <textarea type="text" class="form-control" id="descripcion_solicitud" name="descripcion_solicitud" required="" rows="5"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="solicitar_dinero_btn" type="submit">Enviar</button>
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

                <form action="{{route('solicitud_filtro')}}" id="form-create-tercero" method="GET" onsubmit="cargarbtn('#agregar_filter')">
                    @csrf
                    <div class="container">
                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Ordenar Por</label>
                                    <select name="ordenarpor" class="form-control">
                                        <option value="">Selecciona </option>
                                        <option value="users.name">Solicitante</option>
                                        <option value="personal.nombres">Beneficiario</option>
                                        <option value="fecha_solicitud">Fecha y hora</option>
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Tipo</label>
                                    <select name="tipo" id="tipo" class="form-control">
                                        <option value="">Selecciona</option>
                                        <option value="Viaticos">Viaticos</option>
                                        <option value="Mantenimientos">Mantenimientos</option>
                                        <option value="Otros">Otros</option>
                                        
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Beneficiario</label>
                                    <select name="beneficiario" id="beneficiario" class="form-control">
                                        <option value="">Selecciona</option>
                                            @foreach (\App\Models\Personal::all() as $personal)
                                            <option value="{{ $personal->id }}">{{ $personal->nombres }} {{ $personal->primer_apellido }} {{ $personal->segundo_apellido }}</option>
                                            @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Solicitante</label>
                                    <select name="solicitante" id="solicitante" class="form-control">
                                        <option value="">Selecciona</option>
                                        @foreach (\App\User::all() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>

                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Fecha</label>
                                    <input type="text" class="form-control datepicker-here" name="fecha" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd">
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
                                        <input type="text" class="form-control" placeholder="Buscar" name="search"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="mt-5 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit" id="agregar_filter">Aplicar Filtros</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection