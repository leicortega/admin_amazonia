@section('title') Cotizaciones @endsection 

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

                                @if (session()->has('enviado') && session('enviado') == 1)
                                    <div class="alert alert-success">
                                        La cotizacion fue enviada correctamente.
                                    </div>
                                @endif

                                @if (session()->has('update') && session('update') == 1)
                                    <div class="alert alert-success">
                                        El Usuario se actualizo correctamente.
                                    </div>
                                @endif
                                
                                @if (session()->has('create') && session('create') == 0)
                                    <div class="alert alert-danger">
                                        Ocurrio un error, contacte al desarrollador.
                                    </div>
                                @endif

                                @if (session()->has('tercero') && session('tercero') == 1)
                                    <div class="alert alert-success">
                                        Tercero creado correctamente.
                                    </div>
                                @endif

                                @if (session()->has('tercero_add') && session('tercero_add') == 1)
                                    <div class="alert alert-success">
                                        Tercero agregado correctamente.
                                    </div>
                                @endif

                                @if (session()->has('tercero') && session('tercero') == 0)
                                    <div class="alert alert-danger">
                                        Tercero NO creado correctamente.
                                    </div>
                                @endif

                                <table class="table table-centered table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th colspan="12" class="text-center">
                                                <div class="d-inline-block icons-sm mr-2"><i class="uim uim-window-grid"></i></div>
                                                <span class="header-title mt-2">Cotizaciones</span>
                                            </th>
                                        </tr>
                                        <!--Parte de busqueda de datos-->
                                        <tr>
                                            <th colspan="12" class="text-center">
                                                
                                            </th>
                                        </tr>
                                        <!--Fin parte de busqueda de datos-->
                                        <tr>
                                            <th scope="col">N°</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Correo</th>
                                            <th scope="col">Trayecto</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cotizaciones as $cotizacion)
                                            <tr>
                                                <th scope="row">
                                                    <a href="#">{{ $cotizacion->id }}</a>
                                                </th>
                                                <td>{{ $cotizacion->fecha }}</td>
                                                <td>{{ $cotizacion->nombre }}</td>
                                                <td>{{ $cotizacion->correo }}</td>
                                                <td>{{ $cotizacion->ciudad_origen.' - '.$cotizacion->ciudad_destino }}</td>
                                                <td class="text-center">
                                                    @if ( Request::is('cotizaciones/nuevas') )
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showCotizacion({{ $cotizacion->id }})" data-toggle="tooltip" data-placement="top" title="Ver correo">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </button>
                                                    @endif    

                                                    @if ( Request::is('cotizaciones/aceptadas') )
                                                        @if (!$cotizacion->tercero_id)
                                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="createTercero({{ $cotizacion->id }}, '{{ $cotizacion->nombre }}', '{{ $cotizacion->correo }}', {{ $cotizacion->telefono }})" data-toggle="tooltip" data-placement="top" title="Crear Tercero">
                                                                <i class="mdi mdi-account"></i>
                                                            </button> 
                                                        @endif
                                                        
                                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="createContrato({{ $cotizacion->id }})" data-toggle="tooltip" data-placement="top" title="Crear Contrato">
                                                            <i class="mdi mdi-check"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                            </div>

                            {{ $cotizaciones->links() }}
                            
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<div class="modal fade bs-example-modal-xl" id="modal-responder-cotizacion" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-cotizacion"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/cotizaciones/responder" method="POST">
                    @csrf

                    <div id="modal-content-cotizacion"></div>
                
                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn-submit-correo" type="submit">Enviar</button>
                    </div> 
                
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Crear/Añadir Tercero --}}
<div class="modal fade bs-example-modal-xl" id="modal-crear-tercero" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-cotizacion">Crear / Añadir Tercero</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group mb-4 container p-5">
                    <div class="row">
                        <div class="col-xl-10">
                            <input type="number" placeholder="identificacion" id="identificacion_tercero" class="form-control" />
                        </div>
                        <div class="col-xl-2">
                            <button class="btn btn-info waves-effect waves-light" type="button" onclick="buscarTercero()">Buscar</button>
                            <button class="btn btn-primary waves-effect waves-light" type="button" onclick="crearTercero()">Crear</button>
                        </div>
                    </div>
                </div>

                <form action="/cotizaciones/crear-tercero" id="form-create-tercero" method="POST" class="d-none">
                    @csrf

                    <div class="container">
                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">
                                    
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Tipo Identificacion</label>
                                    <select name="tipo_identificacion" class="form-control" required>
                                        <option value="">Seleccione tipo</option>
                                        <option value="Cedula de Ciudadania">Cedula de Ciudadania</option>
                                        <option value="Cedula de Extrangeria">Cedula de Extrangeria</option>
                                        <option value="Nit">Nit</option>
                                        <option value="Registro Civil">Registro Civil</option>
                                        <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Numero Identificación</label>
                                    <input class="form-control" type="number" name="identificacion" placeholder="Escriba la identificación" required="">
                                </div>
                                    
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Nombre Completo</label>
                                    <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Escriba el nombre" required="">
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Tipo Cliente</label>
                                    <select name="tipo_tercero" class="form-control" required>
                                        <option value="">Seleccione tipo</option>
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
                        </div>

                        <hr>

                        <div class="form-group row">
                            <div class="col-sm-12 d-flex">
                                    
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Régimen</label>
                                    <select name="regimen" class="form-control" required>
                                        <option value="">Seleccione régimen</option>
                                        <option value="Comun">Comun</option>
                                        <option value="Simplificado">Simplificado</option>
                                        <option value="Natural">Natural</option>
                                        <option value="Gran Contibuyente">Registro Civil</option>
                                        <option value="Persona Juridica">Persona Juridica</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Departamento</label>
                                    <select name="departamento" id="departamento" onchange="cargarMunicipios(this.value)" class="form-control" required>
                                    </select>
                                </div>
                                    
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Municipio</label>
                                    <select name="municipio" id="municipio" class="form-control" required>
                                        <option value="">Seleccione</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Dirección</label>
                                    <input class="form-control" type="text" name="direccion" placeholder="Escriba la Dirección" required="">
                                </div>

                            </div>
                        </div>
                        
                        <hr>

                        <div class="form-group row mb-3">
                            <div class="col-sm-12 d-flex">
                                    
                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Correo</label>
                                    <input class="form-control" type="text" name="correo" id="correo" placeholder="Escriba la Dirección" required="">
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Telefono</label>
                                    <input class="form-control" type="text" name="telefono" id="telefono" placeholder="Escriba la Dirección" required="">
                                </div>

                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="cotizacion_id" id="cotizacion_id" />

                    <div class="mt-5 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                    </div> 
                </form>

                <form action="/cotizaciones/add-tercero" id="form-add-tercero" method="POST" class="d-none">
                    @csrf

                    <div id="modal-content-tercero" class="text-center"></div>
                
                    <div class="mt-5 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="enviar-add-tercero" disabled type="submit">Enviar</button>
                    </div> 
                
                </form>
            </div>
        </div>
    </div>
</div>
@endsection







