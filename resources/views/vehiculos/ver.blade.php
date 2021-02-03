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
                                @php
                                    $alert=0;
                                @endphp

                                @foreach ($alerta_documentos as $alerta)
                                    @if (\Carbon\Carbon::now('America/Bogota')->format('Y-m-d') > $alerta['fecha_fin_vigencia'])
                                        @php
                                            $alert++;
                                        @endphp
                                    @endif
                                @endforeach
                                @if ($alert != 0)
                                    <div class="alert alert-danger mb-3" role="alert">
                                        <h5 class="text-danger"><b>Documentos Vencidos</b></h5>
                                        <ul>
                                            @foreach ($alerta_documentos as $alerta)
                                                @if (\Carbon\Carbon::now('America/Bogota')->format('Y-m-d') > $alerta['fecha_fin_vigencia'])
                                                    <li>{{ $alerta['name'] }} - {{ date("d/m/Y", strtotime($alerta['fecha_fin_vigencia'])) }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @php
                                    $vencer=0;
                                @endphp

                                @foreach ($alerta_documentos as $alerta)
                                    @if (\Carbon\Carbon::parse($alerta['fecha_fin_vigencia'])->diffInDays(\Carbon\Carbon::now('America/Bogota')) < 30 && \Carbon\Carbon::now('America/Bogota')->format('Y-m-d') < $alerta['fecha_fin_vigencia'])
                                       @php
                                           $vencer++;
                                       @endphp
                                    @endif
                                @endforeach
                                @if ($vencer != 0)
                                    <div class="alert alert-warning mb-3" role="alert">
                                        <h5 class="text-warning"><b>Documentos Por Vencer</b></h5>
                                        <ul>
                                            @foreach ($alerta_documentos as $alerta)
                                                @if (\Carbon\Carbon::parse($alerta['fecha_fin_vigencia'])->diffInDays(\Carbon\Carbon::now('America/Bogota')) < 30 && \Carbon\Carbon::now('America/Bogota')->format('Y-m-d') < $alerta['fecha_fin_vigencia'])
                                                    <li>{{ $alerta['name'] }} - {{ date("d/m/Y", strtotime($alerta['fecha_fin_vigencia'])) }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <a href="/vehiculos"><button onclick="cargarbtn('#atras_vehiculos_btn')" id="atras_vehiculos_btn" type="button" class="btn btn-dark btn-lg mb-2">Atras</button></a>
                                @role('admin') <button type="button" class="btn btn-primary ml-2 btn-lg mb-2 float-right" data-toggle="modal" data-target="#aggVehiculo">Editar</button> @endrole
                                <a href="/vehiculos/{{ $vehiculo->id }}/mantenimientos" class="btn btn-info btn-lg mb-2 float-right ml-2" onclick="cargarbtn(this)">Mantenimientos</a>
                                <a href="/vehiculos/{{ $vehiculo->id }}/inspecciones" class="btn btn-info btn-lg mb-2 float-right ml-2" onclick="cargarbtn(this)">Inspecciones</a>
                                <a href="/vehiculos/trazabilidad_inspecciones/{{ $vehiculo->id }}" class="btn btn-info btn-lg mb-2 float-right" onclick="cargarbtn(this)">Trazabilidad Inspecciones</a>

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
                                            <td>{{\App\Models\Sistema\Tipo_Vehiculo::find($vehiculo->tipo_vehiculo_id)->categoria_vehiculo ?? 'N/A' }}</td>
                                            <td class="table-bg-dark"><b>Categoria</b></td>
                                            <td>{{ \App\Models\Sistema\Tipo_Vehiculo::find($vehiculo->tipo_vehiculo_id)->nombre ?? 'N/A'}}</td>
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
                                        <tr>
                                            <td class="table-bg-dark"><b>No Pasajeros</b></td>
                                            <td >{{ $vehiculo->capacidad }}</td>
                                            <td class="table-bg-dark"><b>Nº de carpeta física</b></td>
                                            <td>{{ $vehiculo->num_carpeta_fisica }}</td>
                                        </tr>
                                        @if ($vehiculo->estado == 'Inactivo')
                                            <tr>
                                                <td class="table-bg-dark" colspan=""><b>Estado</b></td>
                                                <td>{{$vehiculo->estado}}</td>
                                                <td class="table-bg-dark" colspan=""><b>Fecha de estado</b></td>
                                                <td>{{ Carbon\Carbon::parse($vehiculo->fecha_estado)->format('d-m-Y')}}</td>
                                                <td class="table-bg-dark" colspan=""><b>Observaciones</b></td>
                                                <td colspan="3">{{$vehiculo->observacion_estado}}</td>
                                            </tr>
                                        @endif
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

                                                        <label class="sr-only" for="fecha_inicial">Fecha Inicial</label>
                                                        <input class="form-control datepicker-here mb-2 mt-sm-0 mr-sm-3" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" type="text" name="fecha_inicial" id="fecha" placeholder="fecha_inicial" required/>

                                                        <label class="sr-only" for="fecha_inicial">Fecha Final</label>
                                                        <input class="form-control datepicker-here mb-2 mt-sm-0 mr-sm-3" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" type="text" name="fecha_final" id="fecha" placeholder="fecha final" required/>

                                                    <input type="hidden" value="{{ $vehiculo->id }}" name="vehiculo_id" id="vehiculo_id_conductor">

                                                    <button type="submit" class="btn btn-primary mb-2 mt-sm-0" id="btn_crear_conductr">Enviar</button>
                                                </form>

                                                {{-- <button class="btn btn-info waves-effect waves-light mb-2"><i class="fas fa-plus"></i></button> --}}
                                            </div>

                                            <div class="alert alert-primary d-none" id="alerta_success" role="alert">
                                              </div>

                                              <div class="alert alert-danger d-none" id="alerta_dager" role="alert">
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

                                {{-- TAB COMPRAVENTA --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseCompraventa" aria-expanded="false" aria-controls="collapseCompraventa" class="text-dark collapsed" onclick="documentos_compraventa()">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">COMPRAVETA DE VEHICULO</h5>
                                        </div>
                                    </a>

                                    <div id="collapseCompraventa" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                                
                                                <li class="nav-item">
                                                    <a class="nav-link active" onclick="documentos_compraventa()" data-toggle="tab" href="#panecompraventa" role="tab" aria-selected="true">
                                                       <span class="d-none d-md-inline-block">Compraventa</span>
                                                    </a>
                                                </li>
                                            </ul>

                                            <div class="tab-content p-3">
                                                <div class="tab-pane active" id="panecompraventa" role="tabpanel">

                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" data-toggle="modal" data-target="#agg_doc_compraventa"><i class="fas fa-plus" ></i></button>

                                                    <table class="table table-bordered">
                                                        <thead class="thead-inverse">
                                                            <tr>
                                                                <th class="text-center table-bg-dark">No</th>
                                                                <th class="text-center table-bg-dark">Fecha expedición</th>
                                                                <th class="text-center table-bg-dark">Vendedor</th>
                                                                <th class="text-center table-bg-dark">Compador</th>
                                                                <th class="text-center table-bg-dark">Estado</th>
                                                                <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="tabla_compraventas">
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
                                {{-- TAB DOCUMENTOS LEGALES --}}
                                @foreach ($categorias as $categoria)
                                @php
                                   $id= App\Models\Admin_documentos_vehiculo::where("categoria_id", $categoria->id)->first()['id'];
                                   $vigencia= App\Models\Admin_documentos_vehiculo::where("categoria_id", $categoria->id)->first()['vigencia'];
                                   $name= App\Models\Admin_documentos_vehiculo::where("categoria_id", $categoria->id)->first()['name'];
                                   if($vigencia == null || $vigencia == ''){
                                       $vigencia=1;
                                   }
                                @endphp
                                <div class="card mb-0">
                                    <a class="text-dark collapsed" onclick='documentos_legales("{{$id}}", "{{ $vehiculo->id }}", "content_table_{{str_replace(" ", "", preg_replace("([^A-Za-z0-9 ])", "", $name))}}", "{{$vigencia}}")' data-toggle="collapse" data-parent="#accordion" href="#collapse{{str_replace(' ', '', $categoria->categoria)}}" aria-expanded="false" aria-controls="collapse{{str_replace(' ', '', $categoria->categoria)}}">
                                        <div class="card-header bg-dark" id="heading{{str_replace(' ', '', $categoria->categoria)}}">
                                            <h5 class="m-0 font-size-14 text-white">{{$categoria->categoria}}</h5>
                                        </div>
                                    </a>
                                    <div id="collapse{{str_replace(' ', '', $categoria->categoria)}}" class="collapse" aria-labelledby="heading{{str_replace(' ', '', $categoria->categoria)}}" data-parent="#accordion" style="">
                                        <div class="card-body">

                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                                @php
                                                $a=0;
                                                @endphp
                                                @foreach(App\Models\Admin_documentos_vehiculo::where('categoria_id', $categoria->id)->get() as $documento)
                                                @php
                                                $a++;
                                                $name_id=str_replace(' ', '', preg_replace('([^A-Za-z0-9 ])', '', $documento->name));
                                                @endphp
                                                <li class="nav-item">
                                                    <a class="nav-link {{$a==1 ? 'active' : ''}}" onclick="documentos_legales('{{$documento->id}}', {{ $vehiculo->id }}, 'content_table_{{$name_id}}', '{{$documento->vigencia ?? 1}}')" data-toggle="tab" href="#{{$name_id}}" role="tab" aria-selected="{{$a==1 ? 'true' : ''}}">
                                                       <span class="d-none d-md-inline-block">{{$documento->name}}</span>
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>

                                            <!-- Tab panes -->
                                            <div class="tab-content p-3">
                                                @php
                                                $a=0;
                                                @endphp
                                                @foreach(App\Models\Admin_documentos_vehiculo::where('categoria_id', $categoria->id)->get() as $documento)
                                                @php
                                                $a++;
                                                $name_id=str_replace(' ', '', preg_replace('([^A-Za-z0-9 ])', '', $documento->name));
                                                @endphp
                                                <div class="tab-pane {{$a==1 ? 'active' : ''}}" id="{{$name_id}}" role="tabpanel">

                                                    <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="agg_documento_legal('{{$documento->name}}', 'content_table_{{$name_id}}', '{{$documento->vigencia}}', '{{$documento->id}}', '{{$documento->tipo_tercero}}')"><i class="fas fa-plus"></i></button>

                                                    <table class="table table-bordered">
                                                        <thead class="thead-inverse">
                                                            <tr>
                                                                <th class="text-center table-bg-dark">No</th>
                                                                <th class="text-center table-bg-dark">Fecha expedición</th>
                                                                @if ($documento->vigencia)
                                                                <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                                <th class="text-center table-bg-dark">Fecha Final</th>
                                                                <th class="text-center table-bg-dark">Dias de Vigencia</th>
                                                                @endif
                                                                <th class="text-center table-bg-dark">Entidad Expide</th>
                                                                <th class="text-center table-bg-dark">Estado</th>
                                                                <th class="text-center table-bg-dark"><i class="fas fa-cog"></i></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="content_table_{{$name_id}}">
                                                                <tr>
                                                                    <td colspan="{{$documento->vigencia==1 ? '7' : '4'}}" class="text-center">
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                    </table>
                                                </div>
                                                @endforeach
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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

                <form action="" id="agg_targeta_propiedad" method="POST" enctype="multipart/form-data">
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

                        <div class="row" id="fechas_vigencias">
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
                                <label for="entidad_expide" id="entidad_expide_t">Entidad expide</label>
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

                        <input type="hidden" name="id" id="id" value='0'>
                        <input type="hidden" name="id_table" id="id_table">
                        <input type="hidden" name="tipo_id" id="tipo_id">
                        <input type="hidden" name="vehiculo_id" value="{{ $vehiculo->id }}">

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit" id="btn_submit_documentos">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- AGREGAR AGREGAR COMPRAVEMTA --}}
<div class="modal fade bs-example-modal-xl" id="agg_doc_compraventa" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" >Agregar Compraventa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="" id="agg_compraventa" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="consecutivo_comp" id="">Consecutivo Compraventa</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="consecutivo_compraventa" name="consecutivo" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="fecha_expedicion">Fecha expedición</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" id="fecha_expedicion_compraventa" name="fecha_expedicion" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="entidad_expide" id="">Comprador</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="comprador" class="form-control" id="comprador_id_compra" required>
                                        <option value="">Seleccione</option>
                                        @php
                                            $id=App\Models\Sistema\Cargo::where('nombre', 'Propietario')->first()->id;
                                        @endphp
                                        @foreach (App\Models\Cargos_personal::join('personal', 'personal.id', '=', 'cargos_personal.personal_id')->where('cargos_id', $id)->get() as $item)
                                        <option value="{{$item->id}}">{{$item->nombres}} {{$item->primer_apellido}} {{$item->segundo_apellido}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="file_doc">Agregar Adjunto</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="file" class="form-control" name="documento_file" id="file_doc">
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <input type="hidden" name="id_existe" id="id_existe_compra">
                    <input type="hidden" name="vehiculo_id" value="{{ $vehiculo->id }}">

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit" id="btn_submit_compra">Enviar</button>
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

                <form action="/vehiculos/update" method="POST" onsubmit="cargarbtn('#btn-submit-editar-veh')">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-3" id="">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="placa" name="placa" value="{{ $vehiculo->placa }}" required="">
                                    <label for="placa">Placa</label>
                                </div>
                            </div>
                            <div class="col-sm-3" id="">
                                <div class="form-group form-group-custom mb-4">
                                    <select onchange="editar_tipo_vehiculo(this.value)" name="tipo_vehiculo" class="form-control" id="tipo_vehiculo" required>
                                        @php
                                            
                                        @endphp
                                        <option value=""></option>
                                        <option value="Especial" {{ (\App\Models\Sistema\Tipo_Vehiculo::find($vehiculo->tipo_vehiculo_id)->categoria_vehiculo == 'Especial') ? 'selected' : '' }}>Especial</option>
                                        <option value="Carga" {{ (\App\Models\Sistema\Tipo_Vehiculo::find($vehiculo->tipo_vehiculo_id)->categoria_vehiculo == 'Carga') ? 'selected' : '' }}>Carga</option>
                                    </select>
                                    <label for="tipo_vehiculo_id">Tipo Vehiculo</label>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_vehiculo_id" class="form-control" id="tipo_vehiculo_id" required>
                                        <option value=""></option>
                                        @foreach (\App\Models\Sistema\Tipo_Vehiculo::all() as $tipo_vehiculo)
                                            <option value="{{ $tipo_vehiculo->id }}" {{ ($vehiculo->tipo_vehiculo_id == $tipo_vehiculo->id) ? 'selected' : '' }}>{{ $tipo_vehiculo->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <label for="tipo_vehiculo_id">Categoria</label>
                                </div>
                            </div>
                            <div class="col-sm-3" id="">
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
                            <div class="col-sm-4">
                                <div class="form-group form-group-custom mb-4">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control" value="{{ $vehiculo->color }}" id="color" name="color" required="">
                                        <label for="color">Color</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
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
                            <div class="col-sm-4">
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

                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-group-custom mb-4">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control" id="num_carpeta_fisica" name="num_carpeta_fisica" required value="{{$vehiculo->num_carpeta_fisica}}">
                                        <label for="num_carpeta_fisica">Nº de carpeta fisica</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="estado" class="form-control" id="estado" required onchange="estado_nuevo(this)">
                                        <option value=""></option>
                                        <option value="Activo" {{ ($vehiculo->estado == 'Activo') ? 'selected' : '' }}>Activo</option>
                                        <option value="inactivo" {{ ($vehiculo->estado == 'Inactivo') ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                    <label for="estado">Estado</label>
                                </div>
                            </div>
                        </div>

                        <div class="row d-none" id="estado_inactivo">
                            <div class="col-sm-3 mt-4">
                                <div class="form-group form-group-custom mb-4 mt-1">
                                    <input class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" type="text" name="fecha_estado" id="fecha_estado"/>
                                    <label for="fecha">Fecha</label>
                                </div>
                            </div>

                            <div class="col-sm-9">
                                <div class="form-group mb-4">
                                    <label for="descripcion">Observaciones </label>
                                    <textarea type="text" class="form-control" id="observacion_estado" name="observacion_estado" rows="5"></textarea>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id" value="{{ $vehiculo->id }}">

                    </div>

                    <div class="mt-3 text-center">
                        <button type="submit" class="btn btn-primary btn-lg waves-effect waves-light" id="btn-submit-editar-veh" >Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL VER HITORIAL CONDUCTOR --}}
<div class="modal fade bs-example-modal-xl" id="modal_ver_historial_conductor" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal_ver_historial_conductor_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_ver_historial_conductor_content">
                <table class="table table-bordered">
                    <thead class="thead-inverse">
                        <tr>
                            <th class="text-center table-bg-dark"><b>No</b></th>
                            <th class="text-center table-bg-dark"><b>Fecha Inicial</b></th>
                            <th class="text-center table-bg-dark"><b>Fecha Final</b></th>
                            <th class="text-center table-bg-dark"><b>Dias de Vigencia</b></th>
                            <th class="text-center table-bg-dark"><b>Estado</b></th>
                            <th class="text-center table-bg-dark"><b>Acciones</b></th>
                        </tr>
                        </thead>
                        <tbody id="table_ver_historial_vehiculo">
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

{{-- MODAL EXPORTAR --}}
<div class="modal fade bs-example-modal-xl" id="modal_exportar" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Exportar Documentación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/informacion/documentacion/exportar_documentos" id="form_exportar_documentos" method="POST">
                    @csrf

                    <div class="container p-3">

                        <div class="col-sm-12">
                            <div class="mt-4 mt-sm-0">
                                <h5 class="font-size-14 mb-3">Seleccionar documentos a exportar</h5>

                                <div id="content_exportar_documentos"></div>

                            </div>
                        </div>

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn_submit_exportar_documentos" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
