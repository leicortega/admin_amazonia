@section('title') Tercero  @endsection 

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
                                        Tercero actualizado correctamente
                                    </div>
                                @endif

                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center table-bg-dark">
                                            <th colspan="6"><b>{{ $tercero->nombre }}</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="table-bg-dark"><b></b></td>
                                            <td>{{ $tercero->placa }}</td>
                                            <td class="table-bg-dark"><b>No Motor</b></td>
                                            <td>{{ $tercero->numero_motor }}</td>
                                            <td class="table-bg-dark"><b>No Chasis</b></td>
                                            <td>{{ $tercero->chasis }}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Licencia de Transito</b></td>
                                            <td>{{ $tercero->licencia_transito }}</td>
                                            <td class="table-bg-dark"><b>Tipo Vehiculo</b></td>
                                            <td></td>
                                            <td class="table-bg-dark"><b>No Pasajeros</b></td>
                                            <td>{{ $tercero->capacidad }}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Propietario</b></td>
                                            <td></td>
                                            <td class="table-bg-dark"><b>Modelo</b></td>
                                            <td>{{ $tercero->modelo }}</td>
                                            <td class="table-bg-dark"><b>Marca</b></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>No Interno</b></td>
                                            <td>{{ $tercero->numero_interno }}</td>
                                            <td class="table-bg-dark"><b>Tipo Vinculación</b></td>
                                            <td></td>
                                            <td class="table-bg-dark"><b>Tarjeta operaciones</b></td>
                                            <td>{{ $tercero->tarjeta_operacion }}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Color</b></td>
                                            <td>{{ $tercero->color }}</td>
                                            <td class="table-bg-dark"><b>Linea</b></td>
                                            <td></td>
                                            <td class="table-bg-dark"><b>Carroceria</b></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
    
                            <div id="accordion" class="col-12">
                                {{-- TAB CONDUCTORESexpande --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_conductores({{ $tercero->id }})" data-parent="#accordion" href="#collapseConductores" aria-expanded="false" aria-controls="collapseConductores" class="text-dark collapsed">
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
                                                        {{-- @foreach ($conductores as $conductor)
                                                            <option value="{{ $conductor->personal->id }}">{{ $conductor->personal->nombres }} {{ $conductor->personal->primer_apellido }}</option>
                                                        @endforeach --}}
                                                    </select>

                                                    <input type="hidden" value="{{ $tercero->id }}" name="vehiculo_id">
        
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
                        <input type="hidden" name="vehiculo_id" value="{{ $tercero->id }}">
                        
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
                                    <input type="text" class="form-control" id="placa" name="placa" value="{{ $tercero->placa }}" required="">
                                    <label for="placa">Placa</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_vehiculo_id" class="form-control" id="tipo_vehiculo_id" required>
                                        <option value=""></option>
                                        {{-- @foreach (\App\Models\Sistema\Tipo_Vehiculo::all() as $tipo_vehiculo)
                                            <option value="{{ $tipo_vehiculo->id }}" {{ ($tercero->tipo_vehiculo_id == $tipo_vehiculo->id) ? 'selected' : '' }}>{{ $tipo_vehiculo->nombre }}</option>
                                        @endforeach --}}
                                    </select>
                                    <label for="tipo_vehiculo_id">Tipo Vehiculo</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" value="{{ $tercero->licencia_transito }}" id="licencia_transito" name="licencia_transito" required="">
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
                                            <option value="{{ $marca->id }}" {{ ($tercero->marca_id == $marca->id) ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label for="marca_id">Marca</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" value="{{ $tercero->modelo }}" id="modelo" name="modelo" required="">
                                    <label for="modelo">Modelo</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" value="{{ $tercero->capacidad }}" id="capacidad" name="capacidad" required="">
                                    <label for="capacidad">Capacidad</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control" value="{{ $tercero->numero_motor }}" id="numero_motor" name="numero_motor" required="">
                                        <label for="numero_motor">Numero de Motor</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" value="{{ $tercero->chasis }}" id="chasis" name="chasis" required="">
                                    <label for="chasis">Chasis</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" value="{{ $tercero->licencia_transito }}" id="numero_interno" name="numero_interno" required="">
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
                                            {{-- @foreach ($propietarios as $propietario)
                                                <option value="{{ $propietario->id }}" {{ ($tercero->personal_id == $propietario->id) ? 'selected' : '' }}>{{ $propietario->nombres }} {{ $propietario->primer_apellido }} {{ $propietario->segundo_apellido }}</option>
                                            @endforeach --}}
                                        </select>
                                        <label for="personal_id">Propietario</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_vinculacion_id" class="form-control" id="tipo_vinculacion_id" required>
                                        <option value=""></option>
                                        {{-- @foreach (\App\Models\Sistema\Tipo_Vinculacion::all() as $tipo_vinculacion)
                                            <option value="{{ $tipo_vinculacion->id }}" {{ ($tercero->tipo_vinculacion_id == $tipo_vinculacion->id) ? 'selected' : '' }}>{{ $tipo_vinculacion->nombre }}</option>
                                        @endforeach --}}
                                    </select>
                                    <label for="tipo_vinculacion_id">Tipo Vinculacion</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" value="{{ $tercero->tarjeta_operacion }}" id="tarjeta_operacion" name="tarjeta_operacion" required="">
                                    <label for="tarjeta_operacion">Tarjeta Operación</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group form-group-custom mb-4">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control" value="{{ $tercero->color }}" id="color" name="color" required="">
                                        <label for="color">Color</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="linea_id" class="form-control" id="linea_id" required>
                                        <option value=""></option>
                                        {{-- @foreach (\App\Models\Sistema\Linea::all() as $linea)
                                            <option value="{{ $linea->id }}" {{ ($tercero->linea_id == $linea->id) ? 'selected' : '' }}>{{ $linea->nombre }}</option>
                                        @endforeach --}}
                                    </select>
                                    <label for="linea_id">Linea</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_carroceria_id" class="form-control" id="tipo_carroceria_id" required>
                                        <option value=""></option>
                                        {{-- @foreach (\App\Models\Sistema\Tipo_Carroceria::all() as $tipo_carroceria)
                                            <option value="{{ $tipo_carroceria->id }}" {{ ($tercero->tipo_carroceria_id == $tipo_carroceria->id) ? 'selected' : '' }}>{{ $tipo_carroceria->nombre }}</option>
                                        @endforeach --}}
                                    </select>
                                    <label for="tipo_carroceria_id">Tipo de carroceria</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="estado" class="form-control" id="estado" required>
                                        <option value=""></option>
                                        <option value="Activo" {{ ($tercero->estado == 'Activo') ? 'selected' : '' }}>Activo</option>
                                        <option value="inactivo" {{ ($tercero->estado == 'inactivo') ? 'selected' : '' }}>inactivo</option>
                                    </select>
                                    <label for="estado">Estado</label>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id" value="{{ $tercero->id }}">
                        
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