@section('title') Documentación @endsection

@extends('layouts.app')

@section('jsMain') <script src="{{ asset('assets/js/documentacion.js') }}"></script> @endsection

@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">

            @if (session()->has('error') && session()->has('mensaje'))
                <div class="alert {{ session('error') == 0 ? 'alert-success' : 'alert-danger' }}">
                    {{ session('mensaje') }}
                </div>
            @endif


            <div class="alert alert-success d-none" id="delete_modulo_alert">
                Modulo Eliminado Correctamente
            </div>

            <div class="col-xl-12">
                <div class="timeline" dir="ltr">
                    <div class="timeline-item timeline-left">
                        <div class="timeline-block">
                            <div class="time-show-btn mt-0">
                                <a href="#" class="btn btn-success w-lg">AMAZONIA C&L</a>
                            </div>
                        </div>
                    </div>

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
                                    <li>{{ $alerta['nombre'] .' - '. $alerta['name']}} - {{ date("d/m/Y", strtotime($alerta['fecha_fin_vigencia'])) }}</li>
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
                                    <li>{{ $alerta['nombre'] .' - '. $alerta['name'] }} - {{ date("d/m/Y", strtotime($alerta['fecha_fin_vigencia'])) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                    @foreach ($documentacion as $key => $row)
                        <div class="timeline-item {{ $key % 2 == 0 ? 'timeline-left' : '' }} {{ $key > 0 ? 'just-top' : '' }}">
                            <div class="timeline-block">
                                <div class="timeline-box card">
                                    <div class="card-body">

                                        <div class="timeline-icon icons-md">
                                            <i class="uim uim-layer-group"></i>
                                        </div>

                                        <?php echo $key % 2 == 0 ? '<button type="button" onclick="eliminar_modulo('.$row['id'].')" class="btn btn-danger rounded-circle btn-sm"> x </button>' : ''; ?>

                                        <div class="d-inline-block py-1 px-3 bg-primary text-white badge-pill h4">
                                            {{ $row['nombre'] }}
                                        </div>

                                        <?php echo $key % 2 == 0 ? '' : '<button type="button" onclick="eliminar_modulo('.$row['id'].')" class="btn btn-danger rounded-circle btn-sm"> x </button>'; ?>

                                        <p class="mt-3 mb-2">Documentos</p>

                                        <div class="text-muted">
                                            <button class="btn btn-info" onclick="agregar_documento({{ $row['id'] }})"> + </button>
                                            <a class="btn btn-info" data-toggle="collapse" href="#collapse_{{ $row['id'] }}" role="button" aria-expanded="false" aria-controls="collapse_{{ $row['id'] }}" onclick="cargar_documentos({{ $row['id'] }})"> Ver documentos </a>

                                            <div class="collapse" id="collapse_{{ $row['id'] }}">
                                                <div class="card card-body">
                                                    <table class="table table-bordered">
                                                        <thead class="thead-inverse">
                                                            <tr>
                                                                <th class="text-center table-bg-dark">Nombre</th>
                                                                <th class="text-center table-bg-dark">Fecha Inicio Vig.</th>
                                                                <th class="text-center table-bg-dark">Fecha Fin Vig.</th>
                                                                <th class="text-center table-bg-dark">Acciones</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="content_table_documentos_{{ $row['id'] }}">
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
                    @endforeach

                </div>

            </div>

        </div>
        <!-- end row -->

    </div> <!-- container-fluid -->
</div>

{{-- Modal Crear Modulo --}}
<div class="modal fade bs-example-modal-xl" id="modal_crear_modulo" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Crear Modulo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/informacion/documentacion/create_modulo" method="post">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    <label for="nombre">Nombre del modulo</label>
                                </div>
                            </div>
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

{{-- MODAL AGREGAR DOCUMENTO --}}
<div class="modal fade bs-example-modal-xl" id="modal_agregar_documento" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Agregar documento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/informacion/documentacion/agregar_documento" id="form_agregar_documento" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="nombre">Documento</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="nombre" id="nombre_documento" class="form-control" onchange="change_nombre(this.value)" required>

                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12 d-none" id="change_nombre">
                                <label for="nombre">Nombre</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" placeholder="Escriba el nombre" name="nombre_otros">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="file">Agregar Adjunto</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="file" class="form-control" name="file" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <label class="col-sm-12 col-form-label">Vigencia</label>

                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="conductor" name="conductor" value="Si" class="custom-control-input" onchange="cambiarviegencia(1)">
                                <label class="custom-control-label" for="conductor">Si</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="conductor2" name="conductor" value="No" class="custom-control-input" onchange="cambiarviegencia(0)" checked>
                                <label class="custom-control-label" for="conductor2">No</label>
                            </div>
                        </div>

                        <div class="row mt-4 d-none" id="fechas_vigencias">
                            <div class="col-sm-6">
                                <label for="fecha_inicio_vigencia">Fecha inicio de vigencia</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" name="fecha_inicio_vigencia"  id="fecha_inicio_vigencia">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="fecha_fin_vigencia">Fecha fin de vigencia</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" name="fecha_fin_vigencia"  id="fecha_fin_vigencia">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="documentacion_id" id="documentacion_id">

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn_submit_agregar_documento" type="submit">Enviar</button>
                    </div>

                </form>
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
