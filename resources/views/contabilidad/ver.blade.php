@section('title') Ver Tarea @endsection

@extends('layouts.app')

@section('jsMain') <script src="{{ asset('assets/js/contabilidad.js') }}"></script> @endsection

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
                                            <a href="/contabilidad"><button type="button" class="btn btn-dark btn-lg mb-2">Atras</button></a>
                                        </div>
                                        <div class="col-sm-6 mt-3 text-right">
                                            {{-- <h4>Asignada por: {{ $tarea->supervisor_id->name }}</h4> --}}
                                        </div>

                                        <table class="table table-bordered">
                                            <thead class="table-bg-dark">
                                                <tr>
                                                    <th colspan="4" class="text-center"><b>ESTADO DE CUENTA</b></th>
                                                </tr>
                                                <tr>
                                                    <th>Total Por Cobrar</th>
                                                    <th>Total Por Pagar</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>${{ number_format($total_por_cobrar) }}</td>
                                                    <td>${{ number_format($total_por_pagar) }}</td>
                                                    <td><b>{{ $estado }} ${{ number_format(abs($estado_cuenta)) }}<b></td>
                                                </tr>
                                            </tbody>
                                        </table>


                                        <div class="col-sm-12 mt-3 mb-2 text-right">
                                            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modal_agregar_registro">Agregar registro</i></button>
                                        </div>
                                        <table class="table table-bordered">
                                            <thead class="table-bg-dark">
                                                <tr>
                                                    <th colspan="6" class="text-center"><b>HISTORIAL DE REGISTROS</b></th>
                                                </tr>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Concepto</th>
                                                    <th>Tipo</th>
                                                    <th>Estado</th>
                                                    <th>Valor</th>
                                                    <th>Ajunto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($registros as $registro)
                                                    <tr>
                                                        <td>{{ Carbon\Carbon::parse($registro->fecha)->format('d-m-Y') }}</td>
                                                        <td>{{ $registro->concepto }}</td>
                                                        <td>{{ $registro->tipo }}</td>
                                                        <td>{{ $registro->estado }}</td>
                                                        <td>${{ number_format($registro->valor) }}</td>
                                                        <td class="text-center">
                                                            @if ($registro->anexo)
                                                                <button class="btn btn-info" onclick="ver_anexo('{{ $registro->anexo }}')"><i class="fa fa-eye"></i></button>
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

                <form action="/contabilidad/create" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="vehiculos_id" class="form-control" id="vehiculos_id" required>
                                        <option value=""></option>
                                        @foreach (\App\Models\Vehiculo::all() as $vehiculo)
                                            <option value="{{ $vehiculo->id }}" {{ $vehiculo->id == $id ? 'selected' : '' }}>{{ $vehiculo->placa }}</option>
                                        @endforeach
                                    </select>
                                    <label for="vehiculos_id">Agregar Registro Contable a</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="file" class="form-control" id="anexo" name="anexo" />
                                    <label for="anexo">Anexo</label>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-4">
                                <label for="concepto">Concepto</label>
                                <textarea name="concepto" id="concepto" rows="5" class="form-control" required placeholder="Escriba el concepto del registro"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo" class="form-control" id="tipo" required>
                                        <option value=""></option>
                                        <option value="Por pagar">Por pagar</option>
                                        <option value="Por cobrar">Por cobrar</option>
                                    </select>
                                    <label for="tipo">Tipo</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" id="valor" name="valor" placeholder="Escriba el valor">
                                    <label for="valor">Valor</label>
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

{{-- Modal Anexo de registro contable --}}
<div class="modal fade bs-example-modal-lg" id="modal_ver_anexo" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-correo">Anexo de registro contable</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="content_modal_anexo"></div>
            </div>
        </div>
    </div>
</div>
@endsection







