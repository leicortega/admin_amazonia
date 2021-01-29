@section('title') Ver Inspeccion @endsection

@section('Plugins')
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
    <script src="{{ asset('assets/libs/selectize/js/standalone/selectize.min.js') }}"></script>
    <script src="{{ asset('assets/js/inspecciones.js') }}"></script>
@endsection

@section('jsMain') <script>
    function change_select_elemento(option) {
        if (option == 'Otro') {
            $('#elemento_otro').html(`
                <div class="col-sm-12">
                    <label for="observaciones">Elemento</label>
                    <input type="text" class="form-control mb-3" name="input_elemento" id="input_elemento" required>
                </div>
            `);
        } else {
            $('#elemento_otro').html(``);
        }
    }
</script> @endsection

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
                                <a href="{{ route('inspecciones') }}"><button type="button" class="btn btn-dark btn-lg mb-2" onclick="cargarbtn(this)">Atras</button></a>

                                

                                <div class="container-fluid">


                                    <div class="row p-0">
                                        <div class="col-sm-4 mb-3">
                                            <h4>Reporto: {{ $inspeccion->users->name }}</h4>
                                        </div>
                                        <div class="col-sm-8 mb-3 text-right">
                                            <button class="btn btn-primary" data-toggle="modal" onclick="cargarbtnmodal(this, '#plantilla_certificado_inspeccion', 'Plantilla Certificado')">Plantilla Certificado</button>
                                            <button class="btn btn-primary" data-toggle="modal" onclick="cargarbtnmodal(this, '#certificado_inspeccion', 'Certificado')">Certificado</button>
                                            @if ($inspeccion->kilometraje_final == NULL)
                                                <button class="btn btn-primary" data-toggle="modal"  onclick="cargarbtnmodal(this, '#cerrar_inspeccion', 'Cerrar')">Cerrar</button>
                                            @endif
                                            <a href="/vehiculos/inspecciones/pdf/{{ $inspeccion->id }}" target="_blank" class="btn btn-primary" onclick="cargarbtnmodal(this, '#shsj', 'PDF')">PDF</a>
                                            @if ($inspeccion->kilometraje_final == NULL)
                                                <button class="btn btn-primary" data-toggle="modal" onclick="cargarbtnmodal(this, '#agg_adjunto', 'Agregar Adjunto')">Agregar Adjunto</button>
                                            @endif
                                        </div>

                                        <table class="table table-bordered">
                                            <thead class="table-bg-dark">
                                                <tr>
                                                    <th colspan="3" class="text-center"><b>DATOS DE INICIO</b></th>
                                                </tr>
                                                <tr>
                                                    <th>Fecha inicio</th>
                                                    <th>Kilometraje inicio</th>
                                                    <th>Observaciones inicio</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ Carbon\Carbon::parse($inspeccion->fecha_inicio)->format('d-m-Y H:m:s') }}</td>
                                                    <td>{{ $inspeccion->kilometraje_inicio }}</td>
                                                    <td>{{ $inspeccion->observaciones_inicio }}</td>
                                                </tr>
                                            </tbody>
                                            <thead class="table-bg-dark">
                                                <tr>
                                                    <th colspan="3" class="text-center"><b>DATOS DE FINAL</b></th>
                                                </tr>
                                                <tr>
                                                    <th>Fecha final</th>
                                                    <th>Kilometraje final</th>
                                                    <th>Observaciones final</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ ($inspeccion->fecha_final == '') ? 'N/A' : Carbon\Carbon::parse($inspeccion->fecha_final)->format('d-m-Y H:m:s') }}</td>
                                                    <td>{{ $inspeccion->kilometraje_final ?? 'N/A' }}</td>
                                                    <td>{{ $inspeccion->observaciones_final ?? 'N/A' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>


                                        <table class="table table-bordered">
                                            <thead class="table-bg-dark">
                                                <tr>
                                                    <th>Elemento</th>
                                                    <th>Calificacion</th>
                                                    <th>Elemento</th>
                                                    <th>Calificacion</th>
                                                    <th>Elemento</th>
                                                    <th>Calificacion</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for ($i = 0; $i < $inspeccion->detalle->count(); $i++)
                                                    <tr>
                                                        @for ($j = $i; $j <= $i+2; $j++)
                                                            <td>{{ $inspeccion->detalle[$j]->campo }}</td>
                                                            <td>{{ $inspeccion->detalle[$j]->estado }}</td>
                                                        @endfor
                                                        <?php $i = $i + 2 ?>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>

                                        <table class="table table-bordered">
                                            <thead class="table-bg-dark">
                                                <tr>
                                                    <th>Elemento</th>
                                                    <th>Observaciones</th>
                                                    <th>Adjunto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($inspeccion->adjuntos as $adjunto)
                                                    <tr>
                                                        <td>{{ $adjunto->elemento }}</td>
                                                        <td>{{ $adjunto->observaciones }}</td>
                                                        <td><iframe src="/storage/{{ $adjunto->adjunto }}" width="100%" height="250px" frameborder="0"></iframe></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
<div class="modal fade bs-example-modal-xl" id="agg_adjunto" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Agregar adjunto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/vehiculos/inspecciones/agregar_adjunto" method="POST" enctype="multipart/form-data" onsubmit="cargarbtn('#btn_envia_adutn')">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="elemento">Elemento</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="elemento" id="elemento" class="form-control" onchange="change_select_elemento(this.value)" required>
                                        <option value="">Seleccione</option>
                                        @foreach (\App\Models\Sistema\Admin_inspeccion::all() as $item)
                                            <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                                        @endforeach>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="elemento_otro">

                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="observaciones">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" class="form-control form-group-custom mb-4" rows="7" placeholder="Escriba las observaciones" required></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="adjunto">Agregar Adjunto</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="file" class="form-control" name="adjunto" id="adjunto" required>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="inspeccion_id" value="{{ $inspeccion->id }}">

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light"  id="btn_envia_adutn" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- AGREGAR CERRAR INSPECCION --}}
<div class="modal fade bs-example-modal-xl" id="cerrar_inspeccion" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Cerrar inspeccion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/vehiculos/inspecciones/cerrar" method="POST" onsubmit="cargarbtn('#cerrarinspecbtn')">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="kilometraje_final">Kilometraje</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" name="kilometraje_final" id="kilometraje_final" class="form-control" required placeholder="Escriba el kilometraje" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="observaciones_final">Observaciones</label>
                                <textarea name="observaciones_final" id="observaciones_final" class="form-control form-group-custom mb-4" rows="7" placeholder="Escriba las observaciones" required></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="inspeccion_id" value="{{ $inspeccion->id }}">

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="cerrarinspecbtn" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- PLANTILLA CERTIFICADO INSPECCION --}}
<div class="modal fade bs-example-modal-xl" id="plantilla_certificado_inspeccion" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Certificado inspeccion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/vehiculos/inspecciones/certificado" method="POST" onsubmit="cargarbtn('#btn_submit_cert_isp')">
                    @csrf

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" id="textarea-correo">
                                    <h4 class="header-title text-center">Contenido</h4>

                                    <textarea class="elm1" name="area" class="text-white" style="min-height: 750px">
                                        <p>Neiva,&nbsp; {{ \Carbon\Carbon::now('America/Bogota')->format('d') }} de {{ \Carbon\Carbon::now('America/Bogota')->formatLocalized('%B') }} {{ \Carbon\Carbon::now('America/Bogota')->format('Y') }}</p>
                                        <p>&nbsp;</p>
                                        <p><strong>{{ \App\Models\Personal::find($inspeccion->vehiculo->personal_id)->nombres }} {{ \App\Models\Personal::find($inspeccion->vehiculo->personal_id)->primer_apellido }} {{ \App\Models\Personal::find($inspeccion->vehiculo->personal_id)->segundo_apellido ?? '' }}</strong></p>
                                        <p>Propietario veh&iacute;culo {{ $inspeccion->vehiculo->placa }}</p>
                                        <p>Ciudad</p>
                                        <p>&nbsp;</p>
                                        <p>Asunto: Plan de acci&oacute;n hallazgos de inspecci&oacute;n</p>
                                        <p>Cordial saludo</p>
                                        <p>&nbsp;</p>
                                        <p>Por medio de la presente me permito informar sobre los hallazgos encontrados en la inspecci&oacute;n del d&iacute;a {{ \Carbon\Carbon::createFromDate($inspeccion->fecha_inicio)->format('d') }} de {{ \Carbon\Carbon::createFromDate($inspeccion->fecha_inicio)->formatLocalized('%B') }} de {{ \Carbon\Carbon::createFromDate($inspeccion->fecha_inicio)->format('Y') }}:</p>
                                        <ul>
                                            @foreach ($novedad as $item)
                                                <li>{{ $item['elemento'] }} en estado {{ $item['estado'] }}</li>
                                            @endforeach
                                        </ul>
                                        <p>Teniendo en cuenta lo anterior, me permito solicitar amablemente su colaboraci&oacute;n para realizar la respectiva acci&oacute;n frente a las evidencias halladas</p>
                                        <p>Plan de acci&oacute;n</p>
                                        <table width="525" border="1">
                                            <tbody>
                                                <tr>
                                                    <td width="80">
                                                        <p style="margin: 0px;padding: 0px;"><strong> ITEM </strong></p>
                                                    </td>
                                                    <td width="350">
                                                        <p style="margin: 0px;padding: 0px;"><strong> PLAN DE ACCIÓN</strong></p>
                                                    </td>
                                                    <td width="95">
                                                        <p style="margin: 0px;padding: 0px;"><strong> FECHA </strong></p>
                                                    </td>
                                                </tr>
                                                @foreach ($novedad as $key => $item)
                                                    <tr>
                                                        <td width="80">
                                                            <p style="margin: 0px;padding: 0px;"><strong> {{ $key + 1 }}</strong></p>
                                                        </td>
                                                        <td>
                                                            <p style="margin: 0px;padding: 0px;"><strong> {{ $item['elemento'] }}</strong></p>
                                                        </td>
                                                        <td>
                                                            <p style="margin: 0px;padding: 0px;"><strong> INMEDIATO </strong></p>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <p>Agradezco remitir soporte de factura de los procedimientos realizados, con el objetivo de dar aprobaci&oacute;n de rodamiento del veh&iacute;culo y dar cumplimiento a nuestro sistema de&nbsp; gesti&oacute;n</p>
                                        <p>&nbsp;</p>
                                        <p>Gracias por la atenci&oacute;n</p>
                                        <p>&nbsp;</p>
                                        <p>Cordialmente</p>
                                        <p><img src="https://admin.amazoniacl.com/assets/images/firma.png" alt="" width="237" height="113" /></p>
                                        <p><strong>NATHALIE CASTRO TENGON&Oacute; </strong></p>
                                        <p>Coordinadora HSEQ</p>
                                    </textarea>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>

                    <input type="hidden" name="inspeccion_id" value="{{ $inspeccion->id }}"/>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn_submit_cert_isp" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- CERTIFICADO INSPECCION --}}
<div class="modal fade bs-example-modal-xl" id="certificado_inspeccion" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Certificado inspeccion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/vehiculos/inspecciones/certificado" method="POST" onsubmit="cargarbtn('#btn_certificado_inps')">
                    @csrf

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" id="textarea-correo">
                                    <h4 class="header-title text-center">Contenido</h4>

                                    <textarea class="elm1" name="area" class="text-white" style="min-height: 750px">
                                        {{ $inspeccion->certificado ?? 'Aun no ha generado un certificado' }}
                                    </textarea>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>

                    <input type="hidden" name="inspeccion_id" value="{{ $inspeccion->id }}"/>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn_certificado_inps" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
