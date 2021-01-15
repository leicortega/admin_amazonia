@section('title') Contabilidad @endsection

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
                                
                                <a href="{{ route('index') }}"><button type="button" class="btn btn-dark btn-lg mb-2">Atras</button></a>

                                {{-- botones de filtro --}}
                                <button type="button" class="btn btn-primary btn-lg ml-2 mb-2" data-toggle="modal" data-target="#modal-filtro">Filtrar <i class="fa fa-filter" aria-hidden="true"></i>
                                </button>


                                @if(request()->routeIs('contabilidad_filtro'))
                                    <a href="{{route('contabilidad')}}" class="btn btn-primary btn-lg mb-2 ml-2">
                                        Limpiar <i class="fa fa-eraser" aria-hidden="true"></i>
                                    </a>
                                @endif
                                {{-- end botones de fitro --}}
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
                                            <th scope="col">Vehiculo</th>
                                            <th scope="col">Propietario</th>
                                            <th scope="col">Estado de cuenta</th>
                                            {{-- <th scope="col">Concepto</th>
                                            <th scope="col">Por Pagar</th>
                                            <th scope="col">Por Cobrar</th> --}}
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vehiculos as $vehiculo)
                                            <tr>
                                                <th>{{ $vehiculo->placa }}</th>
                                                <td>{{ \App\Models\Personal::find($vehiculo->personal_id)->nombres }} {{ \App\Models\Personal::find($vehiculo->personal_id)->primer_apellido }}</td>
                                                <td>0</td>
                                                {{-- <td>{{ $registro->concepto }}</td>
                                                <td>{{ number_format($registro->valor_pagar) }}</td>
                                                <td>{{ number_format($registro->valor_cobrar) }}</td> --}}
                                                <td class="text-center">
                                                    <a href="/contabilidad/ver/{{ $vehiculo->id }}">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver registros">
                                                            <i class="mdi mdi-eye"></i>
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                            {{ $vehiculos->appends(request()->input())->links() }}

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
                                            <option value="{{ $vehiculo->id }}">{{ $vehiculo->placa }}</option>
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
                                    <input type="number" class="form-control" id="valor_pagar" name="valor_pagar" placeholder="Escriba el valor a pagar">
                                    <label for="valor_pagar">Valor a pagar</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" id="valor_cobrar" name="valor_cobrar" placeholder="Escriba el valor a cobrar">
                                    <label for="valor_cobrar">Valor a cobrar</label>
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

{{-- AGREGAR FILTRO --}}
<div class="modal fade bs-example-modal-xl" id="modal-filtro" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-cotizacion">Agregar Filtros</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{route('contabilidad_filtro')}}" id="form-create-tercero" method="GET">
                    @csrf
                    <div class="container">
                        <div class="form-group row">                            
                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-4">
                                    <label class="col-sm-12 col-form-label">Ordenar Por</label>
                                    <select name="ordenarpor" class="form-control">
                                        <option value="">Selecciona </option>
                                        <option value="placa">Placa</option>
                                        <option value="propietario">Propietario</option>
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label class="col-sm-12 col-form-label">Propietario</label>
                                    <select name="propietario" id="propietario" class="form-control">
                                        <option value="">Selecciona</option>
                                        @foreach ($propietarios as $item)
                                            <option value="{{$item->id}}">{{$item->nombres . $item->primer_apellido . $item->segundo_apellido}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    @role('admin')
                                        <div class="form-group mb-4">
                                            <label class="col-form-label">Seleccione placa del vehiculo</label>
                                            <select class="selectize form-control" name="placa">
                                                <option value="">Seleccione</option>
                                                @foreach ($placas as $vehiculo)
                                                    <option value="{{ $vehiculo->id }}">{{ $vehiculo->placa }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endrole
                                </div>

                            </div>
                        </div>

                        <hr>
                        <div class="form-group row">                            
                            <div class="col-sm-12 d-flex">
                                <div class="col-sm-12">
                                    <div class="form-group mb-4">
                                        <label>Buscar</label>
                                        <input type="text" class="form-control" placeholder="Buscar Vehiculo o Propietario" name="search"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="mt-5 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Aplicar Filtros</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection







