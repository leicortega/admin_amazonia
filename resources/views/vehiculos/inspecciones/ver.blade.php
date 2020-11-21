@section('title') Ver Inspeccion @endsection

@section('Plugins')
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
    <script src="{{ asset('assets/libs/selectize/js/standalone/selectize.min.js') }}"></script>
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

                                <div class="container-fluid">


                                    <div class="row p-0">
                                        <div class="col-sm-4 mb-3">
                                            <h4>Reporto: {{ $inspeccion->users->name }}</h4>
                                        </div>
                                        <div class="col-sm-8 mb-3 text-right">
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#certificado_inspeccion">Certificado</button>
                                            @if ($inspeccion->kilometraje_final == NULL)
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#cerrar_inspeccion">Cerrar</button>
                                            @endif
                                            <a href="/vehiculos/inspecciones/pdf/{{ $inspeccion->id }}" target="_blank" class="btn btn-primary" >PDF</a>
                                            @if ($inspeccion->kilometraje_final == NULL)
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#agg_adjunto">Agregar Adjunto</button>
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
                                                    <td>{{ $inspeccion->fecha_inicio }}</td>
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
                                                    <td>{{ $inspeccion->fecha_final ?? 'N/A' }}</td>
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

                <form action="/vehiculos/inspecciones/agregar_adjunto" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="elemento">Elemento</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="elemento" id="elemento" class="form-control" required>
                                        <option value="">Seleccione</option>
                                        @foreach (\App\Models\Sistema\Admin_inspeccion::all() as $item)
                                            <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                                        @endforeach>
                                    </select>
                                </div>
                            </div>
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
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- AGREGAR AGREGAR DOCUMENTO --}}
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

                <form action="/vehiculos/inspecciones/cerrar" method="POST">
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
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
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

                <form action="/vehiculos/inspecciones/certificado" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" id="textarea-correo">
                                    <h4 class="header-title text-center">Contenido</h4>

                                    <textarea id="elm1" name="area" class="text-white" style="min-height: 750px">
                                        <p>Puerto as&iacute;s, 13 de febrero de 2020</p>
                                        <p>&nbsp;</p>
                                        <p><br />Se&ntilde;ores<br />___________________<br />Nit:_____________<br />{Direccion}<br />{Ciudad}</p>
                                        <p>&nbsp;</p>
                                        <p><br />Ref: Hallazgos de mantenimiento</p>
                                        <p><br />Reciba un cordial saludo.<br />Por medio de la presente, me permito informar sobre los hallazgos encontrados en inspecciones anteriores de parte del &aacute;rea de mantenimiento en donde se encontraron los siguientes hallazgos</p>
                                        <ul>
                                            @foreach ($novedad as $item)
                                                <li>{{ $item['elemento'] }} en estado {{ $item['estado'] }}</li>
                                            @endforeach
                                        </ul>
                                        <p>En vista de estos acontecimientos el cliente opto por inutilizar el veh&iacute;culo hasta cerrar dichos hallazgos, ya que, el servicio especial que ellos contratan exigen el servicio de aire acondicionado por las condiciones clim&aacute;ticas del terreno. Por tal motivo el &aacute;rea de operaciones y antenimiento toma la decisi&oacute;n de que el veh&iacute;culo sea arreglado inmediatamente.</p>
                                        <p><br />Agradezco su atenci&oacute;n prestada y su colaboraci&oacute;n oportuna.<br />Cordialmente,</p>
                                        <p>&nbsp;</p>
                                        <p><br />______________________________<br />{{ auth()->user()->name }}<br /></p>
                                    </textarea>
                                </div>
                            </div>
                        </div> <!-- end col -->
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
