@section('title') Vehiculo  @endsection

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

                                <div class="alert alert-danger mb-3" role="alert">
                                    <h5 class="text-danger"><b>Documentos Vencidos</b></h5>
                                    <ul>
                                        @foreach ($alerta_documentos as $alerta)
                                            @if (\Carbon\Carbon::now('America/Bogota')->format('Y-m-d') > $alerta['fecha_fin_vigencia'])
                                                <li>{{ $alerta['tipo'] }} - {{ $alerta['fecha_fin_vigencia'] }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="alert alert-warning mb-3" role="alert">
                                    <h5 class="text-warning"><b>Documentos Por Vencer</b></h5>
                                    <ul>
                                        @foreach ($alerta_documentos as $alerta)
                                            @if (\Carbon\Carbon::parse($alerta['fecha_fin_vigencia'])->diffInDays(\Carbon\Carbon::now('America/Bogota')) < 30 && \Carbon\Carbon::now('America/Bogota')->format('Y-m-d') < $alerta['fecha_fin_vigencia'])
                                                <li>{{ $alerta['tipo'] }} - {{ $alerta['fecha_fin_vigencia'] }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>

                                <a href="/vehiculos"><button type="button" class="btn btn-dark btn-lg mb-2">Atras</button></a>
                                @role('admin') <button type="button" class="btn btn-primary ml-2 btn-lg mb-2 float-right" data-toggle="modal" data-target="#aggVehiculo">Editar</button> @endrole
                                <a href="/vehiculos/{{ $vehiculo->id }}/mantenimientos" class="btn btn-info btn-lg mb-2 float-right ml-2">Mantenimientos</a>
                                <a href="/vehiculos/{{ $vehiculo->id }}/inspecciones" class="btn btn-info btn-lg mb-2 float-right ml-2">Inspecciones</a>
                                <a href="/vehiculos/trazabilidad_inspecciones/{{ $vehiculo->id }}" class="btn btn-info btn-lg mb-2 float-right">Trazabilidad Inspecciones</a>

                                @if (session()->has('update') && session('update') == 1)
                                    <div class="alert alert-success">
                                        Vehiculo actualizado correctamente
                                    </div>
                                @endif

                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center table-bg-dark">
                                            <th colspan="6">Vehiculo <b>{{ $vehiculo->placa }}</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="table-bg-dark"><b>Placa</b></td>
                                            <td>{{ $vehiculo->placa }}</td>
                                            <td class="table-bg-dark"><b>No Motor</b></td>
                                            <td>{{ $vehiculo->numero_motor }}</td>
                                            <td class="table-bg-dark"><b>No Chasis</b></td>
                                            <td>{{ $vehiculo->chasis }}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Licencia de Transito</b></td>
                                            <td>{{ $vehiculo->licencia_transito }}</td>
                                            <td class="table-bg-dark"><b>Tipo Vehiculo</b></td>
                                            <td>{{ \App\Models\Sistema\Tipo_Vehiculo::find($vehiculo->tipo_vehiculo_id)->nombre }}</td>
                                            <td class="table-bg-dark"><b>No Pasajeros</b></td>
                                            <td>{{ $vehiculo->capacidad }}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Propietario</b></td>
                                            <td>{{ \App\Models\Personal::find($vehiculo->personal_id)->nombres }} {{ \App\Models\Personal::find($vehiculo->personal_id)->primer_apellido }}</td>
                                            <td class="table-bg-dark"><b>Modelo</b></td>
                                            <td>{{ $vehiculo->modelo }}</td>
                                            <td class="table-bg-dark"><b>Marca</b></td>
                                            <td>{{ \App\Models\Sistema\Marca::find($vehiculo->marca_id)->nombre }}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>No Interno</b></td>
                                            <td>{{ $vehiculo->numero_interno }}</td>
                                            <td class="table-bg-dark"><b>Tipo Vinculación</b></td>
                                            <td>{{ \App\Models\Sistema\Tipo_Vinculacion::find($vehiculo->tipo_vinculacion_id)->nombre }}</td>
                                            <td class="table-bg-dark"><b>Tarjeta operaciones</b></td>
                                            <td>{{ $vehiculo->tarjeta_operacion }}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Color</b></td>
                                            <td>{{ $vehiculo->color }}</td>
                                            <td class="table-bg-dark"><b>Linea</b></td>
                                            <td>{{ \App\Models\Sistema\Linea::find($vehiculo->linea_id)->nombre }}</td>
                                            <td class="table-bg-dark"><b>Carroceria</b></td>
                                            <td>{{ \App\Models\Sistema\Tipo_Carroceria::find($vehiculo->tipo_carroceria_id)->nombre }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div id="accordion" class="col-12">
                                {{-- TAB CONDUCTORES --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_conductores({{ $vehiculo->id }})" data-parent="#accordion" href="#collapseConductores" aria-expanded="false" aria-controls="collapseConductores" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">CONDUCTORES</h5>
                                        </div>
                                    </a>

                                    <div id="collapseConductores" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row float-right">
                                                <form class="form-inline mr-3" id="form_agg_conductor" method="POST" action="">
                                                    @csrf

                                                    <label class="sr-only" for="conductor">Seleccione Conductor</label>
                                                    <select name="conductor" id="conductor" class="form-control mb-2 mt-sm-0 mr-sm-3" required>
                                                        <option value="">Seleccione el Conductor</option>
                                                        @foreach ($conductores as $conductor)
                                                            <option value="{{ $conductor->personal->id }}">{{ $conductor->personal->nombres }} {{ $conductor->personal->primer_apellido }}</option>
                                                        @endforeach
                                                    </select>

                                                    <input type="hidden" value="{{ $vehiculo->id }}" name="vehiculo_id">

                                                    <button type="submit" class="btn btn-primary mb-2 mt-sm-0">Enviar</button>
                                                </form>

                                                {{-- <button class="btn btn-info waves-effect waves-light mb-2"><i class="fas fa-plus"></i></button> --}}
                                            </div>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Nombre(s) y Apellido(s)</th>
                                                        {{-- <th class="text-center table-bg-dark">Fecha Inicial</th>
                                                        <th class="text-center table-bg-dark">Fecha Final</th> --}}
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="content_table_conductores">
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
                                {{-- TAB DOCUMENTOS LEGALES --}}
                                <div class="card mb-0">
                                    <a class="text-dark collapsed" onclick="documentos_legales('Tarjeta de Propiedad', {{ $vehiculo->id }}, 'content_table_documentos_legales')" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <div class="card-header bg-dark" id="headingTwo">
                                            <h5 class="m-0 font-size-14 text-white">DOCUMENTOS LEGALES</h5>
                                        </div>
                                    </a>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" style="">
                                        <div class="card-body">

                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" onclick="documentos_legales('Tarjeta de Propiedad', {{ $vehiculo->id }}, 'content_table_documentos_legales')" data-toggle="tab" href="#Tarjeta_Propiedad" role="tab" aria-selected="true">
                                                       <span class="d-none d-md-inline-block">Tarjeta de Propiedad</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="documentos_legales('Tarjeta Operación', {{ $vehiculo->id }}, 'content_table_tarjeta_operacion')" data-toggle="tab" href="#Tarjeta_Operacion" role="tab" aria-selected="false">
                                                       <span class="d-none d-md-inline-block">Tarjeta Operación</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="documentos_legales('SOAT', {{ $vehiculo->id }}, 'content_table_soat')" data-toggle="tab" href="#SOAT" role="tab" aria-selected="false">
                                                       <span class="d-none d-md-inline-block">SOAT</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="documentos_legales('Técnico Mecánica', {{ $vehiculo->id }}, 'content_table_tecnico_mecanica')" data-toggle="tab" href="#Tecnico_Mecanica" role="tab" aria-selected="false">
                                                       <span class="d-none d-md-inline-block">Técnico Mecánica</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="documentos_legales('Seguro Todo Riesgo', {{ $vehiculo->id }}, 'content_table_seguro')" data-toggle="tab" href="#Seguro" role="tab" aria-selected="true">
                                                       <span class="d-none d-md-inline-block">Seguro Todo Riesgo</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="documentos_legales('Certificado GPS', {{ $vehiculo->id }}, 'content_table_gps')" data-toggle="tab" href="#Certificado_GPS" role="tab" aria-selected="false">
                                                       <span class="d-none d-md-inline-block">Certificado GPS</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="documentos_legales('RUNT', {{ $vehiculo->id }}, 'content_table_runt')" data-toggle="tab" href="#RUNT" role="tab" aria-selected="false">
                                                       <span class="d-none d-md-inline-block">RUNT</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="documentos_legales('Póliza contractual', {{ $vehiculo->id }}, 'content_table_contractual')" data-toggle="tab" href="#Poliza_contractual" role="tab" aria-selected="false">
                                                       <span class="d-none d-md-inline-block">Póliza contractual</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="documentos_legales('Póliza extracontractual', {{ $vehiculo->id }}, 'content_table_extracontractual')" data-toggle="tab" href="#Poliza_extracontractual" role="tab" aria-selected="false">
                                                       <span class="d-none d-md-inline-block">Póliza extracontractual</span>
                                                    </a>
                                                </li>
                                            </ul>

                                            <!-- Tab panes -->
                                            <div class="tab-content p-3">
                                                <div class="tab-pane active" id="Tarjeta_Propiedad" role="tabpanel">

                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Tarjeta de Propiedad', 'content_table_documentos_legales')"><i class="fas fa-plus"></i></button>

                                                    <table class="table table-bordered">
                                                        <thead class="thead-inverse">
                                                            <tr>
                                                                <th class="text-center table-bg-dark">No</th>
                                                                <th class="text-center table-bg-dark">Fecha expedición</th>
                                                                <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                                <th class="text-center table-bg-dark">Fecha Final</th>
                                                                <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                                <th class="text-center table-bg-dark">Entidad Expide</th>
                                                                <th class="text-center table-bg-dark">Estado</th>
                                                                <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="content_table_documentos_legales">
                                                                <tr>
                                                                    <td colspan="8" class="text-center">
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                    </table>
                                                </div>
                                                <div class="tab-pane" id="Tarjeta_Operacion" role="tabpanel">

                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Tarjeta Operación', 'content_table_tarjeta_operacion')"><i class="fas fa-plus"></i></button>

                                                    <table class="table table-bordered">
                                                        <thead class="thead-inverse">
                                                            <tr>
                                                                <th class="text-center table-bg-dark">No</th>
                                                                <th class="text-center table-bg-dark">Fecha expedición</th>
                                                                <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                                <th class="text-center table-bg-dark">Fecha Final</th>
                                                                <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                                <th class="text-center table-bg-dark">Entidad Expide</th>
                                                                <th class="text-center table-bg-dark">Estado</th>
                                                                <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="content_table_tarjeta_operacion">
                                                                <tr>
                                                                    <td colspan="8" class="text-center">
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                    </table>

                                                </div>
                                                <div class="tab-pane" id="SOAT" role="tabpanel">
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('SOAT', 'content_table_soat')"><i class="fas fa-plus"></i></button>

                                                    <table class="table table-bordered">
                                                        <thead class="thead-inverse">
                                                            <tr>
                                                                <th class="text-center table-bg-dark">No</th>
                                                                <th class="text-center table-bg-dark">Fecha expedición</th>
                                                                <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                                <th class="text-center table-bg-dark">Fecha Final</th>
                                                                <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                                <th class="text-center table-bg-dark">Entidad Expide</th>
                                                                <th class="text-center table-bg-dark">Estado</th>
                                                                <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="content_table_soat">
                                                                <tr>
                                                                    <td colspan="8" class="text-center">
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                    </table>
                                                </div>
                                                <div class="tab-pane" id="Tecnico_Mecanica" role="tabpanel">
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Técnico Mecánica', 'content_table_tecnico_mecanica')"><i class="fas fa-plus"></i></button>

                                                    <table class="table table-bordered">
                                                        <thead class="thead-inverse">
                                                            <tr>
                                                                <th class="text-center table-bg-dark">No</th>
                                                                <th class="text-center table-bg-dark">Fecha expedición</th>
                                                                <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                                <th class="text-center table-bg-dark">Fecha Final</th>
                                                                <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                                <th class="text-center table-bg-dark">Entidad Expide</th>
                                                                <th class="text-center table-bg-dark">Estado</th>
                                                                <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="content_table_tecnico_mecanica">
                                                                <tr>
                                                                    <td colspan="8" class="text-center">
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                    </table>
                                                </div>
                                                <div class="tab-pane" id="Seguro" role="tabpanel">
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Seguro Todo Riesgo', 'content_table_seguro')"><i class="fas fa-plus"></i></button>

                                                    <table class="table table-bordered">
                                                        <thead class="thead-inverse">
                                                            <tr>
                                                                <th class="text-center table-bg-dark">No</th>
                                                                <th class="text-center table-bg-dark">Fecha expedición</th>
                                                                <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                                <th class="text-center table-bg-dark">Fecha Final</th>
                                                                <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                                <th class="text-center table-bg-dark">Entidad Expide</th>
                                                                <th class="text-center table-bg-dark">Estado</th>
                                                                <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="content_table_seguro">
                                                                <tr>
                                                                    <td colspan="8" class="text-center">
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                    </table>
                                                </div>
                                                <div class="tab-pane" id="Certificado_GPS" role="tabpanel">
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Certificado GPS', 'content_table_gps')"><i class="fas fa-plus"></i></button>

                                                    <table class="table table-bordered">
                                                        <thead class="thead-inverse">
                                                            <tr>
                                                                <th class="text-center table-bg-dark">No</th>
                                                                <th class="text-center table-bg-dark">Fecha expedición</th>
                                                                <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                                <th class="text-center table-bg-dark">Fecha Final</th>
                                                                <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                                <th class="text-center table-bg-dark">Entidad Expide</th>
                                                                <th class="text-center table-bg-dark">Estado</th>
                                                                <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="content_table_gps">
                                                                <tr>
                                                                    <td colspan="8" class="text-center">
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                    </table>
                                                </div>
                                                <div class="tab-pane" id="RUNT" role="tabpanel">
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('RUNT', 'content_table_runt')"><i class="fas fa-plus"></i></button>

                                                    <table class="table table-bordered">
                                                        <thead class="thead-inverse">
                                                            <tr>
                                                                <th class="text-center table-bg-dark">No</th>
                                                                <th class="text-center table-bg-dark">Fecha expedición</th>
                                                                <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                                <th class="text-center table-bg-dark">Fecha Final</th>
                                                                <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                                <th class="text-center table-bg-dark">Entidad Expide</th>
                                                                <th class="text-center table-bg-dark">Estado</th>
                                                                <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="content_table_runt">
                                                                <tr>
                                                                    <td colspan="8" class="text-center">
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                    </table>
                                                </div>
                                                <div class="tab-pane" id="Poliza_contractual" role="tabpanel">
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Póliza contractual', 'content_table_contractual')"><i class="fas fa-plus"></i></button>

                                                    <table class="table table-bordered">
                                                        <thead class="thead-inverse">
                                                            <tr>
                                                                <th class="text-center table-bg-dark">No</th>
                                                                <th class="text-center table-bg-dark">Fecha expedición</th>
                                                                <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                                <th class="text-center table-bg-dark">Fecha Final</th>
                                                                <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                                <th class="text-center table-bg-dark">Entidad Expide</th>
                                                                <th class="text-center table-bg-dark">Estado</th>
                                                                <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="content_table_contractual">
                                                                <tr>
                                                                    <td colspan="8" class="text-center">
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                    </table>
                                                </div>
                                                <div class="tab-pane" id="Poliza_extracontractual" role="tabpanel">
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Póliza extracontractual', 'content_table_extracontractual')"><i class="fas fa-plus"></i></button>

                                                    <table class="table table-bordered">
                                                        <thead class="thead-inverse">
                                                            <tr>
                                                                <th class="text-center table-bg-dark">No</th>
                                                                <th class="text-center table-bg-dark">Fecha expedición</th>
                                                                <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                                <th class="text-center table-bg-dark">Fecha Final</th>
                                                                <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                                <th class="text-center table-bg-dark">Entidad Expide</th>
                                                                <th class="text-center table-bg-dark">Estado</th>
                                                                <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="content_table_extracontractual">
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
                                    </div>
                                </div>
                                {{-- TAB VINCULACION --}}
                                <div class="card mb-0">
                                    <a class="text-dark" data-toggle="collapse" onclick="documentos_legales('Certificado de desvinculación', {{ $vehiculo->id }}, 'content_table_certificado_desvinculacion')" data-parent="#accordion" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                        <div class="card-header bg-dark" id="headingThree">
                                        <h5 class="m-0 font-size-14 text-white">VINCULACION</h5>
                                        </div>
                                    </a>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion" style="">

                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" onclick="documentos_legales('Certificado de desvinculación', {{ $vehiculo->id }}, 'content_table_certificado_desvinculacion')" data-toggle="tab" href="#Certificado-de-desvinculacion" role="tab" aria-selected="true">
                                                   <span class="d-none d-md-inline-block">Certificado de desvinculación</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="documentos_legales('Solicitud de cambio de empresa en la tarjeta de operación', {{ $vehiculo->id }}, 'content_table_solicitud_cambio_empresa')" data-toggle="tab" href="#Solicitud-cambio-empresa" role="tab" aria-selected="false">
                                                   <span class="d-none d-md-inline-block">Solicitud de cambio de empresa en la tarjeta de operación</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="documentos_legales('Solicitud y/o certificado de disponibilidad', {{ $vehiculo->id }}, 'content_table_solicitud_certificado_disponibilidad')" data-toggle="tab" href="#Solicitud_certificado_disponibilidad" role="tab" aria-selected="false">
                                                   <span class="d-none d-md-inline-block">Solicitud y/o certificado de disponibilidad</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="documentos_legales('Certificado de seción de derechos (SIG-CA-F-21)', {{ $vehiculo->id }}, 'content_table_certificado_secion_derechos')" data-toggle="tab" href="#certificado_secion_derechos" role="tab" aria-selected="false">
                                                   <span class="d-none d-md-inline-block">Certificado de seción de derechos (SIG-CA-F-21)</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="documentos_legales('Carta de aceptación (SIG-CA-F-21)', {{ $vehiculo->id }}, 'content_table_carta_aceptacion')" data-toggle="tab" href="#carta_aceptacion" role="tab" aria-selected="true">
                                                   <span class="d-none d-md-inline-block">Carta de aceptación (SIG-CA-F-21)</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="documentos_legales('Contrato de vinculación (SIG-CA-F-75)', {{ $vehiculo->id }}, 'content_table_contrato_vinculacion')" data-toggle="tab" href="#contrato_vinculacion" role="tab" aria-selected="false">
                                                   <span class="d-none d-md-inline-block">Contrato de vinculación (SIG-CA-F-75)</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="documentos_legales('Pagare-carta de instrucciones (SIG-F-80)', {{ $vehiculo->id }}, 'content_table_pagare_carta')" data-toggle="tab" href="#pagare_carta" role="tab" aria-selected="false">
                                                   <span class="d-none d-md-inline-block">Pagare-carta de instrucciones (SIG-F-80)</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <!-- Tab panes -->
                                        <div class="tab-content p-3">
                                            <div class="tab-pane active" id="Certificado-de-desvinculacion" role="tabpanel">

                                                <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Certificado de desvinculación', 'content_table_certificado_desvinculacion')"><i class="fas fa-plus"></i></button>

                                                <table class="table table-bordered">
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="text-center table-bg-dark">No</th>
                                                            <th class="text-center table-bg-dark">Fecha expedición</th>
                                                            <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                            <th class="text-center table-bg-dark">Fecha Final</th>
                                                            <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                            <th class="text-center table-bg-dark">Entidad Expide</th>
                                                            <th class="text-center table-bg-dark">Estado</th>
                                                            <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="content_table_certificado_desvinculacion">
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="Solicitud-cambio-empresa" role="tabpanel">

                                                <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Solicitud de cambio de empresa en la tarjeta de operación', 'content_table_solicitud_cambio_empresa')"><i class="fas fa-plus"></i></button>

                                                <table class="table table-bordered">
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="text-center table-bg-dark">No</th>
                                                            <th class="text-center table-bg-dark">Fecha expedición</th>
                                                            <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                            <th class="text-center table-bg-dark">Fecha Final</th>
                                                            <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                            <th class="text-center table-bg-dark">Entidad Expide</th>
                                                            <th class="text-center table-bg-dark">Estado</th>
                                                            <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="content_table_solicitud_cambio_empresa">
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                </table>

                                            </div>
                                            <div class="tab-pane" id="Solicitud_certificado_disponibilidad" role="tabpanel">
                                                <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Solicitud y/o certificado de disponibilidad', 'content_table_solicitud_certificado_disponibilidad')"><i class="fas fa-plus"></i></button>

                                                <table class="table table-bordered">
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="text-center table-bg-dark">No</th>
                                                            <th class="text-center table-bg-dark">Fecha expedición</th>
                                                            <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                            <th class="text-center table-bg-dark">Fecha Final</th>
                                                            <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                            <th class="text-center table-bg-dark">Entidad Expide</th>
                                                            <th class="text-center table-bg-dark">Estado</th>
                                                            <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="content_table_solicitud_certificado_disponibilidad">
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="certificado_secion_derechos" role="tabpanel">
                                                <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Certificado de seción de derechos (SIG-CA-F-21)', 'content_table_certificado_secion_derechos')"><i class="fas fa-plus"></i></button>

                                                <table class="table table-bordered">
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="text-center table-bg-dark">No</th>
                                                            <th class="text-center table-bg-dark">Fecha expedición</th>
                                                            <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                            <th class="text-center table-bg-dark">Fecha Final</th>
                                                            <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                            <th class="text-center table-bg-dark">Entidad Expide</th>
                                                            <th class="text-center table-bg-dark">Estado</th>
                                                            <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="content_table_certificado_secion_derechos">
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="carta_aceptacion" role="tabpanel">
                                                <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Carta de aceptación (SIG-CA-F-21)', 'content_table_carta_aceptacion')"><i class="fas fa-plus"></i></button>

                                                <table class="table table-bordered">
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="text-center table-bg-dark">No</th>
                                                            <th class="text-center table-bg-dark">Fecha expedición</th>
                                                            <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                            <th class="text-center table-bg-dark">Fecha Final</th>
                                                            <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                            <th class="text-center table-bg-dark">Entidad Expide</th>
                                                            <th class="text-center table-bg-dark">Estado</th>
                                                            <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="content_table_carta_aceptacion">
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="contrato_vinculacion" role="tabpanel">
                                                <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Contrato de vinculación (SIG-CA-F-75)', 'content_table_contrato_vinculacion')"><i class="fas fa-plus"></i></button>

                                                <table class="table table-bordered">
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="text-center table-bg-dark">No</th>
                                                            <th class="text-center table-bg-dark">Fecha expedición</th>
                                                            <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                            <th class="text-center table-bg-dark">Fecha Final</th>
                                                            <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                            <th class="text-center table-bg-dark">Entidad Expide</th>
                                                            <th class="text-center table-bg-dark">Estado</th>
                                                            <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="content_table_contrato_vinculacion">
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="pagare_carta" role="tabpanel">
                                                <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Pagare-carta de instrucciones (SIG-F-80)', 'content_table_pagare_carta')"><i class="fas fa-plus"></i></button>

                                                <table class="table table-bordered">
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="text-center table-bg-dark">No</th>
                                                            <th class="text-center table-bg-dark">Fecha expedición</th>
                                                            <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                            <th class="text-center table-bg-dark">Fecha Final</th>
                                                            <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                            <th class="text-center table-bg-dark">Entidad Expide</th>
                                                            <th class="text-center table-bg-dark">Estado</th>
                                                            <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="content_table_pagare_carta">
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
                                </div>
                                {{-- TAB COMPRAVENTA DE VEHICULO --}}
                                <div class="card mb-0">
                                    <a class="text-dark" data-toggle="collapse" onclick="documentos_legales('Compraventa', {{ $vehiculo->id }}, 'content_table_compraventa')" data-parent="#accordion" href="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                        <div class="card-header bg-dark" id="headingFour">
                                        <h5 class="m-0 font-size-14 text-white">COMPRAVENTA DE VEHICULO</h5>
                                        </div>
                                    </a>
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion" style="">

                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" onclick="documentos_legales('Compraventa', {{ $vehiculo->id }}, 'content_table_compraventa')" data-toggle="tab" href="#compraventa" role="tab" aria-selected="true">
                                                   <span class="d-none d-md-inline-block">Compraventa</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <!-- Tab panes -->
                                        <div class="tab-content p-3">
                                            <div class="tab-pane active" id="compraventa" role="tabpanel">

                                                <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Compraventa', 'content_table_compraventa')"><i class="fas fa-plus"></i></button>

                                                <table class="table table-bordered">
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="text-center table-bg-dark">No</th>
                                                            <th class="text-center table-bg-dark">Fecha expedición</th>
                                                            <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                            <th class="text-center table-bg-dark">Fecha Final</th>
                                                            <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                            <th class="text-center table-bg-dark">Entidad Expide</th>
                                                            <th class="text-center table-bg-dark">Estado</th>
                                                            <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="content_table_compraventa">
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
                                </div>
                                {{-- TAB CONVENIOS Y CONTRATOS DE PRESTACION DE SERVICIOS --}}
                                <div class="card mb-0">
                                    <a class="text-dark" data-toggle="collapse" onclick="documentos_legales('Convenios colaboración empresarial (SIG-F-73)', {{ $vehiculo->id }}, 'content_table_convenios_colaboracion')" data-parent="#accordion" href="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                                        <div class="card-header bg-dark" id="headingFive">
                                        <h5 class="m-0 font-size-14 text-white">CONVENIOS Y CONTRATOS DE PRESTACION DE SERVICIOS</h5>
                                        </div>
                                    </a>
                                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion" style="">

                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" onclick="documentos_legales('Convenios colaboración empresarial (SIG-F-73)', {{ $vehiculo->id }}, 'content_table_convenios_colaboracion')" data-toggle="tab" href="#convenios_colaboracion" role="tab" aria-selected="true">
                                                   <span class="d-none d-md-inline-block">Convenios colaboración empresarial (SIG-F-73)</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" onclick="documentos_legales('Contrarto civil de prestación de servicios de transporte (SIG-F-49)', {{ $vehiculo->id }}, 'content_table_contrato_prestacion_servicios')" data-toggle="tab" href="#contrato_prestacion_servicios" role="tab" aria-selected="true">
                                                   <span class="d-none d-md-inline-block">Contrarto civil de prestación de servicios de transporte (SIG-F-49)</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <!-- Tab panes -->
                                        <div class="tab-content p-3">
                                            <div class="tab-pane active" id="convenios_colaboracion" role="tabpanel">

                                                <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Convenios colaboración empresarial (SIG-F-73)', 'content_table_convenios_colaboracion')"><i class="fas fa-plus"></i></button>

                                                <table class="table table-bordered">
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="text-center table-bg-dark">No</th>
                                                            <th class="text-center table-bg-dark">Fecha expedición</th>
                                                            <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                            <th class="text-center table-bg-dark">Fecha Final</th>
                                                            <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                            <th class="text-center table-bg-dark">Entidad Expide</th>
                                                            <th class="text-center table-bg-dark">Estado</th>
                                                            <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="content_table_convenios_colaboracion">
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="contrato_prestacion_servicios" role="tabpanel">

                                                <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Contrarto civil de prestación de servicios de transporte (SIG-F-49)', 'content_table_contrato_prestacion_servicios')"><i class="fas fa-plus"></i></button>

                                                <table class="table table-bordered">
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="text-center table-bg-dark">No</th>
                                                            <th class="text-center table-bg-dark">Fecha expedición</th>
                                                            <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                            <th class="text-center table-bg-dark">Fecha Final</th>
                                                            <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                            <th class="text-center table-bg-dark">Entidad Expide</th>
                                                            <th class="text-center table-bg-dark">Estado</th>
                                                            <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="content_table_contrato_prestacion_servicios">
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
                                </div>
                                {{-- TAB INSPECCIONES MENSUALES --}}
                                <div class="card mb-0">
                                    <a class="text-dark" data-toggle="collapse" onclick="documentos_legales('Ultima inspección mensual (SIG-F-89)', {{ $vehiculo->id }}, 'content_table_ultima_inspeccion')" data-parent="#accordion" href="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                                        <div class="card-header bg-dark" id="headingSix">
                                        <h5 class="m-0 font-size-14 text-white">INSPECCIONES MENSUALES</h5>
                                        </div>
                                    </a>
                                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion" style="">

                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" onclick="documentos_legales('Ultima inspección mensual (SIG-F-89)', {{ $vehiculo->id }}, 'content_table_ultima_inspeccion')" data-toggle="tab" href="#ultima_inspeccion" role="tab" aria-selected="true">
                                                   <span class="d-none d-md-inline-block">Ultima inspección mensual (SIG-F-89)</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <!-- Tab panes -->
                                        <div class="tab-content p-3">
                                            <div class="tab-pane active" id="ultima_inspeccion" role="tabpanel">

                                                <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Ultima inspección mensual (SIG-F-89)', 'content_table_ultima_inspeccion')"><i class="fas fa-plus"></i></button>

                                                <table class="table table-bordered">
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="text-center table-bg-dark">No</th>
                                                            <th class="text-center table-bg-dark">Fecha expedición</th>
                                                            <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                            <th class="text-center table-bg-dark">Fecha Final</th>
                                                            <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                            <th class="text-center table-bg-dark">Entidad Expide</th>
                                                            <th class="text-center table-bg-dark">Estado</th>
                                                            <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="content_table_ultima_inspeccion">
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
                                </div>
                                {{-- TAB ACTAS DE ENTREGA Y RECIBIDO --}}
                                <div class="card mb-0">
                                    <a class="text-dark" data-toggle="collapse" onclick="documentos_legales('Ultima acta entrega y/o recibido (SIG-F-47)', {{ $vehiculo->id }}, 'content_table_ultima_acta_entrega')" data-parent="#accordion" href="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
                                        <div class="card-header bg-dark" id="headingSeven">
                                        <h5 class="m-0 font-size-14 text-white">ACTAS DE ENTREGA Y RECIBIDO</h5>
                                        </div>
                                    </a>
                                    <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion" style="">

                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" onclick="documentos_legales('Ultima acta entrega y/o recibido (SIG-F-47)', {{ $vehiculo->id }}, 'content_table_ultima_acta_entrega')" data-toggle="tab" href="#ultima_acta_entrega" role="tab" aria-selected="true">
                                                   <span class="d-none d-md-inline-block">Ultima acta entrega y/o recibido (SIG-F-47)</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <!-- Tab panes -->
                                        <div class="tab-content p-3">
                                            <div class="tab-pane active" id="ultima_acta_entrega" role="tabpanel">

                                                <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Ultima acta entrega y/o recibido (SIG-F-47)', 'content_table_ultima_acta_entrega')"><i class="fas fa-plus"></i></button>

                                                <table class="table table-bordered">
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="text-center table-bg-dark">No</th>
                                                            <th class="text-center table-bg-dark">Fecha expedición</th>
                                                            <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                            <th class="text-center table-bg-dark">Fecha Final</th>
                                                            <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                            <th class="text-center table-bg-dark">Entidad Expide</th>
                                                            <th class="text-center table-bg-dark">Estado</th>
                                                            <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="content_table_ultima_acta_entrega">
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
                                </div>
                                {{-- TAB BIMESTRAL --}}
                                <div class="card mb-0">
                                    <a class="text-dark" data-toggle="collapse" onclick="documentos_legales('Ultima bimestarl CDA', {{ $vehiculo->id }}, 'content_table_ultima_bimestral')" data-parent="#accordion" href="#collapseEight" aria-expanded="true" aria-controls="collapseEight">
                                        <div class="card-header bg-dark" id="headingEight">
                                        <h5 class="m-0 font-size-14 text-white">BIMESTRAL</h5>
                                        </div>
                                    </a>
                                    <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion" style="">

                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" onclick="documentos_legales('Ultima bimestarl CDA', {{ $vehiculo->id }}, 'content_table_ultima_bimestral')" data-toggle="tab" href="#ultima_bimestral" role="tab" aria-selected="true">
                                                   <span class="d-none d-md-inline-block">Ultima bimestarl CDA</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <!-- Tab panes -->
                                        <div class="tab-content p-3">
                                            <div class="tab-pane active" id="ultima_bimestral" role="tabpanel">

                                                <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Ultima bimestarl CDA', 'content_table_ultima_bimestral')"><i class="fas fa-plus"></i></button>

                                                <table class="table table-bordered">
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="text-center table-bg-dark">No</th>
                                                            <th class="text-center table-bg-dark">Fecha expedición</th>
                                                            <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                            <th class="text-center table-bg-dark">Fecha Final</th>
                                                            <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                            <th class="text-center table-bg-dark">Entidad Expide</th>
                                                            <th class="text-center table-bg-dark">Estado</th>
                                                            <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="content_table_ultima_bimestral">
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
                                </div>
                                {{-- TAB SOPORTE DE MANTENIMIENTOS --}}
                                <div class="card mb-0">
                                    <a class="text-dark" data-toggle="collapse" onclick="documentos_legales('Ultimo soporte de mantenimiento', {{ $vehiculo->id }}, 'content_table_ultimo_mantenimiento')" data-parent="#accordion" href="#collapseNine" aria-expanded="true" aria-controls="collapseNine">
                                        <div class="card-header bg-dark" id="headingNine">
                                        <h5 class="m-0 font-size-14 text-white">SOPORTE DE MANTENIMIENTOS</h5>
                                        </div>
                                    </a>
                                    <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion" style="">

                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" onclick="documentos_legales('Ultimo soporte de mantenimiento', {{ $vehiculo->id }}, 'content_table_ultimo_mantenimiento')" data-toggle="tab" href="#ultimo_mantenimiento" role="tab" aria-selected="true">
                                                   <span class="d-none d-md-inline-block">Ultimo soporte de mantenimiento</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <!-- Tab panes -->
                                        <div class="tab-content p-3">
                                            <div class="tab-pane active" id="ultimo_mantenimiento" role="tabpanel">

                                                <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Ultimo soporte de mantenimiento', 'content_table_ultimo_mantenimiento')"><i class="fas fa-plus"></i></button>

                                                <table class="table table-bordered">
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="text-center table-bg-dark">No</th>
                                                            <th class="text-center table-bg-dark">Fecha expedición</th>
                                                            <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                            <th class="text-center table-bg-dark">Fecha Final</th>
                                                            <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                            <th class="text-center table-bg-dark">Entidad Expide</th>
                                                            <th class="text-center table-bg-dark">Estado</th>
                                                            <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="content_table_ultimo_mantenimiento">
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
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

{{-- AGREGAR AGREGAR DOCUMENTO --}}
<div class="modal fade bs-example-modal-xl" id="agg_doc_legal" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="agg_doc_legal_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/vehiculos/agg_targeta_propiedad" id="agg_targeta_propiedad" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="consecutivo" id="consecutivo_title"></label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="consecutivo" name="consecutivo" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="fecha_expedicion">Fecha expedición</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" id="fecha_expedicion" name="fecha_expedicion" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6" id="fecha_inicio_vigencia_div">
                                <label for="fecha_inicio_vigencia">Fecha inicio de vigencia</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" name="fecha_inicio_vigencia"  id="fecha_inicio_vigencia">
                                </div>
                            </div>
                            <div class="col-sm-6" id="fecha_fin_vigencia_div">
                                <label for="fecha_fin_vigencia">Fecha fin de vigencia</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" name="fecha_fin_vigencia"  id="fecha_fin_vigencia">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="entidad_expide">Entidad expide</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="entidad_expide" class="form-control" id="entidad_expide">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="documento_file">Agregar Adjunto</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="file" class="form-control" name="documento_file" id="documento_file">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="id_table" id="id_table">
                        <input type="hidden" name="tipo" id="tipo">
                        <input type="hidden" name="vehiculo_id" value="{{ $vehiculo->id }}">

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn-submit-correo" type="submit">Enviar</button>
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

{{-- EDITAR VEHICULO --}}
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

                <form action="/vehiculos/update" method="POST">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="placa" name="placa" value="{{ $vehiculo->placa }}" required="">
                                    <label for="placa">Placa</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_vehiculo_id" class="form-control" id="tipo_vehiculo_id" required>
                                        <option value=""></option>
                                        @foreach (\App\Models\Sistema\Tipo_Vehiculo::all() as $tipo_vehiculo)
                                            <option value="{{ $tipo_vehiculo->id }}" {{ ($vehiculo->tipo_vehiculo_id == $tipo_vehiculo->id) ? 'selected' : '' }}>{{ $tipo_vehiculo->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label for="tipo_vehiculo_id">Tipo Vehiculo</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" value="{{ $vehiculo->licencia_transito }}" id="licencia_transito" name="licencia_transito" required="">
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
                                            <option value="{{ $marca->id }}" {{ ($vehiculo->marca_id == $marca->id) ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label for="marca_id">Marca</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" value="{{ $vehiculo->modelo }}" id="modelo" name="modelo" required="">
                                    <label for="modelo">Modelo</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" value="{{ $vehiculo->capacidad }}" id="capacidad" name="capacidad" required="">
                                    <label for="capacidad">Capacidad</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control" value="{{ $vehiculo->numero_motor }}" id="numero_motor" name="numero_motor" required="">
                                        <label for="numero_motor">Numero de Motor</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" value="{{ $vehiculo->chasis }}" id="chasis" name="chasis" required="">
                                    <label for="chasis">Chasis</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" value="{{ $vehiculo->licencia_transito }}" id="numero_interno" name="numero_interno" required="">
                                    <label for="numero_interno">Numero Interno</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div  class="{{ $vehiculo->tipo_vinculacion_id == 1 ? 'col-sm-3' : 'col-sm-4' }}" id="div_item1">
                                <div class="form-group form-group-custom mb-4">
                                    <div class="form-group form-group-custom mb-4">
                                        <select name="personal_id" class="form-control" id="personal_id" required>
                                            <option value=""></option>
                                            @foreach ($propietarios as $propietario)
                                                <option value="{{ $propietario->id }}" {{ ($vehiculo->personal_id == $propietario->id) ? 'selected' : '' }}>{{ $propietario->nombres }} {{ $propietario->primer_apellido }} {{ $propietario->segundo_apellido }}</option>
                                            @endforeach
                                        </select>
                                        <label for="personal_id">Propietario</label>
                                    </div>
                                </div>
                            </div>
                            <div  class="{{ $vehiculo->tipo_vinculacion_id == 1 ? 'col-sm-3' : 'col-sm-4' }}" id="div_item2">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_vinculacion_id" class="form-control" id="tipo_vinculacion_id" onchange="select_tipo_vinculacion(this.value)" required>
                                        <option value=""></option>
                                        @foreach (\App\Models\Sistema\Tipo_Vinculacion::all() as $tipo_vinculacion)
                                            <option value="{{ $tipo_vinculacion->id }}" {{ ($vehiculo->tipo_vinculacion_id == $tipo_vinculacion->id) ? 'selected' : '' }}>{{ $tipo_vinculacion->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label for="tipo_vinculacion_id">Tipo Vinculacion</label>
                                </div>
                            </div>


                            <div class="col-sm-3 {{ $vehiculo->tipo_vinculacion_id == 1 ? '' : 'd-none' }}" id="div_empresa_convenio">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="empresa_convenio" name="empresa_convenio" value="{{ $vehiculo->empresa_convenio }}">
                                    <label for="empresa_convenio">Empresa Convenio</label>
                                </div>
                            </div>

                            <div  class="{{ $vehiculo->tipo_vinculacion_id == 1 ? 'col-sm-3' : 'col-sm-4' }}" id="div_item3">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" value="{{ $vehiculo->tarjeta_operacion }}" id="tarjeta_operacion" name="tarjeta_operacion" required="">
                                    <label for="tarjeta_operacion">Tarjeta Operación</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group form-group-custom mb-4">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control" value="{{ $vehiculo->color }}" id="color" name="color" required="">
                                        <label for="color">Color</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="linea_id" class="form-control" id="linea_id" required>
                                        <option value=""></option>
                                        @foreach (\App\Models\Sistema\Linea::all() as $linea)
                                            <option value="{{ $linea->id }}" {{ ($vehiculo->linea_id == $linea->id) ? 'selected' : '' }}>{{ $linea->nombre }}</option>
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
                                            <option value="{{ $tipo_carroceria->id }}" {{ ($vehiculo->tipo_carroceria_id == $tipo_carroceria->id) ? 'selected' : '' }}>{{ $tipo_carroceria->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label for="tipo_carroceria_id">Tipo de carroceria</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="estado" class="form-control" id="estado" required>
                                        <option value=""></option>
                                        <option value="Activo" {{ ($vehiculo->estado == 'Activo') ? 'selected' : '' }}>Activo</option>
                                        <option value="inactivo" {{ ($vehiculo->estado == 'inactivo') ? 'selected' : '' }}>inactivo</option>
                                    </select>
                                    <label for="estado">Estado</label>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id" value="{{ $vehiculo->id }}">

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
