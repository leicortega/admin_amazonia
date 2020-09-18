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

                                <a href="/vehiculos"><button type="button" class="btn btn-dark btn-lg mb-2">Atras</button></a>
                                <button type="button" class="btn btn-primary btn-lg mb-2 float-right" data-toggle="modal" data-target="#aggVehiculo">Editar</button>

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
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_conductores({{ $vehiculo->id }})" data-parent="#accordion" href="#collapseConductores" aria-expanded="false" aria-controls="collapseConductores" class="text-dark collapsed">
                                        <div class="card-header" id="headingOne">
                                            <h5 class="m-0 font-size-14">CONDUCTORES</h5>
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
                                                        <th class="text-center table-bg-dark">Fecha Inicial</th>
                                                        <th class="text-center table-bg-dark">Fecha Final</th>
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
                                <div class="card mb-0">
                                    <a class="text-dark collapsed" onclick="cargar_tarjeta_propiedad('Tarjeta de Propiedad', {{ $vehiculo->id }})" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <div class="card-header" id="headingTwo">
                                            <h5 class="m-0 font-size-14">DOCUMENTOS LEGALES</h5>
                                        </div>
                                    </a>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" style="">
                                        <div class="card-body">

                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" onclick="cargar_tarjeta_propiedad('Tarjeta de Propiedad', {{ $vehiculo->id }})" data-toggle="tab" href="#Tarjeta_Propiedad" role="tab" aria-selected="true">
                                                       <span class="d-none d-md-inline-block">Tarjeta de Propiedad</span> 
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="cargar_tarjeta_propiedad('Tarjeta Operación', {{ $vehiculo->id }})" data-toggle="tab" href="#Tarjeta_Operacion" role="tab" aria-selected="false">
                                                       <span class="d-none d-md-inline-block">Tarjeta Operación</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="cargar_tarjeta_propiedad('SOAT', {{ $vehiculo->id }})" data-toggle="tab" href="#SOAT" role="tab" aria-selected="false">
                                                       <span class="d-none d-md-inline-block">SOAT</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="cargar_tarjeta_propiedad('Técnico Mecánica', {{ $vehiculo->id }})" data-toggle="tab" href="#Tecnico_Mecanica" role="tab" aria-selected="false">
                                                       <span class="d-none d-md-inline-block">Técnico Mecánica</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="cargar_tarjeta_propiedad('Seguro Todo Riesgo', {{ $vehiculo->id }})" data-toggle="tab" href="#Seguro" role="tab" aria-selected="true">
                                                       <span class="d-none d-md-inline-block">Seguro Todo Riesgo</span> 
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="cargar_tarjeta_propiedad('Certificado GPS', {{ $vehiculo->id }})" data-toggle="tab" href="#Certificado_GPS" role="tab" aria-selected="false">
                                                       <span class="d-none d-md-inline-block">Certificado GPS</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="cargar_tarjeta_propiedad('RUNT', {{ $vehiculo->id }})" data-toggle="tab" href="#RUNT" role="tab" aria-selected="false">
                                                       <span class="d-none d-md-inline-block">RUNT</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="cargar_tarjeta_propiedad('Póliza contractual', {{ $vehiculo->id }})" data-toggle="tab" href="#Poliza_contractual" role="tab" aria-selected="false">
                                                       <span class="d-none d-md-inline-block">Póliza contractual</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" onclick="cargar_tarjeta_propiedad('Póliza extracontractual', {{ $vehiculo->id }})" data-toggle="tab" href="#Poliza_extracontractual" role="tab" aria-selected="false">
                                                       <span class="d-none d-md-inline-block">Póliza extracontractual</span>
                                                    </a>
                                                </li>
                                            </ul>
            
                                            <!-- Tab panes -->
                                            <div class="tab-content p-3">
                                                <div class="tab-pane active" id="Tarjeta_Propiedad" role="tabpanel">

                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Tarjeta de Propiedad')"><i class="fas fa-plus"></i></button>

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
                                                    
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Tarjeta Operación')"><i class="fas fa-plus"></i></button>

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
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('SOAT')"><i class="fas fa-plus"></i></button>

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
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Técnico Mecánica')"><i class="fas fa-plus"></i></button>

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
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Seguro Todo Riesgo')"><i class="fas fa-plus"></i></button>

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
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Certificado GPS')"><i class="fas fa-plus"></i></button>

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
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('RUNT')"><i class="fas fa-plus"></i></button>

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
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Póliza contractual')"><i class="fas fa-plus"></i></button>

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
                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('Póliza extracontractual')"><i class="fas fa-plus"></i></button>

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
                                <div class="card mb-0">
                                    <a class="text-dark" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                        <div class="card-header" id="headingThree">
                                        <h5 class="m-0 font-size-14">Collapsible Group Item #3</h5>
                                        </div>
                                    </a>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion" style="">
                                        <div class="card-body">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life
                                            accusamus terry richardson ad squid. 3 wolf moon officia
                                            aute, non cupidatat skateboard dolor brunch. Food truck
                                            quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor,
                                            sunt aliqua put a bird on it squid single-origin coffee
                                            nulla assumenda shoreditch et. Nihil anim keffiyeh
                                            helvetica, craft beer labore wes anderson cred nesciunt
                                            sapiente ea proident. Ad vegan excepteur butcher vice lomo.
                                            Leggings occaecat craft beer farm-to-table, raw denim
                                            aesthetic synth nesciunt you probably haven't heard of them
                                            accusamus labore sustainable VHS.
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
                            <div class="col-sm-4">
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
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_vinculacion_id" class="form-control" id="tipo_vinculacion_id" required>
                                        <option value=""></option>
                                        @foreach (\App\Models\Sistema\Tipo_Vinculacion::all() as $tipo_vinculacion)
                                            <option value="{{ $tipo_vinculacion->id }}" {{ ($vehiculo->tipo_vinculacion_id == $tipo_vinculacion->id) ? 'selected' : '' }}>{{ $tipo_vinculacion->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label for="tipo_vinculacion_id">Tipo Vinculacion</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
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





