@section('title') Tercero  @endsection

@section('jsMain')
    <script src="{{ asset('assets/js/terceros.js') }}"></script>
    <script> cargarDepartamentos(); </script>
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

                                <a href="/terceros"><button type="button" class="btn btn-dark btn-lg mb-2">Atras</button></a>
                                <button type="button" class="btn btn-primary btn-lg mb-2 float-right" onclick="editar_tercero({{ $tercero[0]->id }})">Editar</button>

                                @if (session()->has('update') && session('update') == 1)
                                    <div class="alert alert-success">
                                        Tercero actualizado correctamente
                                    </div>
                                @endif

                                @if (session()->has('response') && session('response') == 1)
                                    <div class="alert alert-success">
                                        {{ session('mensaje') }}
                                    </div>
                                @endif

                                @if (session()->has('response') && session('response') == 0)
                                    <div class="alert alert-danger">
                                        {{ session('mensaje') }}
                                    </div>
                                @endif

                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center table-bg-dark">
                                            <th colspan="6"><b>{{ $tercero[0]->nombre }}</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="table-bg-dark"><b>Nombre</b></td>
                                            <td>{{ $tercero[0]->nombre }}</td>
                                            <td class="table-bg-dark"><b>Tipo identificacion</b></td>
                                            <td>{{ $tercero[0]->tipo_identificacion }}</td>
                                            <td class="table-bg-dark"><b>No Identificacion</b></td>
                                            <td>{{ $tercero[0]->identificacion }}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Regimen</b></td>
                                            <td>{{ $tercero[0]->regimen }}</td>
                                            <td class="table-bg-dark"><b>Departamento</b></td>
                                            <td>{{ $tercero[0]->departamento }}</td>
                                            <td class="table-bg-dark"><b>Municipio</b></td>
                                            <td>{{ $tercero[0]->municipio }}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Correo</b></td>
                                            <td>{{ $tercero[0]->correo }}</td>
                                            <td class="table-bg-dark"><b>Telefono</b></td>
                                            <td>{{ $tercero[0]->telefono }}</td>
                                            <td class="table-bg-dark"><b>Direccion</b></td>
                                            <td>{{ $tercero[0]->direccion }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="min-width: 411px !important;">
                                                <form class="form-inline justify-content-center" method="POST" action="/terceros/agg_perfil_tercero">
                                                    @csrf

                                                    <select name="nombre" class="form-control mr-2" required>
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

                                                    <input type="hidden" name="terceros_id" value="{{ $tercero[0]->id }}">

                                                    <button type="submit" class="btn btn-primary mt-3 mt-sm-0">Enviar</button>
                                                </form>
                                            </td>
                                            <td colspan="4" class="conatiner">
                                                <div class="row px-3">

                                                    @foreach ($tercero[0]->perfiles_terceros as $item)
                                                        <div class="input-group col-6 mb-2">
                                                            <input type="text" class="form-control" name="tipo_tercero" value="{{ $item->nombre }}" readonly>
                                                            <div class="input-group-prepend">
                                                                <a href="/terceros/delete_perfil_tercero/{{ $item->id }}"><button class="btn btn-danger">X</button></a>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                </div>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div id="accordion" class="col-12">
                                {{-- TAB DOCUMENTOS ADJUNTOS --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos({{ $tercero[0]->id }})" data-parent="#accordion" href="#collapseDocumentosAdjuntos" aria-expanded="false" aria-controls="collapseDocumentosAdjuntos" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">DOCUMENTOS ADJUNTOS</h5>
                                        </div>
                                    </a>

                                    <div id="collapseDocumentosAdjuntos" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" data-toggle="modal" data-target="#agg_documento_modal"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Tipo Documento</th>
                                                        <th class="text-center table-bg-dark">Descripcion</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_documentos_adjuntos">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB CONTACTOS --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_contactos({{ $tercero[0]->id }})" data-parent="#accordion" href="#collapseContactos" aria-expanded="false" aria-controls="collapseContactos" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">CONTACTOS</h5>
                                        </div>
                                    </a>

                                    <div id="collapseContactos" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">
                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" data-toggle="modal" data-target="#agg_contacto"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">Identificacion</th>
                                                        <th class="text-center table-bg-dark">Nombre(s) y Apellido(s)</th>
                                                        <th class="text-center table-bg-dark">Telefono</th>
                                                        <th class="text-center table-bg-dark">Direccion</th>
                                                        {{-- <th class="text-center table-bg-dark">Estado</th> --}}
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_contactos">
                                                    <tr>
                                                        <td colspan="6" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB COTIZACIONES --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_cotizaciones({{ $tercero[0]->identificacion }})" data-parent="#accordion" href="#collapseCotizaciones" aria-expanded="false" aria-controls="collapseCotizaciones" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingTwo">
                                            <h5 class="m-0 font-size-14 text-white">COTIZACIONES</h5>
                                        </div>
                                    </a>

                                    <div id="collapseCotizaciones" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                        <div class="card-body">
                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" data-toggle="modal" data-target="#modal_crear_cotizacion"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Fecha</th>
                                                        <th scope="col">Servicio</th>
                                                        <th scope="col">Tipo Vehiculo</th>
                                                        <th scope="col">Trayecto</th>
                                                        <th scope="col">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_cotizaciones">
                                                    <tr>
                                                        <td colspan="6" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB CONTRATOS --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_contratos({{ $tercero[0]->identificacion }})" data-parent="#accordion" href="#collapseContratos" aria-expanded="false" aria-controls="collapseContratos" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingTwo">
                                            <h5 class="m-0 font-size-14 text-white">CONTRATOS</h5>
                                        </div>
                                    </a>

                                    <div id="collapseContratos" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                        <div class="card-body">
                                            {{-- <button class="btn btn-info waves-effect waves-light mb-2 float-right" data-toggle="modal" data-target="#modal_crear_cotizacion"><i class="fas fa-plus"></i></button> --}}

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Fecha</th>
                                                        <th scope="col">Responable</th>
                                                        <th scope="col">Tipo</th>
                                                        <th scope="col">Objeto</th>
                                                        <th scope="col">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_contratos">
                                                    <tr>
                                                        <td colspan="6" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

{{-- AGREGAR AGREGAR CONTACTO --}}
<div class="modal fade bs-example-modal-xl" id="agg_contacto" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="agg_contacto_title">Agregar contacto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/terceros/agg_contacto" id="form_agg_contacto" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="nombre_contacto">Nombre</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" placeholder="Escriba el nombre" id="nombre_contacto" name="nombre_contacto" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="identificacion_contacto">Identificacion</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" placeholder="Escriba la identificacion" name="identificacion_contacto"  id="identificacion_contacto" required="">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label for="telefono_contacto">Telefono</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" placeholder="Escriba el telefono" name="telefono_contacto"  id="telefono_contacto" required="">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label for="direccion_contacto">Direccion</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" placeholder="Escriba el direccion" name="direccion_contacto"  id="direccion_contacto" required="">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="terceros_id" value="{{ $tercero[0]->id }}">

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- AGREGAR AGREGAR DOCUMENTO --}}
<div class="modal fade bs-example-modal-xl" id="agg_documento_modal" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="agg_documento_title">Agregar documento a {{ $tercero[0]->nombre }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/terceros/agg_documento" id="agg_documento" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="tipo">Tipo adjunto</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo" id="tipo" class="form-control" required>
                                        <option value="">Seleccione el tipo de adjunto</option>
                                        <option value="RUT">RUT</option>
                                        <option value="Documento de identidad">Documento de identidad</option>
                                        <option value="DOCUMENTACIÓN LEGAL">DOCUMENTACIÓN LEGAL</option>
                                        <option value="HABILITACIÓN Y CAPACIDAD TRANSPORTADORA">HABILITACIÓN Y CAPACIDAD TRANSPORTADORA </option>
                                        <option value="ACTA DE INICIO">ACTA DE INICIO </option>
                                        <option value="CONTRATO DE PRESTACIÓN DE SERVICIO">CONTRATO DE PRESTACIÓN DE SERVICIO </option>
                                        <option value="OFICIOS Y CORRESPONDENCIA">OFICIOS Y CORRESPONDENCIA </option>
                                        <option value="INFORMACIÓN FINANCIERA">INFORMACIÓN FINANCIERA</option>
                                        <option value="Camara de Comercio">Camara de Comercio</option>
                                        <option value="REGISTRO ÚNICO DE PROPONENTES">REGISTRO ÚNICO DE PROPONENTES </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="descripcion">Descripcion</label>
                                <textarea name="descripcion_documento" id="descripcion_documento" class="form-control form-group-custom mb-4" rows="7" placeholder="Escriba la descripcion" required></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="adjunto_file">Agregar Adjunto</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="file" class="form-control" name="adjunto_file" id="adjunto_file" required>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="terceros_id" value="{{ $tercero[0]->id }}">
                        <input type="hidden" name="id" id="id">

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
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

{{-- MODAL CREAR COTIZACION --}}
<div class="modal fade bs-example-modal-xl" id="modal_crear_cotizacion" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal_crear_cotizacion_title">Crear Cotización</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/terceros/crear_cotizacion" id="form_crear_cotizacion" method="POST">
                    @csrf

                    <div id="modal-content-cotizacion">
                        <div class="card-body" id="form_part_one">

                            <div class="form-group row">
                                <div class="col-sm-6 d-flex">
                                    <div class="form-group row">
                                        <h5 class="col-sm-12 col-form-label">Fechas<hr class="m-0"></h5>

                                        <div class="col-sm-6">
                                            <label class="col-sm-12 col-form-label">Fecha Inicio</label>
                                            <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" type="text" name="fecha_ida" id="fecha_ida" placeholder="yyyy-mm-dd" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-12 col-form-label">Fecha Final</label>
                                            <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" type="text" name="fecha_regreso" id="fecha_regreso" placeholder="yyyy-mm-dd" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 d-flex">
                                    <div class="form-group row">
                                        <h5 class="col-sm-12 col-form-label">Servicio<hr class="m-0"></h5>

                                        <div class="col-sm-6">
                                            <label class="col-sm-12 col-form-label">Tipo Servicio</label>
                                            <select class="form-control" name="tipo_servicio" id="tipo_servicio">
                                                <option value="">Seleccione</option>
                                                <option value="Carga y/o Encomiendas">Carga y/o Encomiendas</option>
                                                <option value="Empresarial">Empresarial</option>
                                                <option value="Escolar">Escolar</option>
                                                <option value="Turismo">Turismo</option>
                                                <option value="Ocasional">Ocasional</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-12 col-form-label">Tipo Vehiculo</label>
                                            <select name="tipo_vehiculo" id="tipo_vehiculo" class="form-control" >
												<option value="">Seleccione</option>
												<option value="Station wagon">Station wagon</option>
												<option value="Buseta">Buseta</option>
												<option value="Bus">Bus</option>
												<option value="Campero">Campero</option>
												<option value="Micro Bus">Micro Bus</option>
												<option value="Volqueta">Volqueta</option>
												<option value="Camioneta Cerrada">Camioneta Cerrada</option>
												<option value="Camioneta Doble Cabina 4*4">Camioneta Doble Cabina 4*4</option>
												<option value="Camion">Camion</option>
												<option value="Camioneta de Estacas">Camioneta de Estacas</option>
												<option value="Vans">Vans</option>
												<option value="Camioneta VAN">Camioneta VAN</option>
											</select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 d-flex">
                                    <div class="form-group row">
                                        <h5 class="col-sm-12 col-form-label">Origen<hr class="m-0"></h5>

                                        <div class="col-sm-6">
                                            <label class="col-form-label">Departamento</label>
                                            <select name="departamento_origen" id="departamento_origen" class="departamento_origen form-control" onchange="dptOrigen(this.value)" >
                                                <option value="">Seleccione el departamento</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-form-label">Municipio</label>
                                            <select name="ciudad_origen" id="ciudad_origen" class="ciudad_origen form-control" >
                                                <option value="">Seleccione el municipio</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 d-flex">
                                    <div class="form-group row">
                                        <h5 class="col-sm-12 col-form-label">Destino<hr class="m-0"></h5>

                                        <div class="col-sm-6">
                                            <label class="col-form-label">Departamento</label>
                                            <select name="departamento_destino" id="departamento_destino"class="departamento_destino form-control" onchange="dptDestino(this.value)" >
                                                <option value="">Seleccione el departamento</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-form-label">Municipio</label>
                                            <select name="ciudad_destino" id="ciudad_destino" class="ciudad_destino form-control" >
                                                <option value="">Seleccione el municipio</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 d-flex">
                                    <div class="form-group row col-sm-12">
                                        <h5 class="col-form-label col-sm-12">Descripción del Trayecto<hr class="m-0"></h5>

                                        <div class="col-sm-12">
                                            <textarea class="form-control" type="text" name="descripcion" id="descripcion" placeholder="Describa los municipios intermedios entre el origen y el destino." ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 d-flex">
                                    <div class="form-group row col-sm-12">
                                        <h5 class="col-form-label col-sm-12">Observaciones del Trayecto<hr class="m-0"></h5>

                                        <div class="col-sm-12">
                                            <textarea class="form-control" type="text" name="observaciones" id="observaciones" placeholder="Ejemplo 'El recorrido inicia en la calle 0 No 0-00 a las 05:00AM.' " ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-5 d-flex">
                                    <div class="form-group row">
                                        <h5 class="col-sm-12 col-form-label">Incluye<hr class="m-0"></h5>

                                        <div class="col-sm-4">
                                            <label class="col-sm-12 col-form-label">Conbustible</label>

                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="combustible" name="combustible" value="Si" class="custom-control-input">
                                                <label class="custom-control-label" for="combustible">Si</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="combustible2" name="combustible" value="No" class="custom-control-input" checked="">
                                                <label class="custom-control-label" for="combustible2">No</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-sm-12 col-form-label">Conductor</label>

                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="conductor" name="conductor" value="Si" class="custom-control-input">
                                                <label class="custom-control-label" for="conductor">Si</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="conductor2" name="conductor" value="No" class="custom-control-input" checked="">
                                                <label class="custom-control-label" for="conductor2">No</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-sm-12 col-form-label">Peajes</label>

                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="peajes" name="peajes" value="Si" class="custom-control-input">
                                                <label class="custom-control-label" for="peajes">Si</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="peajes2" name="peajes" value="No" class="custom-control-input" checked="">
                                                <label class="custom-control-label" for="peajes2">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4 d-flex">
                                    <div class="form-group row">
                                        <h5 class="col-sm-12 col-form-label">Cotización por<hr class="m-0"></h5>

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="cotizacion_por" name="cotizacion_por" value="Dias" class="custom-control-input" checked=""/>
                                            <label class="custom-control-label" for="cotizacion_por">Dia(s)</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="cotizacion_por2" name="cotizacion_por" value="Trayecto" class="custom-control-input" />
                                            <label class="custom-control-label" for="cotizacion_por2">Trayecto(s)</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="cotizacion_por3" name="cotizacion_por" value="Mensual" class="custom-control-input" />
                                            <label class="custom-control-label" for="cotizacion_por3">Mensual</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 d-flex">
                                    <div class="form-group row">
                                        <h5 class="col-sm-12 col-form-label">Trayecto(s)<hr class="m-0"></h5>

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="recorrido" name="recorrido" value="Solo ida" class="custom-control-input" checked=""/>
                                            <label class="custom-control-label" for="recorrido">Solo ida</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="recorrido2" name="recorrido" value="Ida y vuelta" class="custom-control-input" />
                                            <label class="custom-control-label" for="recorrido2">Ida y vuelta</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 d-flex">
                                    <div class="form-group row col-sm-12">
                                        <h5 class="col-form-label col-sm-12">Costos<hr class="m-0"></h5>

                                        <div class="col-sm-4">
                                            <label class="col-form-label">Valor Unitario</label>
                                            <input class="form-control" type="number" name="valor_unitario" id="valor_unitario" onchange="total_cotizacion()" placeholder="Escriba el valor unitario" />
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-form-label">Cantidad</label>
                                            <input class="form-control" type="number" name="cantidad" id="cantidad" onchange="total_cotizacion()" placeholder="Escriba la cantidad" />
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-form-label">Total</label>
                                            <input class="form-control" type="number" name="total" id="total" value="0" disabled/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 d-flex">
                                    <div class="form-group row col-sm-12">
                                        <h5 class="col-form-label col-sm-12">Trayecto 2<hr class="m-0"></h5>

                                        <div class="col-sm-12">
                                            <textarea class="form-control" type="number" name="trayecto_dos" id="trayecto_dos"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="terceros_id" id="terceros_id" value="{{ $tercero[0]->id }}">
                            <input type="hidden" name="cotizacion_creada" id="cotizacion_creada" />

                        </div>

                        <div class="card-body d-none" id="form_part_two">
                            <textarea name="cotizacion_parte_uno" id="cotizacion_parte_uno" class="form-control" rows="18">
Neiva, {{ \Carbon\Carbon::now('America/Bogota')->format('d/m/Y') }}


Señores:
{{ $tercero[0]->nombre }}
{{ $tercero[0]->tipo_identificacion == 'Cedula de Ciudadania' ? 'CC. '.$tercero[0]->identificacion : 'NIT. '.$tercero[0]->identificacion }}
Dirección: {{ $tercero[0]->direccion ?? 'N/A' }}
E-mail: {{ $tercero[0]->correo ?? 'N/A' }}
Telefono(s): {{ $tercero[0]->telefono ?? 'N/A' }}
<?php $date = \Carbon\Carbon::now('America/Bogota'); ?>

COTIZACIÓN No. {{ 'COT'.$date->format('Y').$date->format('m').$date->format('d').$date->format('H').$date->format('i').'-'.$date->format('s') }}

Agradecemos su interés y preferencia, de acuerdo a su solicitud me permito remitir propuesta comercial, tenga en cuenta que los precios aquí expuestos son de uso exclusivo para el servicio al cual cotizo.

Descripción del servicio:
                            </textarea>
                            <table width="100%" border="1" cellspacing="0" cellpadding="0" style="text-align: center;line-height: 16px;" class="table table-bordered mb-0">

                                <tr style="background: #22852d;">
                                    <td style="color: #fff; border: 1px solid #000;font-weight: bold;" colspan="2">Fechas</td>
                                    <td style="color: #fff; border: 1px solid #000;font-weight: bold;" rowspan="2" width="450px">Descripción</td>
                                    <td style="color: #fff; border: 1px solid #000;font-weight: bold;" colspan="3">Costos</td>
                                </tr>
                                <tr style="background: #22852d;">
                                    <td style="color: #fff; border: 1px solid #000;font-weight: bold;">Inicio</td>
                                    <td style="color: #fff; border: 1px solid #000;font-weight: bold;">Final</td>
                                    <td style="color: #fff; border: 1px solid #000;font-weight: bold;">Valor Unit.</td>
                                    <td style="color: #fff; border: 1px solid #000;font-weight: bold;">Cant.</td>
                                    <td style="color: #fff; border: 1px solid #000;font-weight: bold;">Valor total</td>
                                </tr>

                                <tr>
                                    <td id="fecha_ida_preview"></td>
                                    <td id="fecha_regreso_preview"></td>
                                    <td id="descripcion_preview" style="text-align: justify; padding:5px;"></td>
                                    <td id="valor_unitario_preview"></td>
                                    <td id="cantidad_preview"></td>
                                    <td id="total_preview"></td>
                                </tr>

                            </table>
                            <textarea name="cotizacion_parte_dos" id="cotizacion_parte_dos" class="form-control" rows="22">
El servicio se presta según lo pactado, si es CON DISPONIBILIDAD (para hacer varios recorridos) o SIN DISPONIBILIDAD (recoger y dejar en un punto acordado). Inf. En el formato de cotización se especifica. En caso tal que cambie lo pactado, tiene un valor diferente.

Se debe confirmar el servicio con un día de anticipación dependiendo el destino.

La reserva se confirma y se garantiza con la consignación o formato de transferencia del anticipo correspondiente al 100% del valor del servicio.

En caso de cancelar el servicio se cobra el 50% del valor del pagado.

Al remitir consignación daremos por hecho  al servicio y se acoge a las políticas aquí expuestas.





________________________________________________
{{ strtoupper(auth()->user()->name) }}
{{ $personal->cargos_personal[0]->cargos->nombre ?? 'Cargo' }}
{{ $personal->telefonos ?? 'Telefono' }}
{{ $personal->correo ?? 'Correo' }}
                            </textarea>
                        </div>
                        <div class="alert alert-danger d-none text-center mx-3" id="alert_crear_cotizacion" role="alert">
                            <strong>Faltan campos por llenar atras.</strong>
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn_next_cotizacion" onclick="submit_cotizacion()" type="button">Siguiente</button>
                        <button class="btn btn-light btn-lg waves-effect waves-light d-none" id="btn_back_cotizacion" onclick="back_cotizacion()" type="button">Atras</button>
                        <button class="btn btn-primary btn-lg waves-effect waves-light d-none" id="btn_submit_cotizacion" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Crear Cotrato --}}
<div class="modal fade bs-example-modal-xl" id="modal-crear-contrato" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-contrato">Generar Contrato</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/terceros/generar_contrato" id="form_generar_contrato" method="POST">
                    @csrf

                    <div class="container">
                        <div class="form-group row">

                            <h5 class="col-12">RESPONSABLE</h5>

                            <div class="col-sm-12 d-flex">
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Seleccione responsable</label>
                                    <select name="select_responsable" id="select_responsable" onchange="cargar_responsable_contrato(this.value)" class="form-control" required>
                                        <option value="">Seleccione responsable</option>
                                        <option value="Nuevo">Nuevo</option>
                                        @foreach (\App\Models\Contactos_tercero::where('terceros_id', $tercero[0]->id)->get() as $item)
                                            <option value="{{ $item->identificacion }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Numero Identificación</label>
                                    <input class="form-control" type="number" name="identificacion_responsable" id="identificacion_responsable" placeholder="Escriba la identificación" required="">
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Nombre Completo</label>
                                    <input class="form-control" type="text" name="nombre_responsable" id="nombre_responsable" placeholder="Escriba el nombre" required="">
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Dirección</label>
                                    <input class="form-control" type="text" name="direccion_responsable" id="direccion_responsable" placeholder="Escriba la direccion" required="">
                                </div>
                            </div>

                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Telefono</label>
                                    <input class="form-control" type="number" name="telefono_responsable" id="telefono_responsable" placeholder="Escriba el Telefono" required="">
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Tipo Contrato</label>
                                    <select name="tipo_contrato" id="tipo_contrato" class="form-control" required>
                                        <option value="">Seleccione tipo</option>
                                        <option value="ASALARIADO">ASALARIADO</option>
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Objeto del contrato</label>
                                    <textarea name="objeto_contrato" id="objeto_contrato" rows="3" class="form-control" placeholder="Escriba el objeto del contrato" required=""></textarea>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="form-group row">

                            <h5 class="col-12">VEHICULO</h5>

                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Vehiculo</label>
                                    <select name="vehiculo_id" id="vehiculo_id" class="form-control" required>
                                        <option value="">Seleccione vehiculo</option>
                                        @foreach (\App\Models\Vehiculo::all() as $vehiculo)
                                            <option value="{{ $vehiculo->id }}">{{ $vehiculo->placa }} - {{ $vehiculo->numero_interno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Conductor uno</label>
                                    <select name="conductor_uno_id" id="conductor_uno_id" class="form-control" required>
                                        <option value="">Seleccione vehiculo</option>
                                        @foreach (\App\Models\Cargos_personal::with('personal')->with('cargos')->whereHas('cargos', function($query) {
                                            $query->where('cargos.nombre', 'Conductor');
                                        })->get() as $conductor)
                                            <option value="{{ $conductor->personal->id }}">{{ $conductor->personal->nombres }} {{ $conductor->personal->primer_apellido }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="col-sm-12 d-flex">
                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Conductor dos</label>
                                    <select name="conductor_dos_id" id="conductor_dos_id" class="form-control">
                                        <option value="">Seleccione vehiculo</option>
                                        @foreach (\App\Models\Cargos_personal::with('personal')->with('cargos')->whereHas('cargos', function($query) {
                                            $query->where('cargos.nombre', 'Conductor');
                                        })->get() as $conductor)
                                            <option value="{{ $conductor->personal->id }}">{{ $conductor->personal->nombres }} {{ $conductor->personal->primer_apellido }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Conductor tres</label>
                                    <select name="conductor_tres_id" id="conductor_tres_id" class="form-control">
                                        <option value="">Seleccione vehiculo</option>
                                        @foreach (\App\Models\Cargos_personal::with('personal')->with('cargos')->whereHas('cargos', function($query) {
                                            $query->where('cargos.nombre', 'Conductor');
                                        })->get() as $conductor)
                                            <option value="{{ $conductor->personal->id }}">{{ $conductor->personal->nombres }} {{ $conductor->personal->primer_apellido }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row mb-3">

                            <h5 class="col-12">CONTRATO</h5>

                            <div class="col-sm-12 d-flex">
                                <div class="col-sm-12">
                                    <textarea name="contrato_parte_uno" id="contrato_parte_uno" rows="5" class="form-control" required="">Entre los suscritos a saber, AMAZONIA CONSULTORIA & LOGISTICA SAS, Identificada con Nit. 900447438-6 sociedad domiciliada en la ciudad de Neiva, representada legalmente por, JOIMER OSORIO BAQUERO, mayor de edad, vecino de Neiva - Huila, identificado con la cédula de ciudadanía No. 7706232 de Neiva Huila, quien en adelante se denominará El CONTRATISTA, por una parte, y por la otra {{ $tercero[0]->nombre }} , Identificado(a) Cédula de Ciudadania  No {{ $tercero[0]->identificacion }} domiciliado(a)en la ciudad de {{ $tercero[0]->municipio }}
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 d-flex mt-2">
                                <div class="col-sm-12">
                                    <textarea name="contrato_parte_dos" id="contrato_parte_dos" rows="25" class="form-control" required="">quien en lo sucesivo se denominará El CONTRATANTE, se ha celebrado un contrato de prestación de servicios de transporte terrestre, que se rige por la legislación civil y comercial colombiana, además de las siguientes <u>CLÁUSULAS</u>: PRIMERA.OBJETO; EL CONTRATISTA, presta el servicio de transporte solicitado por el contratante en XXXXXXXXXXXXX, el cual se encontrará ajustado a las normas y especificaciones técnicas contempladas en el reglamento de uso y manejo de vehículos del CONTRATANTE y las demás exigencias establecidas de Ley. El servicio se prestará en vehículos preferiblemente XXXXXXXXXXXX con toda la documentación legal al día (SOAT, Revisión Técnico Mecánica, Póliza de daño material todo riesgo que cuente con la cobertura de responsabilidad contractual y extracontractual. Copia del último mantenimiento preventivo, el cual, por ley, se debe realizar cada dos meses, y debe venir elaborado por un ingeniero mecánico con matrícula profesional vigente o por un CDA autorizado, los vehículos deben tener llantas adecuadas en buen estado. Llanta de repuesto, espejos laterales, luces direccionales, luces de freno, freno de parqueo, pito principal, extintor operativo, botiquín de primeros auxilios, equipo de carretera, los vehículos deberán permanecer limpios y en buen estado de mantenimiento mecánico, con el fin de garantizar la seguridad del personal a movilizar. PARAGRAFO 1; Lugares de desplazamiento: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX. PARAGRAFO 2; Horarios: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXS, no obstante, podrá variarse según condiciones que se presenten en las vías y requerimientos en los desplazamientos. Previsiblemente los vehículos saldrán desde XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX SEGUNDA: PRECIO; el valor del servicio prestado será de XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX, pagaderos quince (15) días después de la radicación de la factura. PARAGRAFO: para cada servicio se llevará un control mediante un acta de servicio diario, la cual hace parte integral del contrato. TERCERA; DURACION: La vigencia del presente contrato será de XXXXXXXXXXXXXXXXXXXXXXXXXXXXX y podrá ampliarse de acuerdo a la necesidad del contratante. PARÁGRAFO 1: la prestación de servicio Público de Transporte, se facturará sin IVA teniendo en cuenta que el servicio es EXCLUIDO según el Art 476 Numeral 2 del Estatuto Tributario. CUARTA. -Los gastos de mantenimiento preventivo, correctivo, instalación sistema GPS el cual debe ser suministrado el usuario y contraseña, monitoreo del mismo, al igual el vehículo Backup cuando requiera mantenimiento preventivo o correctivo que debe ser acorde a las exigencias de CONTRATANTE, serán asumidos por el CONTRATISTA, sin que esto implique cobro alguno o adicional para el CONTRATANTE,es responsabilidad del CONTRATISTA mantener los vehículos en perfecto estado mecánico y de funcionamiento. El CONTRATISTA deberá cumplir con todas las normas legalmente establecidas en materia de tránsito y transporte, todos los vehículos deberán contar con las revisiones técnico-mecánicas actualizadas y conforme a las normas de tránsito y transporte. El CONTRATISTA mantendrá el seguro obligatorio del vehículo y seguro todo riesgo vigente. QUINTA. –El control del combustible, alimentación y hospedaje de los conductores será de responsabilidad total del CONTRATANTE. SEXTA. El vehículo será destinado exclusivamente para las labores propias del contrato. SEPTIMA: EL CONTRATISTA está en la obligación de mantener vigente toda la documentación y entregar copia al contratante que legalmente deben tener los vehículos para poder ser  operados, incluyendo las pólizas que debe tener cualquier empresa de transporte legalmente constituida: pólizas de responsabilidad civil contractual, extracontractual, seguro todo riesgo que cubra todo tipo de siniestros y SOAT o seguro obligatorio, tecno mecánica vigente. Los vehículos no podrán ser de un modelo anterior al año 2014. NOVENA: TERMINACIÓN DEL CONTRATO: El contrato se podrá dar por terminado por cualquiera de las siguientes causas: 1. Por vencimiento del plazo pactado. 2. Por incumplimiento de parte del CONTRATISTA o CONTRATANTE a cualquiera de sus obligaciones generadas y o levantamiento del servicio en la zona de operación del objeto del presente contrato- 3. Por la terminación del contrato de prestación de servicios suscrito entre {{ $tercero[0]->nombre }} y sus CLIENTES. 4. Por decisión unilateral de cualquiera de las partes. 5. Por incumplimiento del contratista a las normas o requisitos de HSEQ, establecidos por los clientes los cuales acepta haber leído y entendido antes de la firma del presente documento. 6.Por mutuo acuerdo entre las partes. DECIMA:CLAUSULA PENAL: la parte que incumpla cualquiera de las cláusulas del presente contrato incurrirá en una sanción de 10 SMLMV, la cual podrá ser exigible por la parte afectada. sin prejuicio a las acciones legales que hubiere lugar como consecuencia del incumplimiento. DECIMO PRIMERA: INDEMNIDAD: En todo caso será obligación del CONTRATISTA mantener indemne y libre al CONTRATANTE de cualquier reclamación o demanda que se llegare a presentar proveniente de terceros, que tengan como causa las actuaciones del CONTRATISTA.DECIMA SEGUNDA-Gastos: Los gastos de impuestos de timbre y demás que se ocasionen por el otorgamiento de este contrato, sus prórrogas y renovaciones, en lo no previsto en este contrato, serán asumidos por partes iguales entre los contratantes.DECIMA TERCERA-Notificaciones: Las notificaciones que cualquiera de las partes remita a la otra, deben formularse con certificación de entrega a las siguientes direcciones: El CONTRATANTE: {{ $tercero[0]->direccion }} {{ $tercero[0]->municipio }} - {{ $tercero[0]->departamento }} Tel: {{ $tercero[0]->telefono }} Email: {{ $tercero[0]->correo }};   , El CONTRATISTA: Calle 19 sur # 10 18 0f 105 Neiva Huila, Tel: 8600663 –3168756444, Email: gerencia@amazoniacl.com. DÉCIMO CUARTA-Resolución de Controversias. En caso de conflicto entre las partes de este Contrato de prestación de servicios de transporte, su ejecución y liquidación, deberá agotarse una diligencia de conciliación ante cualquier entidad autorizada para efectuarla, si esta fracasa, se llevará las diferencias ante el Juez Ordinario que sea competente.DÉCIMO QUINTA- MÉRITO EJECUTIVO: El presente contrato presta mérito ejecutivo por contener obligaciones expresas, claras y exigibles a cargo de las partes, para las reclamaciones y exigencias que se derivan del presente contrato como obligaciones del contratista de cara al contratante y viceversa, incluso éste contrato presta mérito ejecutivo en los términos del artículo 422° del Código General del Proceso para el cobro de multas y clausula penal que deba realizar el CONTRATANTE. El presente contrato se firma en la ciudad de Neiva, a los {{ \Carbon\Carbon::now('America/Bogota')->format('d') }} días del mes de {{ \Carbon\Carbon::now('America/Bogota')->formatLocalized('%B') }} de {{ \Carbon\Carbon::now('America/Bogota')->format('Y') }}.
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="cotizacion_id_contrato" id="cotizacion_id_contrato" />
                    <input type="hidden" name="tercero_id_return" id="tercero_id_return" value="{{ $tercero[0]->identificacion }}" />
                    <input type="hidden" name="tercero_id_contrato" id="tercero_id_contrato" value="{{ $tercero[0]->id }}" />

                    <div class="mt-5 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- Modal EDITAR Cotrato --}}
<div class="modal fade bs-example-modal-xl" id="modal_editar_contrato" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal_editar_contrato">Editar Contrato</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/terceros/actualizar_contrato" id="form_actualizar_contrato" method="POST">
                    @csrf

                    <div class="container">
                        <div class="form-group row">

                            <h5 class="col-12">RESPONSABLE</h5>

                            <div class="col-sm-12 d-flex">
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Seleccione responsable</label>
                                    <select name="select_responsable" id="select_responsable_update" onchange="cargar_responsable_contrato(this.value)" class="form-control" required>
                                        <option value="">Seleccione responsable</option>
                                        <option value="Nuevo">Nuevo</option>
                                        @foreach (\App\Models\Contactos_tercero::where('terceros_id', $tercero[0]->id)->get() as $item)
                                            <option value="{{ $item->identificacion }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Numero Identificación</label>
                                    <input class="form-control" type="number" name="identificacion_responsable" id="identificacion_responsable_update" placeholder="Escriba la identificación" required="">
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Nombre Completo</label>
                                    <input class="form-control" type="text" name="nombre_responsable" id="nombre_responsable_update" placeholder="Escriba el nombre" required="">
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Dirección</label>
                                    <input class="form-control" type="text" name="direccion_responsable" id="direccion_responsable_update" placeholder="Escriba la direccion" required="">
                                </div>
                            </div>

                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Telefono</label>
                                    <input class="form-control" type="number" name="telefono_responsable" id="telefono_responsable_update" placeholder="Escriba el Telefono" required="">
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Tipo Contrato</label>
                                    <select name="tipo_contrato" id="tipo_contrato_update" class="form-control" required>
                                        <option value="">Seleccione tipo</option>
                                        <option value="ASALARIADO">ASALARIADO</option>
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Objeto del contrato</label>
                                    <textarea name="objeto_contrato" id="objeto_contrato_update" rows="3" class="form-control" placeholder="Escriba el objeto del contrato" required=""></textarea>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="form-group row mb-3">

                            <h5 class="col-12">CONTRATO</h5>

                            <div class="col-sm-12 d-flex">
                                <div class="col-sm-12">
                                    <textarea name="contrato_parte_uno" id="contrato_parte_uno_update" rows="5" class="form-control" required=""></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 d-flex mt-2">
                                <div class="col-sm-12">
                                    <textarea name="contrato_parte_dos" id="contrato_parte_dos_update" rows="25" class="form-control" required=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="contrato_id" id="contrato_id" />
                    <input type="hidden" name="tercero_id_return" value="{{ $tercero[0]->identificacion }}" />

                    <div class="mt-5 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- Modal Eliminar Cotizacioón --}}
<div class="modal fade" id="modal_eliminar_cotizacion" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/terceros/eliminar_cotizacion" id="form_eliminar_cotizacion" method="post">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="modal_eliminar_cotizacion_tilte">Eliminar Cotizacion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div id="modal_eliminar_cotizacion_content">

                    </div>
                    <input type="hidden" name="cotizacion_id" id="cotizacion_id" />
                    <input type="hidden" name="tercero_id" id="tercero_id" value="{{ $tercero[0]->identificacion }}" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Eliminar CONTRATO --}}
<div class="modal fade" id="modal_eliminar_contrato" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/terceros/eliminar_contrato" id="form_eliminar_contrato" method="post">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="modal_eliminar_contrato_tilte">Eliminar contrato</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div id="modal_eliminar_contrato_content">

                    </div>
                    <input type="hidden" name="contrato_id" id="contrato_id_delete" />
                    <input type="hidden" name="tercero_id" id="tercero_id_delete" value="{{ $tercero[0]->identificacion }}" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDITAR TERCERO MODAL --}}
<div class="modal fade bs-example-modal-xl" id="modal-editar-tercero" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Editar Tercero</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/terceros/update" id="form-create-tercero" method="POST">
                    @csrf

                    <div class="container">
                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Tipo Identificacion</label>
                                    <select name="tipo_identificacion" id="tipo_identificacion" class="form-control" required>
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
                                    <input class="form-control" type="number" name="identificacion" id="identificacion" placeholder="Escriba la identificación" required="">
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Nombre Completo</label>
                                    <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Escriba el nombre" required="">
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Tipo Cliente</label>
                                    <select name="tipo_tercero" id="tipo_tercero" class="form-control" required>
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
                                    <select name="regimen" id="regimen" class="form-control" required>
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
                                    <input class="form-control" type="text" name="direccion" id="direccion" placeholder="Escriba la Dirección" required="">
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

                    <input type="hidden" name="tercero_id" value="{{ $tercero[0]->id }}">

                    <div class="mt-5 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- VER TRAYECTOS MODAL --}}
<div class="modal fade bs-example-modal-xl" id="modal-ver-trayectos" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Trayectos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="content_ver_trayectos">

            </div>
        </div>
    </div>
</div>

{{-- MODAL AGREGAR TRAYECTO --}}
<div class="modal fade bs-example-modal-xl" id="modal_agregar_trayecto" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal_agregar_trayecto_title">Agregar Trayecto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/terceros/agregar_trayecto" id="form_agregar_trayecto" method="POST">
                    @csrf

                    <div id="modal-content-cotizacion">
                        <div class="card-body">

                            <div class="form-group row">
                                <div class="col-sm-6 d-flex">
                                    <div class="form-group row">
                                        <h5 class="col-sm-12 col-form-label">Fechas<hr class="m-0"></h5>

                                        <div class="col-sm-6">
                                            <label class="col-sm-12 col-form-label">Fecha Inicio</label>
                                            <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" type="text" name="fecha_ida" id="fecha_ida_trayecto" placeholder="yyyy-mm-dd" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-12 col-form-label">Fecha Final</label>
                                            <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" type="text" name="fecha_regreso" id="fecha_regreso_trayecto" placeholder="yyyy-mm-dd" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 d-flex">
                                    <div class="form-group row">
                                        <h5 class="col-sm-12 col-form-label">Servicio<hr class="m-0"></h5>

                                        <div class="col-sm-6">
                                            <label class="col-sm-12 col-form-label">Tipo Servicio</label>
                                            <select class="form-control" name="tipo_servicio" id="tipo_servicio_trayecto">
                                                <option value="">Seleccione</option>
                                                <option value="Carga y/o Encomiendas">Carga y/o Encomiendas</option>
                                                <option value="Empresarial">Empresarial</option>
                                                <option value="Escolar">Escolar</option>
                                                <option value="Turismo">Turismo</option>
                                                <option value="Ocasional">Ocasional</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-12 col-form-label">Tipo Vehiculo</label>
                                            <select name="tipo_vehiculo" id="tipo_vehiculo_trayecto" class="form-control" >
												<option value="">Seleccione</option>
												<option value="Station wagon">Station wagon</option>
												<option value="Buseta">Buseta</option>
												<option value="Bus">Bus</option>
												<option value="Campero">Campero</option>
												<option value="Micro Bus">Micro Bus</option>
												<option value="Volqueta">Volqueta</option>
												<option value="Camioneta Cerrada">Camioneta Cerrada</option>
												<option value="Camioneta Doble Cabina 4*4">Camioneta Doble Cabina 4*4</option>
												<option value="Camion">Camion</option>
												<option value="Camioneta de Estacas">Camioneta de Estacas</option>
												<option value="Vans">Vans</option>
												<option value="Camioneta VAN">Camioneta VAN</option>
											</select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 d-flex">
                                    <div class="form-group row">
                                        <h5 class="col-sm-12 col-form-label">Origen<hr class="m-0"></h5>

                                        <div class="col-sm-6">
                                            <label class="col-form-label">Departamento</label>
                                            <select name="departamento_origen" id="departamento_origen_trayecto" class="departamento_origen form-control" onchange="dptOrigen(this.value)" >
                                                <option value="">Seleccione el departamento</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-form-label">Municipio</label>
                                            <select name="ciudad_origen" id="ciudad_origen_trayecto" class="ciudad_origen form-control" >
                                                <option value="">Seleccione el municipio</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 d-flex">
                                    <div class="form-group row">
                                        <h5 class="col-sm-12 col-form-label">Destino<hr class="m-0"></h5>

                                        <div class="col-sm-6">
                                            <label class="col-form-label">Departamento</label>
                                            <select name="departamento_destino" id="departamento_destino_trayecto" class="departamento_destino form-control" onchange="dptDestino(this.value)" >
                                                <option value="">Seleccione el departamento</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-form-label">Municipio</label>
                                            <select name="ciudad_destino" id="ciudad_destino_trayecto" class="ciudad_destino form-control" >
                                                <option value="">Seleccione el municipio</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 d-flex">
                                    <div class="form-group row col-sm-12">
                                        <h5 class="col-form-label col-sm-12">Descripción del Trayecto<hr class="m-0"></h5>

                                        <div class="col-sm-12">
                                            <textarea class="form-control" type="text" name="descripcion" id="descripcion_trayecto" placeholder="Describa los municipios intermedios entre el origen y el destino." ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 d-flex">
                                    <div class="form-group row col-sm-12">
                                        <h5 class="col-form-label col-sm-12">Observaciones del Trayecto<hr class="m-0"></h5>

                                        <div class="col-sm-12">
                                            <textarea class="form-control" type="text" name="observaciones" id="observaciones_trayecto" placeholder="Ejemplo 'El recorrido inicia en la calle 0 No 0-00 a las 05:00AM.' " ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-5 d-flex">
                                    <div class="form-group row">
                                        <h5 class="col-sm-12 col-form-label">Incluye<hr class="m-0"></h5>

                                        <div class="col-sm-4">
                                            <label class="col-sm-12 col-form-label">Conbustible</label>

                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="combustible3" name="combustible_trayecto" value="Si" class="custom-control-input">
                                                <label class="custom-control-label" for="combustible3">Si</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="combustible4" name="combustible_trayecto" value="No" class="custom-control-input" checked="">
                                                <label class="custom-control-label" for="combustible4">No</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-sm-12 col-form-label">Conductor</label>

                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="conductor3" name="conductor_trayecto" value="Si" class="custom-control-input">
                                                <label class="custom-control-label" for="conductor3">Si</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="conductor4" name="conductor_trayecto" value="No" class="custom-control-input" checked="">
                                                <label class="custom-control-label" for="conductor4">No</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-sm-12 col-form-label">Peajes</label>

                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="peajes3" name="peajes_trayecto" value="Si" class="custom-control-input">
                                                <label class="custom-control-label" for="peajes3">Si</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="peajes4" name="peajes_trayecto" value="No" class="custom-control-input" checked="">
                                                <label class="custom-control-label" for="peajes4">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4 d-flex">
                                    <div class="form-group row">
                                        <h5 class="col-sm-12 col-form-label">Cotización por<hr class="m-0"></h5>

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="cotizacion_por4" name="cotizacion_por_trayecto" value="Dias" class="custom-control-input" checked=""/>
                                            <label class="custom-control-label" for="cotizacion_por4">Dia(s)</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="cotizacion_por5" name="cotizacion_por_trayecto" value="Trayecto" class="custom-control-input" />
                                            <label class="custom-control-label" for="cotizacion_por5">Trayecto(s)</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="cotizacion_por6" name="cotizacion_por_trayecto" value="Mensual" class="custom-control-input" />
                                            <label class="custom-control-label" for="cotizacion_por6">Mensual</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 d-flex">
                                    <div class="form-group row">
                                        <h5 class="col-sm-12 col-form-label">Trayecto(s)<hr class="m-0"></h5>

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="recorrido3" name="recorrido_trayecto" value="Solo ida" class="custom-control-input" checked=""/>
                                            <label class="custom-control-label" for="recorrido3">Solo ida</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="recorrido4" name="recorrido_trayecto" value="Ida y vuelta" class="custom-control-input" />
                                            <label class="custom-control-label" for="recorrido4">Ida y vuelta</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 d-flex">
                                    <div class="form-group row col-sm-12">
                                        <h5 class="col-form-label col-sm-12">Costos<hr class="m-0"></h5>

                                        <div class="col-sm-4">
                                            <label class="col-form-label">Valor Unitario</label>
                                            <input class="form-control" type="number" name="valor_unitario" id="valor_unitario_trayecto" onchange="total_cotizacion_trayecto()" placeholder="Escriba el valor unitario" />
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-form-label">Cantidad</label>
                                            <input class="form-control" type="number" name="cantidad" id="cantidad_trayecto" onchange="total_cotizacion_trayecto()" placeholder="Escriba la cantidad" />
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-form-label">Total</label>
                                            <input class="form-control" type="number" name="total" id="total_trayecto" value="0" readonly/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 d-flex">
                                    <div class="form-group row col-sm-12">
                                        <h5 class="col-form-label col-sm-12">Trayecto 2<hr class="m-0"></h5>

                                        <div class="col-sm-12">
                                            <textarea class="form-control" type="number" name="trayecto_dos" id="trayecto_dos_trayecto"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">

                                <h5 class="col-12">VEHICULO</h5>

                                <div class="col-sm-12 d-flex">

                                    <div class="col-sm-6">
                                        <label class="col-sm-12 col-form-label">Vehiculo</label>
                                        <select name="vehiculo_id" id="vehiculo_id_trayecto" class="form-control" required>
                                            <option value="">Seleccione vehiculo</option>
                                            @foreach (\App\Models\Vehiculo::all() as $vehiculo)
                                                <option value="{{ $vehiculo->id }}">{{ $vehiculo->placa }} - {{ $vehiculo->numero_interno }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="col-sm-12 col-form-label">Conductor uno</label>
                                        <select name="conductor_uno_id" id="conductor_uno_id_trayecto" class="form-control" required>
                                            <option value="">Seleccione vehiculo</option>
                                            @foreach (\App\Models\Cargos_personal::with('personal')->with('cargos')->whereHas('cargos', function($query) {
                                                $query->where('cargos.nombre', 'Conductor');
                                            })->get() as $conductor)
                                                <option value="{{ $conductor->personal->id }}">{{ $conductor->personal->nombres }} {{ $conductor->personal->primer_apellido }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="col-sm-12 d-flex">
                                    <div class="col-sm-6">
                                        <label class="col-sm-12 col-form-label">Conductor dos</label>
                                        <select name="conductor_dos_id" id="conductor_dos_id_trayecto" class="form-control">
                                            <option value="">Seleccione vehiculo</option>
                                            @foreach (\App\Models\Cargos_personal::with('personal')->with('cargos')->whereHas('cargos', function($query) {
                                                $query->where('cargos.nombre', 'Conductor');
                                            })->get() as $conductor)
                                                <option value="{{ $conductor->personal->id }}">{{ $conductor->personal->nombres }} {{ $conductor->personal->primer_apellido }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="col-sm-12 col-form-label">Conductor tres</label>
                                        <select name="conductor_tres_id" id="conductor_tres_id_trayecto" class="form-control">
                                            <option value="">Seleccione vehiculo</option>
                                            @foreach (\App\Models\Cargos_personal::with('personal')->with('cargos')->whereHas('cargos', function($query) {
                                                $query->where('cargos.nombre', 'Conductor');
                                            })->get() as $conductor)
                                                <option value="{{ $conductor->personal->id }}">{{ $conductor->personal->nombres }} {{ $conductor->personal->primer_apellido }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="contratos_id" id="contratos_id" />
                            <input type="hidden" name="trayecto_creado" id="trayecto_creado" />

                        </div>

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
