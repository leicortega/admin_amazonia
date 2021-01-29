@section('title') Administrar Inspecciones @endsection

@extends('layouts.app')

@section('jsMain')
    <script src="{{ asset('assets/js/admin.js') }}"></script>
@endsection

@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="header-title mb-3">Administrar Inspecciones</h4>

                        @if (session()->has('create') && session('create') == 1)
                            <div class="alert alert-primary" role="alert">
                                Dato creado correctamente
                            </div>
                        @endif

                        @if (session()->has('create') && session('create') == 0)
                            <div class="alert alert-danger" role="alert">
                                Ocurrio un error, intente de nuevo
                            </div>
                        @endif

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#Generalidades" role="tab">
                                    <span class="d-none d-md-inline-block">Generalidades</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#Botiquin" role="tab">
                                    <span class="d-none d-md-inline-block">Botiquin</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#Luces_y_estado_mecanico" role="tab">
                                    <span class="d-none d-md-inline-block">Luces y estado mecanico</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#Equipos_de_carretera" role="tab">
                                    <span class="d-none d-md-inline-block">Equipos de carretera</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3">
                            <div class="tab-pane active" id="Generalidades" role="tabpanel">
                                <button type="button" onclick="datos_inspecciones('Generalidades')" class="btn btn-primary my-3 btn-lg">Agregar +</button>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Cantidad</th>
                                            <th>Vigencia</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($generalidades as $generalidades_item)
                                            <tr>
                                                <td scope="row">{{ $generalidades_item->nombre }}</td>
                                                <td scope="row">{{ $generalidades_item->cantidad }}</td>
                                                <td scope="row">{{ $generalidades_item->vigencia }}</td>
                                                <td><button type="button" onclick="editar_datos_vehiculo({{ $generalidades_item->id }}, 'Generalidades')" class="btn btn-primary"><i class="fas fa-edit"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="Botiquin" role="tabpanel">
                                <button type="button" onclick="datos_inspecciones('Botiquin')" class="btn btn-primary my-3 btn-lg">Agregar +</button>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Cantidad</th>
                                            <th>Vigencia</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($botiquin as $botiquin_item)
                                            <tr>
                                                <td scope="row">{{ $botiquin_item->nombre }}</td>
                                                <td scope="row">{{ $botiquin_item->cantidad }}</td>
                                                <td scope="row">{{ $botiquin_item->vigencia }}</td>
                                                <td><button type="button" onclick="editar_datos_vehiculo({{ $botiquin_item->id }}, 'Botiquin')" class="btn btn-primary"><i class="fas fa-edit"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="Luces_y_estado_mecanico" role="tabpanel">
                                <button type="button" onclick="datos_inspecciones('Luces y estado mecanico')" class="btn btn-primary my-3 btn-lg">Agregar +</button>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Cantidad</th>
                                            <th>Vigencia</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($luces as $luces_item)
                                            <tr>
                                                <td scope="row">{{ $luces_item->nombre }}</td>
                                                <td scope="row">{{ $luces_item->cantidad }}</td>
                                                <td scope="row">{{ $luces_item->vigencia }}</td>
                                                <td><button type="button" onclick="editar_datos_vehiculo({{ $luces_item->id }}, 'Luces y estado mecanico')" class="btn btn-primary"><i class="fas fa-edit"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="Equipos_de_carretera" role="tabpanel">
                                <button type="button" onclick="datos_inspecciones('Equipos de carretera')" class="btn btn-primary my-3 btn-lg">Agregar +</button>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Cantidad</th>
                                            <th>Vigencia</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($equipos as $equipos_item)
                                            <tr>
                                                <td scope="row">{{ $equipos_item->nombre }}</td>
                                                <td scope="row">{{ $equipos_item->cantidad }}</td>
                                                <td scope="row">{{ $equipos_item->vigencia }}</td>
                                                <td><button type="button" onclick="editar_datos_vehiculo({{ $equipos_item->id }}, 'Equipos de carretera')" class="btn btn-primary"><i class="fas fa-edit"></i></button></td>
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

<div class="modal fade bs-example-modal-lg" id="modal-create-datos-inspecciones" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-create-datos-inspecciones-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/admin/sistema/inspecciones/agg_admin_inspeccion" method="POST" onsubmit="cargarbtn('#btn_crear_datos_ins')">
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Clasificacion</label>
                        <div class="col-sm-10">
                            <select name="tipo" id="tipo" class="form-control">
                                <option value="Generalidades">Generalidades</option>
                                <option value="Botiquin">Botiquin</option>
                                <option value="Luces y estado mecanico">Luces y estado mecanico</option>
                                <option value="Equipos de carretera">Equipos de carretera</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="nombre" placeholder="Escriba el nombre" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Cantidad</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="cantidad" placeholder="Escriba la cantidad" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Vigencia (Vencimiento)</label>
                        <div class="col-sm-10">
                            <select name="vigencia" id="vigencia" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="Si Aplica Vigencia">Si Aplica Vigencia</option>
                                <option value="No Aplica Vigencia">No Aplica Vigencia</option>
                            </select>
                        </div>
                    </div>

                    {{-- <input type="hidden" name="tipo" id="datos_inspecciones_tipo"> --}}

                    <div class="mt-4 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn_crear_datos_ins" type="submit">Agregar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection







