@section('title') Administrar Cargos @endsection

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
            
                        <h4 class="header-title mb-3">Cargos</h4>
                        @if (session()->has('create') && session('create') == 1)
                            <div class="alert alert-primary" role="alert">
                                Cargo creado correctamente
                            </div>
                        @endif
                        @if (session()->has('create_doc') && session('create_doc') == 1)
                            <div class="alert alert-primary" role="alert">
                                {{session('mensaje')}}
                            </div>
                        @endif
                        @if (session()->has('error') && session('error') == 1)
                            <div class="alert alert-danger" role="alert">
                                {{session('mensaje')}}
                            </div>
                        @endif
                        <button type="button" data-toggle="modal" data-target="#modal_ver_documentos" class="btn btn-primary my-3 btn-lg float-right">Ver Docs. +</button>
                        
                        <button type="button" data-toggle="modal" data-target="#modal-create-datos-vehiculo" class="btn btn-primary my-3 btn-lg" onclick="agregar_datos_vehiculo()">Agregar +</button>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Funciones</th>
                                    <th>Obligaciones</th>
                                    <th>Documentos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cargos as $cargo)
                                    @php
                                        $documentos = 'N/A';
                                    @endphp
                                    @foreach ($cargo->documentos as $key => $doc)
                                        @if ($key == 0)
                                            @php
                                                $documentos = $doc->nombre;
                                            @endphp
                                        @else
                                            @php
                                                $documentos .= ' - ' . $doc->nombre;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td>{{ $cargo->nombre }}</td>
                                        <td style="font-size: 11px;">{{ $cargo->funciones }}</td>
                                        <td style="font-size: 11px;">{{ $cargo->obligaciones }}</td>
                                        <td style="font-size: 11px;">{{ $documentos }}</td>
                                        <td><button type="button" onclick="editar_datos_vehiculo({{ $cargo }})" class="btn btn-primary"><i class="fas fa-edit"></i></button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $cargos->links() }}

                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<div class="modal fade bs-example-modal-lg" id="modal-create-datos-vehiculo" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-create-datos-vehiculo-title">Agregar Cargo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/admin/sistema/agg_cargo" id="form_agg_cargo" method="POST" onsubmit="cargarbtn('#crear_datos_vehivulo')">
                    @csrf
                
                    <div class="form-group row">
                        <label for="name" class="col-sm-12 col-form-label">Nombre del cargo</label>
                        <div class="col-sm-12">
                            <input class="form-control" type="text" name="nombre" id="nombre_cargo" placeholder="Escriba el nombre" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-12 col-form-label">Funciones del cargo</label>
                        <div class="col-sm-12">
                            <textarea name="funciones" id="funciones_cargo" class="form-control" id="" cols="30" rows="5"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-12 col-form-label">Obligaciones</label>
                        <div class="col-sm-12">
                            <textarea name="obligaciones" id="obligaciones_cargo" class="form-control" id="" cols="30" rows="5"></textarea>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="documentos_cargos" class="col-sm-2 col-form-label">Documentos: </label>
                        <div class="col-sm-10 mt-2">
                            @foreach (\App\Models\Documentos_cargos_admin::all() as $doc)
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input documentos_cargos" id="documentos_cargo{{$doc->id}}" name="documentos_cargos[]" value="{{ $doc->id }}">
                                    <label class="custom-control-label" for="documentos_cargo{{$doc->id }}">{{ $doc->nombre }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <input type="hidden" name="id_cargo" id="id_cargo" >
                
                    <div class="mt-4 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="crear_datos_vehivulo" type="submit">Agregar</button>
                    </div> 
                
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ver Documento --}}
<div class="modal fade bs-example-modal-lg" id="modal_ver_documentos" tabindex="-1" role="dialog" aria-labelledby="modalmodal_ver_documentos" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal_ver_documentos-title">Documentos De Los Cargos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <button type="button" data-toggle="modal" data-target="#modal_agregar_documentos" class="btn btn-primary my-3 btn-lg" onclick="agregar_documento_vehiculo()">Agregar +</button>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Vigencia</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (\App\Models\Documentos_cargos_admin::all() as $doc)
                            <tr>
                                <td>{{ $doc->nombre }}</td>
                                <td>{{ $doc->vigencia == 1 ? 'Si' : 'No'}}</td>
                                <td  class="text-center">
                                    <button type="button" onclick="editar_documento_vehiculo({{$doc->id}}, '{{$doc->nombre}}', {{$doc->vigencia}}, this)" class="btn btn-primary"><i class="fas fa-edit"></i></button>
                                    <button type="button" onclick="eliminar_ddocumento_vehiculo({{$doc->id}},'{{ $doc->nombre }}', this)" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



{{-- Modal Agregar Documento --}}
<div class="modal fade bs-example-modal-lg" id="modal_agregar_documentos" tabindex="-1" role="dialog" aria-labelledby="modalmodal_ver_documentos" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal_agregar_documentos-title">Agregar Documento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('agg_documeto_cargo')}}" method="POST" id="form_crear_doc" onsubmit="cargarbtn('#crear_datos_documentos')">
                    @csrf
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="name_doc" class="col-sm-12 col-form-label">Nombre del documento</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" id="name_doc" name="nombre" placeholder="Escriba el nombre" required />
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="vigencia" class="col-sm-12 col-form-label">Tiene Vigencia?</label>
                            <div class="col-sm-12">
                                <select class="form-control" name="vigencia" id="vigencia_doc" required>
                                    <option value="">Seleccione</option>
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <input type="hidden" id="id_doc" name="id_doc" />
                
                    <div class="mt-4 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="crear_datos_documentos" type="submit">Agregar</button>
                    </div> 
                
                </form>
            </div>
        </div>
    </div>
</div>
@endsection







