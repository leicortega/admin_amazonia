@section('title') Correos @endsection 

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

                                @if (session()->has('respuesta') && session('respuesta') == 1)
                                    <div class="alert alert-success">
                                        El correo fue enviado correctamente.
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
                                                <div class="d-inline-block icons-sm mr-2"><i class="uim uim-comment-alt-message"></i></div>
                                                <span class="header-title mt-2">Correos</span>
                                            </th>
                                        </tr>
                                        <!--Parte de busqueda de datos-->
                                        <tr>
                                            <th colspan="12" class="text-center">
                                                {{-- <form action="/dashboard/programacion-viaje/get-ciudades" method="get" class="d-inline-block w-50">
                                                    @csrf

                                                    <div class="row col-12 text-center">
                                                        <div class="styled-select col-5">
                                                            <select class="form-control required" id="ciudad_origen" name="ciudad_origen" required onchange="ciudadDestino(this.value)">
                                                                <option value="">Ciudad Origen</option>
                                                            </select>
                                                        </div>
                                                        <div class="styled-select col-5">
                                                            <select class="form-control required" id="ciudad_destino" name="ciudad_destino" required>
                                                                <option value="">Ciudad Destino</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-2">
                                                            <button type="submit" class="btn btn-primary">Buscar</button>
                                                            
                                                        </div>
                                                    </div>
                                                </form> --}}
                                            </th>
                                        </tr>
                                        <!--Fin parte de busqueda de datos-->
                                        <tr>
                                            <th scope="col">N°</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Correo</th>
                                            <th scope="col">Asunto</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($correos as $correo)
                                            <tr>
                                                <th scope="row">
                                                    <a href="#">{{ $correo->id }}</a>
                                                </th>
                                                <td>{{ Carbon\Carbon::parse($correo->fecha)->format('d-m-Y') }}</td>
                                                <td>{{ $correo->nombre .' '. $correo->apellido }}</td>
                                                <td>{{ $correo->email }}</td>
                                                <td>{{ Str::limit($correo->asunto, 20) }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showCorreo({{ $correo->id }})" data-toggle="tooltip" data-placement="top" title="Ver correo">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                            </div>

                            {{ $correos->links() }}
                            
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<div class="modal fade bs-example-modal-xl" id="modal-responder-correo" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-correo"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/correos/responder" method="POST">
                    @csrf

                    <div id="modal-content-correo"></div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" id="textarea-correo">
                                    <h4 class="header-title text-center">Respuesta</h4>
                                    
                                    <textarea id="elm1" name="area" class="text-white"></textarea>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>
                
                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn-submit-correo" type="submit">Enviar</button>
                    </div> 
                
                </form>
            </div>
        </div>
    </div>
</div>
@endsection







