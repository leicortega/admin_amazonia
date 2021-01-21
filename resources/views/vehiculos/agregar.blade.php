@section('title') Agregar Vehiculo @endsection

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

                                <a href="{{ route('vehiculos') }}"><button type="button" class="btn btn-dark btn-lg mb-2 ">Atras</button></a>

                                <h5 class="modal-title mt-0" id="modal-title-correo">Agregar Vehiculo</h5>

                                <form action="/vehiculos/create" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="container p-3">

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group form-group-custom mb-4">
                                                    <input type="text" class="form-control" id="placa" name="placa" required="">
                                                    <label for="placa">Placa</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group form-group-custom mb-4">
                                                    <select name="tipo_vehiculo_id" class="form-control" id="tipo_vehiculo_id" required>
                                                        <option value=""></option>
                                                        @foreach (\App\Models\Sistema\Tipo_Vehiculo::all() as $tipo_vehiculo)
                                                            <option value="{{ $tipo_vehiculo->id }}">{{ $tipo_vehiculo->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="tipo_vehiculo_id">Tipo Vehiculo</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group form-group-custom mb-4">
                                                    <input type="number" class="form-control" id="licencia_transito" name="licencia_transito" required="">
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
                                                            <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="marca_id">Marca</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group form-group-custom mb-4">
                                                    <input type="number" class="form-control" id="modelo" name="modelo" required="">
                                                    <label for="modelo">Modelo</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group form-group-custom mb-4">
                                                    <input type="number" class="form-control" id="capacidad" name="capacidad" required="">
                                                    <label for="capacidad">Capacidad</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group form-group-custom mb-4">
                                                    <div class="form-group form-group-custom mb-4">
                                                        <input type="text" class="form-control" id="numero_motor" name="numero_motor" required="">
                                                        <label for="numero_motor">Numero de Motor</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group form-group-custom mb-4">
                                                    <input type="text" class="form-control" id="chasis" name="chasis" required="">
                                                    <label for="chasis">Chasis</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group form-group-custom mb-4">
                                                    <input type="number" class="form-control" id="numero_interno" name="numero_interno" required="">
                                                    <label for="numero_interno">Numero Interno</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4" id="div_item1">
                                                <div class="form-group form-group-custom mb-4">
                                                    <div class="form-group form-group-custom mb-4">
                                                        <select name="personal_id" class="form-control" id="personal_id" required>
                                                            <option value=""></option>
                                                            @foreach ($propietarios as $propietario)
                                                                <option value="{{ $propietario->id }}">{{ $propietario->nombres }} {{ $propietario->primer_apellido }} {{ $propietario->segundo_apellido }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="personal_id">Propietario</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4" id="div_item2">
                                                <div class="form-group form-group-custom mb-4">
                                                    <select name="tipo_vinculacion_id" class="form-control" id="tipo_vinculacion_id" onchange="select_tipo_vinculacion(this.value)" required>
                                                        <option value=""></option>
                                                        @foreach (\App\Models\Sistema\Tipo_Vinculacion::all() as $tipo_vinculacion)
                                                            <option value="{{ $tipo_vinculacion->id }}">{{ $tipo_vinculacion->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="tipo_vinculacion_id">Tipo Vinculacion</label>
                                                </div>
                                            </div>

                                            <div class="col-sm-3 d-none" id="div_empresa_convenio">
                                                <div class="form-group form-group-custom mb-4">
                                                    <input type="text" class="form-control" id="empresa_convenio" name="empresa_convenio" >
                                                    <label for="empresa_convenio">Empresa Convenio</label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4" id="div_item3">
                                                <div class="form-group form-group-custom mb-4">
                                                    <input type="number" class="form-control" id="tarjeta_operacion" name="tarjeta_operacion" required="">
                                                    <label for="tarjeta_operacion">Tarjeta Operación</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group form-group-custom mb-4">
                                                    <div class="form-group form-group-custom mb-4">
                                                        <input type="text" class="form-control" id="color" name="color" required="">
                                                        <label for="color">Color</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group form-group-custom mb-4">
                                                    <select name="linea_id" class="form-control" id="linea_id" required>
                                                        <option value=""></option>
                                                        @foreach (\App\Models\Sistema\Linea::all() as $linea)
                                                            <option value="{{ $linea->id }}">{{ $linea->nombre }}</option>
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
                                                            <option value="{{ $tipo_carroceria->id }}">{{ $tipo_carroceria->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="tipo_carroceria_id">Tipo de carroceria</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group form-group-custom mb-4">
                                                    <select name="estado" class="form-control" id="estado" required>
                                                        <option value=""></option>
                                                        <option value="Activo">Activo</option>
                                                        <option value="inactivo">inactivo</option>
                                                    </select>
                                                    <label for="estado">Estado</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label><b>{{$documentos[0]->categoria}}</b></label>
                                            <div class="border p-2">
                                                @foreach($documentos as $documento)
                                                    <button onclick="agregar_entidad_expide('{{str_replace(' ', '', $documento->name)}}', '{{$documento->name}}')" type="button" class="btn border ml-2 mt-2 btn-lg waves-effect waves-light" id="btn-submit-correo" data-toggle="modal" data-target="#{{str_replace(' ', '',  $documento->name)}}">{{$documento->name}}</button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    @foreach($documentos as $documento)
                                        <div class="modal fade bs-example-modal-xl" id="{{str_replace(' ', '',  $documento->name)}}" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title mt-0" id="agg_doc_legal_title">Agregar {{$documento->name}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">


                                                            <div class="container p-3">

                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label for="consecutivo" id="consecutivo_title">Consecutivo de {{$documento->name}}</label>
                                                                        <div class="form-group form-group-custom mb-4">
                                                                            <input type="text" class="form-control" id="consecutivo" name="consecutivo{{str_replace(' ', '', $documento->name)}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label for="fecha_expedicion">Fecha expedición</label>
                                                                        <div class="form-group form-group-custom mb-4">
                                                                            <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" id="fecha_expedicion" name="fecha_expedicion{{str_replace(' ', '', $documento->name)}}">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                @if ($documento->vigencia != 0)
                                                                    <div class="row" id="fechas_vigencias">
                                                                        <div class="col-sm-6" id="fecha_inicio_vigencia_div">
                                                                            <label for="fecha_inicio_vigencia">Fecha inicio de vigencia</label>
                                                                            <div class="form-group form-group-custom mb-4">
                                                                                <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" name="fecha_inicio_vigencia{{str_replace(' ', '', $documento->name)}}"  id="fecha_inicio_vigencia">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6" id="fecha_fin_vigencia_div">
                                                                            <label for="fecha_fin_vigencia">Fecha fin de vigencia</label>
                                                                            <div class="form-group form-group-custom mb-4">
                                                                                <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" name="fecha_fin_vigencia{{str_replace(' ', '', $documento->name)}}"  id="fecha_fin_vigencia">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <label for="entidad_expide">Entidad expide</label>
                                                                        <div class="form-group form-group-custom mb-4">
                                                                            <select name="entidad_expide{{str_replace(' ', '', $documento->name)}}" class="form-control" id="entidad_expide{{str_replace(' ', '', $documento->name)}}">
                                                                                <option value=""></option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <label for="documento_file">Agregar Adjunto</label>
                                                                        <div class="form-group form-group-custom mb-4">
                                                                            <input type="file" class="form-control" name="documento_file{{str_replace(' ', '', $documento->name)}}" id="documento_file">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="id_{{str_replace(' ', '', $documento->name)}}" id="id" value='{{$documento->id}}'>


                                                            </div>

                                                            <div class="mt-3 text-center">
                                                                <button class="btn btn-primary btn-lg waves-effect waves-light" class="close" data-dismiss="modal" aria-label="Close">Agregar {{$documento->name}}</button>
                                                            </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="mt-3 text-center">
                                        <button class="btn btn-primary btn-lg waves-effect waves-light" onclick="cargarbtn(this)" id="btn-submit-correo" type="submit">Enviar</button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>






@endsection
