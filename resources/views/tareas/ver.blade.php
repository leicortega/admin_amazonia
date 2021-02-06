@section('title') Ver Tarea @endsection

@extends('layouts.app')

@section('jsMain') <script src="{{ asset('assets/js/tareas.js') }}"></script> @endsection

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

                                @if (session()->has('create'))
                                    <div class="alert {{ (session()->has('create') == 1) ? 'alert-success' : 'alert-danger' }}">
                                        {{ session('mensaje') }}
                                    </div>
                                @endif

                                <div class="container-fluid">
                                    <div class="row p-0">
                                        <div class="col-sm-6 mb-3">
                                            <a href="/tareas"><button type="button" class="btn btn-dark btn-lg mb-2">Atras</button></a>
                                        </div>
                                        <div class="col-sm-6 mt-3 text-right">
                                            <h4>Asignada por: {{ $tarea->supervisor_id->name }}</h4>
                                        </div>

                                        <table class="table table-bordered">
                                            <thead class="table-bg-dark">
                                                <tr>
                                                    <th colspan="4" class="text-center"><b>DATOS DE TAREA ({{$tarea->name_tarea}})</b></th>
                                                </tr>
                                                <tr>
                                                    <th>Fecha asignada</th>
                                                    <th>Responsable</th>
                                                    <th>Estado</th>
                                                    <th>Fecha limite</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{  Carbon\Carbon::parse($tarea->fecha)->format('d-m-Y') }}</td>
                                                    <td>{{ $tarea->asignado_id->name }}</td>
                                                    <td>{{ $tarea->estado }}</td>
                                                    <td>{{ Carbon\Carbon::parse($tarea->fecha_limite)->format('d-m-Y') }}</td>
                                                </tr>
                                            </tbody>
                                            <thead class="table-bg-dark">
                                                <tr>
                                                    <th colspan="3" class="text-center">Tarea</th>
                                                    <th colspan="1" class="text-center">Adjunto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="3">{{ $tarea->tarea }}</td>
                                                    <td colspan="1" class="text-center">
                                                        @if ($tarea->adjunto)
                                                            <button type="button" class="btn btn-success btn-lg"  onclick="ver_adjunto('{{ $tarea->adjunto }}')">Ver adjunto</button>
                                                        @else
                                                            No Hay Adjuntos.
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>


                                        <div class="col-sm-12 mt-3 mb-2 text-right">
                                            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modal_agregar_estado">Agregar estado</i></button>
                                        </div>
                                        <table class="table table-bordered">
                                            <thead class="table-bg-dark">
                                                <tr>
                                                    <th colspan="5" class="text-center"><b>HISTORIAL DE LA TAREA</b></th>
                                                </tr>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Usuario</th>
                                                    <th>Estado</th>
                                                    <th>Observaciones</th>
                                                    <th>Ajunto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($tarea->detalle_tareas as $detalle)
                                                    <tr>
                                                        <td>{{  Carbon\Carbon::parse($detalle->fecha)->format('d-m-Y H:m:s') }}</td>
                                                        <td>{{ $detalle->user->name }}</td>
                                                        <td>{{ $detalle->estado }}</td>
                                                        <td>{{ $detalle->observaciones }}</td>
                                                        <td class="text-center">
                                                            @if ($detalle->adjunto)
                                                                <button class="btn btn-info" onclick="ver_adjunto('{{ $detalle->adjunto }}')"><i class="fa fa-eye"></i></button>
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
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

{{-- Modal Asignar Estado --}}
<div class="modal fade bs-example-modal-lg" id="modal_agregar_estado" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-correo">Agregar estado a tarea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/tareas/agregar_estado" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-12 mb-4">
                                <label for="observaciones">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" rows="10" class="form-control" required placeholder="Escriba la descripcion de la tarea"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="estado" class="form-control" id="estado" required>
                                        <option value=""></option>
                                        <option value="En proceso">En proceso</option>
                                        <option value="Con retrasos">Con retrasos</option>
                                        <option value="Por revision">Por revision</option>
                                        <option value="Incompleta">Incompleta</option>
                                        <option value="Completada">Completada</option>
                                    </select>
                                    <label for="estado">Estado</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="file" class="form-control" id="adjunto" name="adjunto">
                                    <label for="adjunto">Adjunto (opcional)</label>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="tarea_id" id="tarea_id" value="{{ $tarea->id }}">
                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ver Adjunto --}}
<div class="modal fade bs-example-modal-xl" id="modal_ver_adjunto" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-correo">Adjunto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe src="" id="ver_adjunto" width="100%" height="720px" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection







