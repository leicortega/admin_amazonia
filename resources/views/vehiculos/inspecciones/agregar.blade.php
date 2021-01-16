@section('title') Agregar Inspeccion @endsection

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

                                <a href="{{ route('inspecciones') }}"><button type="button" class="btn btn-dark btn-lg mb-5">Atras</button></a>


                                <div class="container-fluid">
                                    <form action="/vehiculos/inspecciones/agregar" method="post">
                                        @csrf

                                        <div class="row p-0">
                                            <div class="col-12">
                                                <div class="form-group mb-4">
                                                    <label>Seleccione el vehiculo</label>
                                                    <select class="selectize" name="vehiculo_id" id="vehiculo_id" required>
                                                        <option value="">Seleccione</option>
                                                        @foreach ($vehiculos as $vehiculo)
                                                            <option value="{{ $vehiculo->id }}">{{ $vehiculo->placa }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <label class="col-sm-12 col-form-label">Fecha</label>
                                                <div class="form-group form-group-custom mb-4">
                                                    <input class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" type="text" name="fecha_inicio" id="fecha_inicio" value="{{ \Carbon\Carbon::now('America/Bogota')->format('Y-m-d H:i:s') }}" required/>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="col-sm-12 col-form-label">Kilometraje</label>
                                                <div class="form-group form-group-custom mb-4">
                                                    <input class="form-control" type="number" name="kilometraje_inicio" id="kilometraje_inicio" placeholder="Escriba el kilometraje" required/>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 mb-5">
                                                <label class="col-sm-12 col-form-label">Observaciones</label>
                                                <textarea name="observaciones_inicio" id="observaciones_inicio" rows="5" class="form-control" required placeholder="Escriba las observaciones"></textarea>
                                            </div>

                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Descripción</th>
                                                        <th>Cantidad</th>
                                                        <th>Calificación</th>
                                                        <th>Descripción</th>
                                                        <th>Cantidad</th>
                                                        <th>Calificación</th>
                                                    </tr>
                                                </thead>
                                            </table>

                                            @foreach ($admin_inspecciones as $key => $item)
                                                <div class="col-sm-6 mt-3">
                                                    <div class="form-group row mb-0">
                                                        <label class="col-md-5 col-form-label">{{ $item->nombre }}</label>
                                                        <input type="hidden" name="campo_{{ $key }}" value="{{ $item->nombre }}">
                                                        <input type="hidden" name="id_{{ $key }}" value="{{ $item->id }}">
                                                        <label class="col-md-3 col-form-label">{{ $item->cantidad }}</label>
                                                        <input type="hidden" name="cantidad_{{ $key }}" value="{{ $item->cantidad }}">
                                                        <div class="col-md-4">
                                                            <select class="custom-select" name="estado_{{ $key }}" required>
                                                                <option value="">Seleccione</option>
                                                                <option value="Bueno">Bueno</option>
                                                                <option value="Regular">Regular</option>
                                                                <option value="Malo">Malo</option>
                                                                <option value="No Dispone">No Dispone</option>
                                                                <option value="No Aplica">No Aplica</option>
                                                            </select>
                                                        </div>
                                                        <input type="hidden" name="total" value="{{ $key }}">
                                                    </div>
                                                </div>
                                            @endforeach

                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-primary btn-lg mt-5">Enviar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>
@endsection







