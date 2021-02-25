@section('title') Correspondencia @endsection

@section('jsMain')
    <script src="{{ asset('assets/js/terceros.js') }}"></script>
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
                                    <div class="alert alert-danger mb-2" role="alert">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (session()->has('correspondencia') && session('correspondencia') == 1)
                                    <div class="alert alert-success">
                                        Correspondencia editada correctamente.
                                    </div>
                                @endif
                                @if (session()->has('correspondencia') && session('correspondencia') == 2)
                                    <div class="alert alert-success">
                                        Correspondencia Creada correctamente.
                                    </div>
                                @endif

                                @if (session()->has('correspondencia') && session('correspondencia') == 0)
                                    <div class="alert alert-error">
                                        Ocurrio un error, vuelva a intentarlo.
                                    </div>
                                @endif

                                <a href="/terceros/ver/{{$tercero->id}}"><button onclick="cargar_btn_single(this)" type="button" class="mr-2 btn btn-dark btn-lg mb-2 float-left">Atras</button></a>

                                {{-- botones de filtro --}}

                                <button type="button" class="btn btn-primary btn-lg float-left mb-2" onclick="cargarDepartamentos()" data-toggle="modal" data-target="#modal-filtro">Filtrar <i class="fa fa-filter" aria-hidden="true"></i>
                                </button>

                                @if(isset($_GET['ordenarpor']) || isset($_GET['tipo_radicacion']) || isset($_GET['origen']) || isset($_GET['dependencia']) || isset($_GET['search']) || isset($_GET['fecha']))
                                    <a href="{{route('correspondencia_index', $tercero->id)}}" class="btn btn-primary btn-lg mb-2 float-left ml-1" onclick="cargar_btn_single(this)">
                                        Limpiar <i class="fa fa-eraser" aria-hidden="true"></i>
                                    </a>
                                @endif


                                {{-- end botones de fitro --}}

                                <button type="button" class="mr-2 btn btn-primary btn-lg mb-2 float-right" data-toggle="modal" data-target="#modal_add_correspondencia" onclick="agregarcorrespondencia()">Agregar +</button>

                                <table class="table table-centered table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th colspan="12" class="text-center">
                                                <div class="d-inline-block icons-sm mr-2"><i class="uim fas fa-envelope-open mx-1"></i> </i></div>
                                                <span class="header-title mt-2">Correspondencia de {{$tercero['nombre']}}</span>
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
                                            <th scope="col">#</th>
                                            <th scope="col">Usuario</th>
                                            <th scope="col">Asunto</th>
                                            <th scope="col">Nº Folios</th>
                                            {{-- <th scope="col">Tipo Radicación</th>
                                            <th scope="col">Dependencia</th>
                                            <th scope="col">Origen</th> --}}
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($correspondencias as $key => $correspondencia)

                                            <tr>
                                                <th scope="row">
                                                    {{ $key+1 }}
                                                </th>
                                                <td>{{ $correspondencia->usuario }}</td>
                                                <td>{{ $correspondencia->asunto }}</td>
                                                <td>{{ $correspondencia->numero_folios }}</td>
                                                {{-- <td>{{ $correspondencia->nombre_radicacion }}</td>
                                                <td>{{ $correspondencia->nombre_dependencia }}</td>
                                                <td>{{ $correspondencia->nombre_origen }}</td> --}}
                                                <td>{{ \Carbon\Carbon::parse($correspondencia->created_at)->format('d-m-Y')  }}</td>
                                                <td class="text-center">
                                                    @if ($correspondencia->adjunto != '' && $correspondencia->adjunto != null)
                                                        <button type="button" class="btn btn-outline-secondary btn-sm" title="Ver Adjunto" onclick="ver_documento('{{$correspondencia->adjunto}}', 'Correspondencia', this)"><i class="mdi mdi-eye"></i></button>
                                                    @endif

                                                    <button type="button" class="btn btn-outline-secondary btn-sm" title="Editar Correspondencia" onclick="editar_correspondencia({{$correspondencia}})"><i class="mdi mdi-pencil"></i></button>

                                                    <a href="{{route('correspondencia_ver', $correspondencia->id)}}"><button type="button" class="btn btn-outline-secondary btn-sm" title="Ver Correspondencia"><i class="fas fa-sign-out-alt"></i></button></a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                            {{ $correspondencias->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>



{{-- AGREGAR FILTRO --}}
<div class="modal  bs-example-modal-xl" id="modal-filtro" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-cotizacion">Agregar Filtros</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{route('correspondencia_index', $tercero->id)}}" id="form-create-tercero" method="GET" onsubmit="cargar_btn_form(this)">
                    @csrf
                    <h5 class="modal-title" id="modal-title-cotizacion">Filtros</h5>
                    <div class="container">
                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-4">
                                    <label class="col-sm-12 col-form-label">Ordenar Por</label>
                                    <select name="ordenarpor" class="form-control">
                                        <option value="">Selecciona </option>
                                        <option value="users.name">Usuario</option>
                                        <option value="correspondencia.asunto">Asunto</option>
                                        <option value="correspondencia.numero_folios">Nº folios</option>
                                        <option value="correspondencia.created_at">Fecha</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-12 col-form-label">Tipo Radicacion</label>
                                    <select name="tipo_radicacion" class="form-control">
                                        <option value="">Selecciona</option>
                                        @foreach (\App\Models\Tipo_radicacion_correspondencia::all() as $tipo)
                                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-12 col-form-label">Dependencia</label>
                                    <select name="dependencia" class="form-control">
                                        <option value="">Selecciona </option>
                                        @foreach (\App\Models\Dependencia_correspondencia::all() as $dependencia)
                                            <option value="{{ $dependencia->id }}">{{ $dependencia->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>
                        </div>

                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">
                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Origen</label>
                                    <select name="origen" class="form-control">
                                        <option value="">Selecciona </option>
                                        @foreach (\App\Models\Origen_correspondencia::all() as $origen)
                                            <option value="{{ $origen->id }}">{{ $origen->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Fecha</label>
                                    <input type="text" class="form-control datepicker-here" name="fecha" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="modal-title" id="modal-title-cotizacion">Buscar</h5>
                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">

                                {{-- <div class="col-sm-6">
                                    <select name="buscapor" class="form-control">
                                        <option value="identificacion">Identificacion</option>
                                        <option value="nombre">Nombre</option>
                                        <option value="correo">Correo</option>
                                        <option value="telefono">Telefono</option>
                                    </select>
                                </div> --}}
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" placeholder="Buscar" name="search"/>
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


{{-- MODAL VER DOCUMENTO --}}
<div class="modal fade bs-example-modal-xl" id="modal_ver_documento" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal_ver_documento_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_ver_documento_content">

            </div>
        </div>
    </div>
</div>


{{-- AGREGAR CORRESPONDENCIA Y EDITAR--}}
<div class="modal fade bs-example-modal-xl" id="modal_add_correspondencia" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-correspondencia">Agregar Correspondencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/terceros/correspondencia/create" id="form-create-correspondencia" method="POST" onsubmit="cargar_btn_form(this)" enctype="multipart/form-data">
                    @csrf

                    <div class="container">
                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-4">
                                    <label class="col-sm-12 col-form-label">Tipo Radicación</label>
                                    <select name="tipo_radicacion_id" id="tipo_radicacion_id" class="form-control" required>
                                        <option value="">Seleccione tipo</option>
                                        @foreach (\App\Models\Tipo_radicacion_correspondencia::all() as $tipo)
                                            <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label class="col-sm-12 col-form-label">Dependencia</label>
                                    <select name="dependencia_id" class="form-control" id="dependencia_id" required>
                                        <option value="">Seleccione</option>
                                        @foreach (\App\Models\Dependencia_correspondencia::all() as $tipo)
                                            <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-12 col-form-label">Asunto</label>
                                    <input class="form-control" type="text" id="asunto_correspondencia" name="asunto" placeholder="Escriba el asunto" required>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Usuario</label>
                                    <select name="users_id" class="form-control" id="users_id" required>
                                        <option value="">Seleccione</option>
                                        @foreach (\App\User::all() as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Nº de folios</label>
                                    <input class="form-control" type="number" id="numero_folio" name="numero_folios" placeholder="Escriba la Nº Folios" required>
                                </div>
                                
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">
                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Origen</label>
                                    <select name="origen_id" class="form-control" id="origen_id" required>
                                        <option value="">Seleccione</option>
                                        @foreach (\App\Models\Origen_correspondencia::all() as $tipo)
                                            <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    <label for="adjunto_file">Agregar Adjunto</label>
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="file" id="adjunto" class="form-control" name="adjunto" id="adjunto_file">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <input type="hidden" name="id" id="correspondencia_id" />
                    <input type="hidden" name="tercero_id" id="tercero_id" value="{{$tercero->id}}" />

                    <div class="mt-5 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit" id="btn_enviar_correspondencia">Agregar Correspondencia</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>



@endsection