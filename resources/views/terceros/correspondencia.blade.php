@section('title') Registro Terceros @endsection

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

                                @if (session()->has('tercero') && session('tercero') == 1)
                                    <div class="alert alert-success">
                                        Tercero agregado correctamente.
                                    </div>
                                @endif

                                @if (session()->has('tercero') && session('tercero') == 0)
                                    <div class="alert alert-error">
                                        Ocurrio un error, vuelva a intentarlo.
                                    </div>
                                @endif

                                <a href="/terceros/ver/{{}}"><button onclick="cargar_btn_single(this)" type="button" class="mr-2 btn btn-dark btn-lg mb-2 float-left">Atras</button></a>

                                {{-- botones de filtro --}}

                                <button type="button" class="btn btn-primary btn-lg float-left mb-2" onclick="cargarDepartamentos()" data-toggle="modal" data-target="#modal-filtro">Filtrar <i class="fa fa-filter" aria-hidden="true"></i>
                                </button>

                                @if(request()->routeIs('terceros_filtro'))
                                    <a href="{{route('terceros')}}" class="btn btn-primary btn-lg mb-2 float-left ml-1" onclick="cargar_btn_single(this)">
                                        Limpiar <i class="fa fa-eraser" aria-hidden="true"></i>
                                    </a>
                                @endif


                                {{-- end botones de fitro --}}

                                <button type="button" class="btn btn-primary btn-lg float-right mb-2" onclick="cargarDepartamentos()" data-toggle="modal" data-target="#modal-crear-tercero">Agregar +</button>

                                <table class="table table-centered table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th colspan="12" class="text-center">
                                                <div class="d-inline-block icons-sm mr-2"><i class="uim fas fa-user-friends mx-1"></i> </i></div>
                                                <span class="header-title mt-2">Terceros</span>
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
                                            <th scope="col">Identificacion</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Ciudad</th>
                                            <th scope="col">Correo</th>
                                            <th scope="col">Telefonos</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($terceros as $item)
                                            <tr>
                                                <th scope="row">
                                                    <a href="#">{{ $item->identificacion }}</a>
                                                </th>
                                                <td>{{ $item->nombre }}</td>
                                                <td>{{ $item->municipio }}</td>
                                                <td>{{ $item->correo }}</td>
                                                <td>{{ $item->telefono }}</td>
                                                <td class="text-center">
                                                <a href="/terceros/ver/{{ $item->id }}"><button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Tercero" onclick="cargar_btn_single(this)">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button></a>
                                                </td>
                                            </tr>
                                        @endforeach --}}

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

{{-- AGREGAR TERCERO MODAL --}}
<div class="modal fade bs-example-modal-xl" id="modal-crear-tercero" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-cotizacion">Agregar Tercero</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/terceros/create" id="form-create-tercero" method="POST" onsubmit="cargar_btn_form(this)">
                    @csrf

                    <div class="container">
                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Tipo Identificacion</label>
                                    <select name="tipo_identificacion" class="form-control" required>
                                        <option value="">Seleccione tipo</option>
                                        <option value="Cedula de Ciudadania">Cedula de Ciudadania</option>
                                        <option value="Cedula de Extrangeria">Cedula de Extrangeria</option>
                                        <option value="Nit">Nit</option>
                                        <option value="Registro Civil">Registro Civil</option>
                                        <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Numero Identificación</label>
                                    <input class="form-control" type="number" name="identificacion" placeholder="Escriba la identificación" required="">
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Nombre Completo</label>
                                    <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Escriba el nombre" required="">
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Tipo Tercero</label>
                                    <select name="tipo_tercero" class="form-control" required>
                                        <option value="">Seleccione tipo</option>
                                        <option value="Cliente">Cliente</option>
                                        <option value="Convenio">Convenio</option>
                                        <option value="Colegio o Institución Educativa">Colegio o Institución Educativa</option>
                                        <option value="Aseguradora">Aseguradora</option>
                                        <option value="Ente Territorial">Ente Territorial</option>
                                        <option value="CDA (Centro de Diagnóstico Automotor)">CDA (Centro de Diagnóstico Automotor)</option>
                                        <option value="Documentación Interna">Documentación Interna</option>
                                        <option value="Proveedores">Proveedores</option>
                                        <option value="Rastreo Satelital GPS">Rastreo Satelital GPS</option>
                                        <option value="SEGUIMIENTO">SEGUIMIENTO</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Régimen</label>
                                    <select name="regimen" class="form-control" required>
                                        <option value="">Seleccione régimen</option>
                                        <option value="Comun">Comun</option>
                                        <option value="Simplificado">Simplificado</option>
                                        <option value="Natural">Natural</option>
                                        <option value="Gran Contibuyente">Registro Civil</option>
                                        <option value="Persona Juridica">Persona Juridica</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Departamento</label>
                                    <select name="departamento" id="departamento" onchange="cargarMunicipios(this.value)" class="form-control" required>
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Municipio</label>
                                    <select name="municipio" id="municipio" class="form-control" required>
                                        <option value="">Seleccione</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Dirección</label>
                                    <input class="form-control" type="text" name="direccion" placeholder="Escriba la Dirección" required="">
                                </div>

                            </div>
                        </div>

                        <hr>

                        <div class="form-group row mb-3">
                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Correo</label>
                                    <input class="form-control" type="text" name="correo" id="correo" placeholder="Escriba la Dirección" required="">
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Telefono</label>
                                    <input class="form-control" type="text" name="telefono" id="telefono" placeholder="Escriba la Dirección" required="">
                                </div>

                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="cotizacion_id" id="cotizacion_id" />

                    <div class="mt-5 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
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

                <form action="{{route('terceros_filtro')}}" id="form-create-tercero" method="GET" onsubmit="cargar_btn_form(this)">
                    @csrf
                    <h5 class="modal-title" id="modal-title-cotizacion">Filtros</h5>
                    <div class="container">
                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Ordenar Por</label>
                                    <select name="ordenarpor" class="form-control">
                                        <option value="">Selecciona </option>
                                        <option value="identificacion">Identificacion</option>
                                        <option value="nombre">Nombre</option>
                                        <option value="municipio">Ciudad</option>
                                        <option value="correo">Correo</option>
                                        <option value="telefono">Telefono</option>
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Departamento</label>
                                    <select name="departamento" id="departamento_2" onchange="cargarMunicipios(this.value)" class="form-control">
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Municipio</label>
                                    <select name="municipio" id="municipio_2" class="form-control">
                                        <option value="">Selecciona</option>
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Tipo Tercero</label>
                                    <select name="tipo_tercero" class="form-control">
                                        <option value="">Seleccione tipo</option>
                                        <option value="Cliente">Cliente</option>
                                        <option value="Convenio">Convenio</option>
                                        <option value="Colegio o Institución Educativa">Colegio o Institución Educativa</option>
                                        <option value="Aseguradora">Aseguradora</option>
                                        <option value="Ente Territorial">Ente Territorial</option>
                                        <option value="CDA (Centro de Diagnóstico Automotor)">CDA (Centro de Diagnóstico Automotor)</option>
                                        <option value="Documentación Interna">Documentación Interna</option>
                                        <option value="Proveedores">Proveedores</option>
                                        <option value="Rastreo Satelital GPS">Rastreo Satelital GPS</option>
                                        <option value="SEGUIMIENTO">SEGUIMIENTO</option>
                                    </select>
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





@endsection