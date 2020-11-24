@section('title') Mantenimiento Vehiculo {{ $mantenimiento->vehiculo->placa }}  @endsection

@section('jsMain')
    <script src="{{ asset('assets/js/mantenimientos.js') }}"></script>
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

                                <a href="{{ url()->previous() }}"><button type="button" class="btn btn-dark btn-lg mb-2">Atras</button></a>

                                <button type="button" class="btn btn-primary mb-2 ml-2 float-right" data-toggle="modal" data-target="#agregar_factura">Registrar Factura</button>
                                @if (!$mantenimiento->persona_contabilidad)
                                    <button type="button" class="btn btn-primary mb-2 ml-2 float-right" data-toggle="modal" data-target="#agregar_firma">Firmar Solicitud</button>
                                @endif
                                <a href="/vehiculos/print/mantenimiento/{{ $mantenimiento->id }}" target="_blank" class="btn btn-success mb-2 ml-2 float-right">Reporte PDF</a>

                                @if (session()->has('update') && session('update') == 1)
                                    <div class="alert alert-success">
                                        Vehiculo actualizado correctamente
                                    </div>
                                @endif

                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center table-bg-dark">
                                            <th colspan="6">Vehiculo <b>{{ $mantenimiento->vehiculo->placa }} - {{ $mantenimiento->estado }}</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="table-bg-dark"><b>Vehículo</b></td>
                                            <td>{{ $mantenimiento->vehiculo->placa }}</td>
                                            <td class="table-bg-dark"><b>Encargado del proceso</b></td>
                                            <td>{{ $mantenimiento->personal->nombres }} {{ $mantenimiento->personal->primer_apellido }}</td>
                                            <td class="table-bg-dark"><b>Fecha y hora solicitud</b></td>
                                            <td>{{ $mantenimiento->fecha }}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Autoriza</b></td>
                                            <td>{{ $mantenimiento->persona_autoriza ?? 'N/A' }}</td>
                                            <td class="table-bg-dark"><b>Fecha y hora autorización</b></td>
                                            <td>{{ $mantenimiento->fecha_autorizacion ?? 'N/A' }}</td>
                                            <td class="table-bg-dark"><b>Observaciones</b></td>
                                            <td>{{ $mantenimiento->observaciones_autorizacion ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Contabilidad</b></td>
                                            <td>{{ $mantenimiento->persona_contabilidad ?? 'N/A' }}</td>
                                            <td class="table-bg-dark"><b>Fecha y hora contabilidad</b></td>
                                            <td>{{ $mantenimiento->fecha_contabilidad ?? 'N/A' }}</td>
                                            <td class="table-bg-dark"><b>Observaciones Contabilidad</b></td>
                                            <td>{{ $mantenimiento->observaciones_contabilidad ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center table-bg-dark">
                                            <th colspan="3"><b>Factura(s) del mantenimiento</b></th>
                                        </tr>
                                        <tr class="text-center table-bg-dark">
                                            <th>Proveedor</th>
                                            <th>Valor Factura</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $total = 0; ?>
                                        @foreach ($mantenimiento->facturas as $factura)
                                            <tr>
                                                <td>{{ $factura->proveedor }}</td>
                                                <td>{{ $factura->valor }}</td>
                                                <td class="text-center"><button type="button" class="btn btn-info"><i class="fa fa-eye" onclick="mostrar_imagen('{{ $factura->factura_imagen }}')"></i></button></td>
                                            </tr>
                                            <?php $total = $total + $factura->valor; ?>
                                        @endforeach
                                        <tr>
                                            <td class="table-bg-dark"><b>Valor Total</b></td>
                                            <td class="table-bg-dark"><b>${{ number_format($total) }}</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <hr class="w-100">

                            <div class="table-responsive mb-3">

                                <button type="button" class="btn btn-info mb-2 float-right" data-toggle="modal" data-target="#agregar_actividad"> <b>+</b> Agregar Actividad</button>

                                @if (session()->has('update') && session('update') == 1)
                                    <div class="alert alert-success">
                                        Vehiculo actualizado correctamente
                                    </div>
                                @endif

                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center table-bg-dark">
                                            <th>Fecha</th>
                                            <th>Tipo Mantenimiento</th>
                                            <th>Observación</th>
                                            <th>Actividad(es)</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mantenimiento->actividades as $actividad)
                                            <tr>
                                                <td>{{ $actividad->fecha }}</td>
                                                <td>{{ $actividad->tipo }}</td>
                                                <td>{{ $actividad->observaciones }}</td>
                                                <td>
                                                    @foreach ($actividad->detalle_actividades as $detalle)
                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class="w-100">{{ $detalle->descripcion }}</td>
                                                                <td><button type="button" class="btn btn-info"><i class="fa fa-eye" onclick="mostrar_imagen('{{ $detalle->imagen_soporte }}')"></i></button></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#agregar_detalle_actividad" onclick="document.getElementById('id_actividad').value = {{ $actividad->id }}"><i class="fa fa-plus"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
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

{{-- MODAL AGREGAR ACTIVIDAD --}}
<div class="modal fade bs-example-modal-xl" id="agregar_actividad" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Activida Mantenimiento Vehículos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/vehiculos/mantenimiento/agregar_actividad" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="fecha">Fecha de la actividad</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" id="fecha" name="fecha" required="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="tipo">Tipo mantenimiento.</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo" id="tipo" class="form-control" required>
                                        <option value="">Seleccione</option>
                                        <option value="Preventivo">Preventivo</option>
                                        <option value="Correctivo">Correctivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12" id="fecha_inicio_vigencia_div">
                                <label for="fecha_inicio_vigencia">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" rows="5" class="form-control mb-4" required></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12" id="fecha_inicio_vigencia_div">
                                <label for="descripcion">Descripcion actividad</label>
                                <textarea name="descripcion" id="descripcion" rows="3" class="form-control mb-4" required></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="imagen_soporte">Agregar Adjunto</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="file" class="form-control" name="imagen_soporte" id="imagen_soporte" required>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id" id="id" value="{{ $mantenimiento->id }}">

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn-submit-correo" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL AGREGAR DETALLE DE ACTIVIDAD --}}
<div class="modal fade bs-example-modal-xl" id="agregar_detalle_actividad" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Activida Mantenimiento Vehículos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/vehiculos/mantenimiento/agregar_detalle_actividad" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-12" id="fecha_inicio_vigencia_div">
                                <label for="descripcion">Descripcion actividad</label>
                                <textarea name="descripcion" rows="3" class="form-control mb-4" required></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="imagen_soporte">Agregar Adjunto</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="file" class="form-control" name="imagen_soporte" required>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id_actividad" id="id_actividad" value="">

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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal_ver_documento_title">Imagen de actividad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_ver_documento_content">

            </div>
        </div>
    </div>
</div>

{{-- MODAL AGREGAR FACTURA --}}
<div class="modal fade bs-example-modal-xl" id="agregar_factura" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Factura Mantenimiento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/vehiculos/mantenimiento/agregar_facruta" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="proveedor">Proveedor</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="proveedor" id="proveedor" class="form-control" required>
                                        <option value="">Seleccione</option>
                                        <option value="INVERIONES FLOTA HUILA SA">INVERIONES FLOTA HUILA SA</option>
                                        <option value="MASSER S.A.S">MASSER S.A.S</option>
                                        <option value="TECNOSUR LOCALIZACIÓN Y RASTREO ">TECNOSUR LOCALIZACIÓN Y RASTREO </option>
                                        <option value="JAIME ALONSO BARRIOS">JAIME ALONSO BARRIOS</option>

                                        <option value="DILLANCOL S.A">DILLANCOL S.A</option>
                                        <option value="MONTALLANTAS CARE PAPA 2">MONTALLANTAS CARE PAPA 2</option>
                                        <option value="MONTALLANTAS LA 15">MONTALLANTAS LA 15</option>
                                        <option value="CALAIRES PAC">CALAIRES PAC</option>
                                        <option value="MARIA EDILMA CRUZ VARGAS">MARIA EDILMA CRUZ VARGAS</option>
                                        <option value="TORNI HERRAMIENTAS DEL SUR">TORNI HERRAMIENTAS DEL SUR</option>
                                        <option value="SERVICIO EL PAJARO">SERVICIO EL PAJARO</option>
                                        <option value="RADIADORES AUTORODAJES">RADIADORES AUTORODAJES</option>
                                        <option value="TALLER ELECTRICO PITUFO">TALLER ELECTRICO PITUFO</option>
                                        <option value="CENTRO DE SERVICIO AUTOMOTRIZ GASCA SU TALLER AMIGO">CENTRO DE SERVICIO AUTOMOTRIZ GASCA SU TALLER AMIGO</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="valor">Valor total de la factura ($)</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" id="valor" name="valor" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="factura_imagen">Agregar Adjunto</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="file" class="form-control" name="factura_imagen" required>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="mantenimientos_id" id="mantenimientos_id" value="{{ $mantenimiento->id }}">

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn-submit-correo" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL AGREGAR FIRMA --}}
<div class="modal fade bs-example-modal-xl" id="agregar_firma" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Activida Mantenimiento Vehículos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/vehiculos/mantenimiento/agregar_firma" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="persona_firma">Persona que firma</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" name="persona_firma" id="persona_firma" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                    {{-- <select name="persona_firma" class="form-control" id="persona_firma" required>
                                        <option value="">Seleccione</option>
                                        @foreach (\App\Models\Personal::all() as $persona)
                                            <option value="{{ $persona->nombres }} {{ $persona->primer_apellido }}">{{ $persona->nombres }} {{ $persona->primer_apellido }}</option>
                                        @endforeach
                                    </select> --}}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="tipo">Tipo Firma</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo" class="form-control" required>
                                        <option value="">Seleccione</option>
                                        <?php echo $mantenimiento->persona_autoriza ? '' : '<option value="Autorizar">Autorizar</option>'; ?>
                                        <?php echo $mantenimiento->persona_autoriza ? '<option value="Contabilidad">Contabilidad</option>' : ''; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="observaciones">Observaciones</label>
                                <textarea name="observaciones" rows="5" class="form-control mb-4" required></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="mantenimientos_id_firma" id="mantenimientos_id_firma" value="{{ $mantenimiento->id }}">

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
