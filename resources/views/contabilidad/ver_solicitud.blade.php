@section('title') Ver Solicitudes {{$solicitud->tipo_solicitud}}  @endsection

@section('jsMain')
    <script src="{{ asset('assets/js/peticiones.js') }}"></script>
    <script src="{{ asset('assets/js/solicitud_dinero.js') }}"></script>
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

                                @if (session()->has('error') && session()->has('mensaje'))
                                    <div class="alert {{ session('error') == 0 ? 'alert-success' : 'alert-danger' }}">
                                        {{ session('mensaje') }}
                                    </div>
                                @endif

                                <a href="{{ route('solicitud_dinero') }}"><button type="button" class="btn btn-dark btn-lg mb-2" onclick="cargarbtn(this)">Atras</button></a>


                                <a href="{{route('solicitud_pdf', $solicitud->id)}}" target="_blank" class="btn btn-success mb-2 ml-2 float-right">Reporte PDF</a>



                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center table-bg-dark">
                                            <th> <b>Tipo Solicitud</b></th>
                                            <th> <b>Fecha</b></th>
                                            <th> <b>Solicitante</b></th>
                                            <th> <b>Beneficiario</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{$solicitud->tipo_solicitud}}</td>
                                            <td>{{ Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d-m-Y')}}</td>
                                            <td>{{$solicitud->name}}</td>
                                            <td>{{$solicitud->nombres}} {{$solicitud->primer_apellido}} {{$solicitud->segundo_apellido}}</td>
                                        </tr>
                                        <tr class="text-center table-bg-dark">
                                            <th colspan='4'> <b>Descripcion</b></th>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark text-center" colspan='4' >{{$solicitud->descripcion}}</td>
                                        </tr>
                                        <tr>
                                    </tbody>
                                </table>

                                <h3>Datos Puntuales</h3>

                                <table class="table table-bordered">
                                    <thead>
                                            <tr class="text-center table-bg-dark">
                                                <th rowspan='2' class="align-middle"><b>concepto</b></th>
                                                <th colspan="3" class="align-middle"><b>Valores</b></th>
                                                <th rowspan='2' class="align-middle"><b>Estados</b></th>
                                            </tr>
                                            <tr class="text-center table-bg-dark">
                                                <th><b>Entregado</b></th>
                                                <th><b>Soportado</b></th>
                                                <th><b>Saldo</b></th>
                                            </tr>
                                    </thead>
                                    <tbody>


                                        @foreach($conceptos as $concepto)
                                            <tr class="text-center table-bg-dark">

                                                <td class="align-middle">{{$concepto->nombre}}</td>

                                                <td class="align-middle">{{$concepto->valor_entregado}}</td>

                                                <td class="align-middle">{{$concepto->valor_soportado}}  &nbsp;&nbsp;
                                                    @if ($concepto->valor_soportado != 0)
                                                        <button onclick="see_soportes({{$concepto->id}})" type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" id="btn_ver_soporte_sop" data-placement="top" title="Ver Soportes">
                                                        <i class="mdi mdi-eye"></i>
                                                        </button>
                                                    @endif
                                                    @php
                                                        $estado = $estados->where('conceptos_id', $concepto->id)->orderBy('created_at', 'desc')->first()['estado'];
                                                    @endphp
                                                    @if($concepto->saldo != 0 && $estado != 'Solicitado' && $estado != 'Cancelado' && $estado != 'Negado')
                                                        <button onclick="add_id_t({{$concepto->saldo}},{{$concepto->id}})" type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-placement="top" data-target="#agregar_soporte" title="Agregar Soporte">
                                                        <i class="mdi mdi-plus"></i>
                                                        </button>
                                                    @endif
                                                </td>

                                                <td class="align-middle">{{$concepto->saldo}}</td>

                                                <td class="align-middle">
                                                    <button onclick="verestado({{$concepto->id}})" type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" id="ver_estado_btn" data-placement="top" title="Ver Estados">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button>
                                                    @if ($estado != 'Entregado')
                                                        @role('admin') 
                                                            <button onclick="add_id_estado({{$concepto->id}},'{{$estado}}')" type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#agregar_estado" data-placement="top" title="Agregar Estado">
                                                                <i class="mdi mdi-plus"></i>
                                                            </button>
                                                        @endrole
                                                    @endif
                                                </td>

                                        </tr>
                                        @endforeach


                                    </tbody>
                                </table>

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

{{-- MODAL AGREGAR SOPORTE --}}
<div class="modal fade bs-example-modal-xl" id="agregar_soporte" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Agregar Soportes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{route('solicitud_add_soporte')}}" method="POST" enctype="multipart/form-data" onsubmit="cargarbtn('#btn_agregar_soporte')">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="soporte">Cuanto va a soportar</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" id="soporte" name="soporte" min="0" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label for="imagen_soporte">Agregar Adjunto</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="file" accept="image/*" class="form-control" name="imagen_soporte" id="imagen_soporte" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="id" id="id" class="id_agregar_soporte" value="">

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn_agregar_soporte" type="submit">Enviar Soporte</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL Ver SOPORTE --}}
<div class="modal fade bs-example-modal-xl" id="ver_soporte" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Ver Soportes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                            <tr class="text-center table-bg-dark">
                                <th class="align-middle"><b>Fecha Soporte</b></th>
                                <th class="align-middle"><b>Archivo</b></th>
                                <th class="align-middle"><b>Valor</b></th>
                            </tr>
                    </thead>
                    <tbody class="table_soportes">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


{{-- MODAL AGREGAR ESTADO --}}
<div class="modal fade bs-example-modal-xl" id="agregar_estado" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Agregar Estados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{route('solicitud_add_estado')}}" method="POST" enctype="multipart/form-data" onsubmit="cargarbtn('#agregar_estado_btn')">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="estado">Estado</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="estado" id="estados_selec" class="form-control" id="estado" required>
                                        {{-- <option value="">Seleccione</option>
                                        <option value="Solicitado">Solicitado</option>
                                        <option value="Cancelado">Cancelado</option>
                                        <option value="Aprobado">Aprobado</option>
                                        <option value="Negado">Negado</option>
                                        <option value="Entregado">Entregado</option>
                                        <option value="Modificar">Modificar</option> --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-4">
                                <label for="concepto">Describa brevemente el Estado</label>
                                <textarea name="descripcion" id="descripcion" rows="5" class="form-control" required placeholder="Describa el estado"></textarea>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="id_estado" id="id" class="id_estado" value="">

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="agregar_estado_btn" type="submit">Enviar Estado</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


{{-- MODAL Ver ESTADO --}}
<div class="modal fade bs-example-modal-xl" id="ver_estado" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Ver Estados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                            <tr class="text-center table-bg-dark">
                                <th class="align-middle"><b>Estado</b></th>
                                <th class="align-middle"><b>Definido por</b></th>
                                <th class="align-middle"><b>Fecha </b></th>
                                <th class="align-middle"><b>Descripcion</b></th>
                            </tr>
                    </thead>
                    <tbody class="table_estados">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
