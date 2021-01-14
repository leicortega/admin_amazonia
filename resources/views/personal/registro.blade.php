@section('title') Registro Personal @endsection

@section('jsMain')
    <script src="{{ asset('assets/js/personal.js') }}"></script>
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

                                @if (session()->has('creado') && session('creado') == 1)
                                    <div class="alert alert-success">
                                        Personal creado correctamente.
                                    </div>
                                @endif

                                @if (session()->has('creado') && session('creado') == 0)
                                    <div class="alert alert-error">
                                        Ocurrio un error, vuelva a intentarlo.
                                    </div>
                                @endif
                                
                                {{-- botones de filtro --}}
                                <button type="button" class="btn btn-primary btn-lg float-left mb-2" data-toggle="modal" data-target="#modal-filtro">Filtrar <i class="fa fa-filter" aria-hidden="true"></i>
                                </button>


                                @if(request()->routeIs('personal_filtro'))
                                    <a href="{{route('personal')}}" class="btn btn-primary btn-lg mb-2 float-left ml-1">
                                        Limpiar <i class="fa fa-eraser" aria-hidden="true"></i>
                                    </a>
                                @endif
                                {{-- end botones de fitro --}}

                                <button type="button" class="btn btn-primary btn-lg float-right mb-2" data-toggle="modal" data-target="#aggPersonal">Agregar +</button>

                                <table class="table table-centered table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th colspan="12" class="text-center">
                                                <div class="d-inline-block icons-sm mr-2"><i class="uim fas fa-user-friends mx-1"></i> </i></div>
                                                <span class="header-title mt-2">Personal</span>
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
                                            <th scope="col">Fecha Ingreso</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Correo</th>
                                            <th scope="col">Telefonos</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($personal as $item)
                                            <tr>
                                                <th scope="row">
                                                    <a href="#">{{ $item->identificacion }}</a>
                                                </th>
                                                <td>{{ $item->fecha_ingreso }}</td>
                                                <td>{{ $item->nombres .' '. $item->primer_apellido }}</td>
                                                <td>{{ $item->correo }}</td>
                                                <td>{{ $item->telefonos }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showPersonal({{ $item->id }})" data-toggle="tooltip" data-placement="top" title="Editar Persona">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </button>
                                                    <a href="/personal/ver/{{ $item->id }}"><button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Persona">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button></a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                            {{ $personal->links() }}

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

{{-- AGREGAR PERSONAL --}}
<div class="modal fade bs-example-modal-xl" id="aggPersonal" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-correo">Agregar Personal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/personal/create" method="POST">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_identificacion" class="form-control" id="tipo_identificacion" required>
                                        <option value=""></option>
                                        <option value="Cedula de ciudadania">Cedula de ciudadania</option>
                                        <option value="Cedula de Extranjeria">Cedula de Extranjeria</option>
                                        <option value="Nit">Nit</option>
                                        <option value="Registro Civil">Registro Civil</option>
                                    </select>
                                    <label for="tipo_identificacion">Tipo de identificacion</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" id="identificacion_" name="identificacion" required="">
                                    <label for="identificacion_">Numero de identificacion</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="nombres" name="nombres" required="">
                                    <label for="nombres">Nombre</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" required="">
                                    <label for="primer_apellido">Primer Apellido</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido">
                                    <label for="segundo_apellido">Segundo Apellido</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" data-language="es" data-date-format="yyyy-mm-dd" id="fecha_ingreso" name="fecha_ingreso" required="">
                                    <label for="fecha_ingreso">Fecha de ingreso</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control" id="direccion" name="direccion" required="">
                                        <label for="direccion">Direccion</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <h5 class="font-size-14">Sexo</h5>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio1" name="sexo" class="custom-control-input" value="Hombre">
                                    <label class="custom-control-label" for="custominlineRadio1">Hombre</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio2" name="sexo" class="custom-control-input" value="Mujer">
                                    <label class="custom-control-label" for="custominlineRadio2">Mujer</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio3" name="sexo" class="custom-control-input" value="Otro">
                                    <label class="custom-control-label" for="custominlineRadio3">Otro</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <h5 class="font-size-14">Estado</h5>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio4" name="estado" class="custom-control-input" value="Activo" checked>
                                    <label class="custom-control-label" for="custominlineRadio4">Activo</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio5" name="estado" class="custom-control-input" value="Inactivo">
                                    <label class="custom-control-label" for="custominlineRadio5">Inactivo</label>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="rh" name="rh" required="">
                                    <label for="rh">RH</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <div class="form-group form-group-custom mb-4">
                                        <select name="tipo_vinculacion" class="form-control" id="tipo_vinculacion" required>
                                            <option value=""></option>
                                            <option value="AMAZONIA C&L">AMAZONIA C&L</option>
                                            <option value="EXTERNO">EXTERNO</option>
                                        </select>
                                        <label for="tipo_vinculacion">Tipo Vinculacion</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="correo" name="correo" required="">
                                    <label for="correo">Correo</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="telefonos" name="telefonos" required="">
                                    <label for="telefonos">Telefonos</label>
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

{{-- MOSTRAR PERSONAL --}}
<div class="modal fade bs-example-modal-xl" id="verPersonal" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-personal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/personal/create" method="POST">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-4">
                                <label for="tipo_identificacion_update">Tipo de identificacion</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_identificacion_update" class="form-control" id="tipo_identificacion_update" disabled required>
                                        <option value=""></option>
                                        <option value="Cedula de ciudadania">Cedula de ciudadania</option>
                                        <option value="Cedula de Extranjeria">Cedula de Extranjeria</option>
                                        <option value="Nit">Nit</option>
                                        <option value="Registro Civil">Registro Civil</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="identificacion_update">Numero de identificacion</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" id="identificacion_update" name="identificacion_update" readonly required="">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="nombres_update">Nombre</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="nombres_update" name="nombres_update" readonly required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <label for="primer_apellido_update">Primer Apellido</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="primer_apellido_update" name="primer_apellido_update" readonly required="">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="segundo_apellido_update">Segundo Apellido</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="segundo_apellido_update" name="segundo_apellido_update" readonly required="">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="fecha_ingreso_update">Fecha de ingreso</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" data-language="es" data-date-format="yyyy-mm-dd" disabled id="fecha_ingreso_update" name="fecha_ingreso_update" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <label for="direccion_update">Direccion</label>
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control" id="direccion_update" name="direccion_update" readonly required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <h5 class="font-size-14">Sexo</h5>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio6" name="sexo_update" class="custom-control-input" disabled value="Hombre">
                                    <label class="custom-control-label" for="custominlineRadio6">Hombre</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio7" name="sexo_update" class="custom-control-input" disabled value="Mujer">
                                    <label class="custom-control-label" for="custominlineRadio7">Mujer</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio8" name="sexo_update" class="custom-control-input" disabled value="Otro">
                                    <label class="custom-control-label" for="custominlineRadio8">Otro</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <h5 class="font-size-14">Estado</h5>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio9" name="estado_update" class="custom-control-input" disabled value="Activo">
                                    <label class="custom-control-label" for="custominlineRadio9">Activo</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio10" name="estado_update" class="custom-control-input" disabled value="Inactivo">
                                    <label class="custom-control-label" for="custominlineRadio10">Inactivo</label>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <label for="rh_update">RH</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="rh_update" name="rh_update" readonly required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <label for="tipo_vinculacion_update">Tipo Vinculacion</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_vinculacion_update" class="form-control" id="tipo_vinculacion_update" disabled required>
                                        <option value=""></option>
                                        <option value="AMAZONIA C&L">AMAZONIA C&L</option>
                                        <option value="EXTERNO">EXTERNO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="correo_update">Correo</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="correo_update" name="correo_update" readonly required="">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="telefonos_update">Telefonos</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="telefonos_update" name="telefonos_update" readonly required="">
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn-submit-correo" type="submit">Enviar</button>
                    </div>  --}}

                </form>

                <hr>

                <div class="card-body">
                    <h4 class="header-title">Cargos</h4>

                    <form action="/personal/agg_cargo_personal" id="form_cargos_personal" method="POST">
                        @csrf

                        <div class="form-row align-items-center">
                            <div class="col-auto">
                                <div class="mt-3 mr-sm-2">
                                    <label class="sr-only" for="inlineFormInput">Name</label>
                                    <select name="cargos_id" class="form-control" id="cargos_id" required>
                                        <option value="">Seleccione un cargo</option>
                                        @foreach (\App\Models\Sistema\Cargo::all() as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" value="" name="personal_id" id="personal_id">
                            <div class="col-auto mt-3 mr-sm-2">
                                <button type="submit" class="btn btn-primary"> Agregar cargo</button>
                            </div>
                        </div>
                    </form>

                    <div id="cargos_personal_content">

                    </div>

                </div>



            </div>
        </div>
    </div>
</div>

{{-- AGREGAR FILTRO --}}
<div class="modal fade bs-example-modal-xl" id="modal-filtro" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-personal">Agregar Filtros</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{route('personal_filtro')}}" id="form-create-tercero" method="GET">
                    @csrf
                    <div class="container">
                        <div class="form-group row">                            
                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Ordenar Por</label>
                                    <select name="ordenarpor" class="form-control">
                                        <option value="">Selecciona </option>
                                        <option value="identificacion">Identificacion</option>
                                        <option value="nombres">Nombre</option>
                                        <option value="fecha_ingreso">Fecha Ingreso</option>
                                        <option value="correo">Correo</option>
                                        <option value="telefonos">Telefono</option>
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group mb-4">
                                        <label class="col-form-label">Rango de fechas</label>
                                        <input type="text" class="form-control datepicker-here" name="fecha_range" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" data-range="true" data-multiple-dates-separator=" - ">
                                    </div>
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
                                <div class="col-sm-12">
                                    <div class="form-group mb-4">
                                        <label class="col-form-label">Buscar</label>
                                        <input type="text" class="form-control" placeholder="Buscar" name="search"/>
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







