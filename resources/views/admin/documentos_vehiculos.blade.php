@section('title') Documentos Vehiculos @endsection

@extends('layouts.app')

@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
            
                        <h4 class="header-title mb-3">Documentos Vehiculos</h4>

                        @if (session()->has('create') && session('create') == 2)
                            <div class="alert alert-primary" role="alert">
                                Documento creado correctamente
                            </div>
                        @endif

                        @if (session()->has('edit') && session('edit') == 1)
                        <div class="alert alert-primary" role="alert">
                            Documento editado correctamente
                        </div>
                    @endif

                        @if (session()->has('create') && session('create') == 1)
                        <div class="alert alert-primary" role="alert">
                            Categoria Creada correctamente
                        </div>
                        @endif

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            @php
                            $a=0;
                            @endphp
                            @foreach ($categorias as $categoria)
                            @php
                                $a++;
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link {{$a==1 ? 'active' : ''}}" data-toggle="tab" href="#{{str_replace(' ', '', $categoria->categoria)}}" role="tab">
                                    <span class="d-none d-md-inline-block">{{$categoria->categoria}}</span> 
                                </a>
                            </li>
                            @endforeach
                            <li class="nav-item">
                                <button type="button" data-toggle="modal" data-target="#modal-create-categoria-vehiculo" class="btn btn-primary ">+</button>
                            </li>
                            
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3">
                            @php
                                $a=0;
                            @endphp
                            @foreach ($categorias as $categoria)
                            @php
                                $a++;
                            @endphp
                            <div class="tab-pane {{$a==1 ? 'active' : ''}}" id="{{str_replace(' ', '', $categoria->categoria)}}" role="tabpanel">
                                <button type="button" onclick='dato_categoria_documento({{$categoria->id}}, "{{$categoria->categoria}}")' data-toggle="modal" data-target="#modal-create-documento-vehiculo" class="btn btn-primary my-3 btn-lg">Agregar +</button>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Vigencia</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (App\Models\Admin_documentos_vehiculo::where('categoria_id', $categoria->id)->get() as $documento)
                                            <tr>
                                                <td scope="row">{{ $documento->name }}</td>
                                                <td scope="row">{{ $documento->vigencia ? 'Si' : 'No' }}</td>
                                                <td><button type="button" onclick='editar_documentos_vehiculo({{ $documento->id }}, "{{$documento->name}}",  "{{$documento->vigencia}}", "{{$documento->tipo_tercero}}")' class="btn btn-primary" data-toggle="modal" data-target="#modal-edit-documentos-vehiculo"><i class="fas fa-edit"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endforeach



                        </div>

                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<div class="modal fade bs-example-modal-lg" id="modal-create-categoria-vehiculo" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-create-datos-vehiculo-title">Agregar Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/admin/sistema/agg_categoria_documentos_vehiculo" method="POST">
                    @csrf
                
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="nombre" placeholder="Escriba el nombre" required />
                        </div>
                    </div>
                
                    <div class="mt-4 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Agregar Categoria</button>
                    </div> 
                
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-lg" id="modal-create-documento-vehiculo" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-create-documentos-vehiculo-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/admin/sistema/agg_documentos_vehiculo" method="POST">
                    @csrf
                
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="nombre" placeholder="Escriba el nombre" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Vigencia</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="vigencia" required>
                                <option value="">Seleccione</option>
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Tipo De Tercero</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="tipo_tereceto" name="tipo_tercero" required>
                                <option value="">Seleccione</option>
                                <option value="Cliente">Cliente</option>
                                <option value="Convenio">Convenio</option>
                                <option value="Colegio o Institución Educativa">Colegio o Institución Educativa</option>
                                <option value="Aseguradora">Aseguradora</option>
                                <option value="Ente Territorial">Ente Territorial</option>
                                <option value="CDA (Centro de Diagnóstico Automotor)">CDA (Centro de Diagnóstico Automotor)</option>
                                <option value="Documentación Interna">Documentación Interna</option>
                                <option value="Proveedores">Proveedores</option>
                                <option value="Rastreo Satelital GPS">Rastreo Satelital GPS</option>
                                <option value="SEGUIMIENTO">SEGUIMIENTO</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" id="categoria_pase" name="categoria" value="" />
                
                    <div class="mt-4 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Agregar Documento</button>
                    </div> 
                
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-lg" id="modal-edit-documentos-vehiculo" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-edit-documentos-vehiculo-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/admin/sistema/edit_documentos_vehiculo" method="POST">
                    @csrf
                
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input class="form-control" id="id_nombre_documento" type="text" name="nombre" placeholder="Escriba el nombre" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Vigencia</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="vigencia_edit" name="vigencia" required>
                                <option value="">Seleccione</option>
                                <option value="1" >Si</option>
                                <option value="0" >No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Tipo De Tercero</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="tipo_tereceto_edit" name="tipo_tercero" required>
                                <option value="">Seleccione</option>
                                <option value="Cliente">Cliente</option>
                                <option value="Convenio">Convenio</option>
                                <option value="Colegio o Institución Educativa">Colegio o Institución Educativa</option>
                                <option value="Aseguradora">Aseguradora</option>
                                <option value="Ente Territorial">Ente Territorial</option>
                                <option value="CDA (Centro de Diagnóstico Automotor)">CDA (Centro de Diagnóstico Automotor)</option>
                                <option value="Documentación Interna">Documentación Interna</option>
                                <option value="Proveedores">Proveedores</option>
                                <option value="Rastreo Satelital GPS">Rastreo Satelital GPS</option>
                                <option value="SEGUIMIENTO">SEGUIMIENTO</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" id="id_pase" name="id" value="" />
                
                    <div class="mt-4 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Editar Documento</button>
                    </div> 
                
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
