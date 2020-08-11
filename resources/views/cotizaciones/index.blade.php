@section('title') Cotizaciones @endsection 

@section('Plugins') 
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script> 
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script> 
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
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showCotizacion({{ $cotizacion->id }})" data-toggle="tooltip" data-placement="top" title="Ver correo">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </button>
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
@endsection







