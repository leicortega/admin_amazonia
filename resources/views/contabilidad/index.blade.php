@section('title') Contabilidad @endsection

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

                                @if (session()->has('create'))
                                    <div class="alert {{ (session()->has('create') == 1) ? 'alert-success' : 'alert-danger' }}">
                                        {{ session('mensaje') }}
                                    </div>
                                @endif
                                <a href="{{ url()->previous() }}"><button type="button" class="btn btn-dark btn-lg mb-2">Atras</button></a>

                                <button type="button" class="btn btn-primary btn-lg float-right mb-2" data-toggle="modal" data-target="#modal_agregar_registro">Agregar +</button>

                                <table class="table table-centered table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th colspan="12" class="text-center">
                                                <div class="d-inline-block icons-sm mr-2"><i class="uim fas fa-business-time"></i></div>
                                                <span class="header-title mt-2">Registros Contables</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Responsable</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Concepto</th>
                                            <th scope="col">Por Pagar</th>
                                            <th scope="col">Por Cobrar</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($registros as $tarea)
                                            <tr>
                                                <th>{{ $tarea->asignado_id->name }}</th>
                                                <td>{{ $tarea->fecha }}</td>
                                                <td>{{ $tarea->estado }}</td>
                                                <td>{{ $tarea->fecha_limite }}</td>
                                                <td>{{ $tarea->fecha_limite }}</td>
                                                <td class="text-center">
                                                    <a href="/tareas/ver/{{ $tarea->id }}"><button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Tarea">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button></a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                            {{ $registros->links() }}

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

{{-- Modal Agregar Registro Contable --}}
<div class="modal fade bs-example-modal-lg" id="modal_agregar_registro" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-correo">Agregar Registro Contable</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/tareas/agregar" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="asignado" class="form-control" id="asignado" required>
                                        <option value=""></option>
                                        @foreach (\App\User::all() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="asignado">Agregar Registro Contable a</label>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-4">
                                <label for="tarea">Descripcion</label>
                                <textarea name="tarea" id="tarea" rows="10" class="form-control" required placeholder="Escriba la descripcion de la tarea"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" data-language="es" data-date-format="yyyy-mm-dd" id="fecha_limite" name="fecha_limite" required>
                                    <label for="fecha_limite">Fecha limite</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="file" class="form-control" id="adjunto" name="adjunto">
                                    <label for="adjunto">Adjunto (opcional)</label>
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
@endsection







