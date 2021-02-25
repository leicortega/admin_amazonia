@section('title') Ver Correspondencia @endsection

@section('jsMain')
    <script src="{{ asset('assets/js/terceros.js') }}"></script>
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
                                    <div class="alert alert-danger mb-2" role="alert">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (session()->has('correspondencia') && session('correspondencia') == 1)
                                    <div class="alert alert-success">
                                        Respuesta creada correctamente.
                                    </div>
                                @endif
                                @if (session()->has('correspondencia') && session('correspondencia') == 2)
                                    <div class="alert alert-success">
                                        Respuesta Editada correctamente.
                                    </div>
                                @endif

                                @if (session()->has('correspondencia') && session('correspondencia') == 0)
                                    <div class="alert alert-error">
                                        Ocurrio un error, vuelva a intentarlo.
                                    </div>
                                @endif

                                <a href="/terceros/correspondencia/{{$correspondencia->tercero_id}}"><button onclick="cargar_btn_single(this)" type="button" class="mr-2 btn btn-dark btn-lg mb-2 float-left">Atras</button></a>
                                @if (\Auth::id() == $correspondencia->users_id)
                                    <button type="button" class="mr-2 btn btn-primary btn-lg mb-2 float-right" data-toggle="modal" data-target="#modal_add_respuesta_correspondencia" onclick="agregar_respuesta_correspondencia()">Responder</button>
                                @endif
                                <table class="table table-centered table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th colspan="12" class="text-center">
                                                <div class="d-inline-block icons-sm mr-2"><i class="uim fas fa-envelope-open mx-1"></i> </i></div>
                                                <span class="header-title mt-2">Informacion de correspondencia</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="table-bg-dark" colspan="2"><b>Cliente</b></td>
                                            <td colspan="2">{{$correspondencia->name_tercero}}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Tipo Radicacion</b></td>
                                            <td>{{$correspondencia->nombre_radicacion}}</td>
                                            <td class="table-bg-dark"><b>Usuario</b></td>
                                            <td>{{$correspondencia->usuario}}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Dependencia</b></td>
                                            <td>{{$correspondencia->nombre_dependencia}}</td>
                                            <td class="table-bg-dark"><b>Nº Folios</b></td>
                                            <td>{{$correspondencia->numero_folios}}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Origen</b></td>
                                            <td>{{$correspondencia->nombre_origen}}</td>
                                            <td class="table-bg-dark"><b>Asunto</b></td>
                                            <td>{{$correspondencia->asunto}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br><br>
                                <table class="table table-centered table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th colspan="12" class="text-center">
                                                <div class="d-inline-block icons-sm mr-2"></div>
                                                <span class="header-title mt-2">Respuestas</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Asunto</th>
                                            <th scope="col">Mensaje</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($respuestas as $key => $respuesta)

                                            <tr>
                                                <th scope="row">
                                                    {{ $key+1 }}
                                                </th>
                                                <td>{{ $respuesta->asunto }}</td>
                                                <td>{{ $respuesta->mensaje }}</td>
                                                <td class="text-center">
                                                    @if ($respuesta->adjunto != '' && $respuesta->adjunto != null)
                                                        <button type="button" class="btn btn-outline-secondary btn-sm" title="Ver Correspondencia" onclick="ver_documento('{{$respuesta->adjunto}}', 'Respuesta Correspondencia', this)"><i class="mdi mdi-eye"></i></button>
                                                    @endif
                                                    @if (\Auth::id() == $correspondencia->users_id)
                                                        <button type="button" class="btn btn-outline-secondary btn-sm" title="Editar Respuesta" onclick="editar_respuesta_correspondencia({{$respuesta}})"><i class="mdi mdi-pencil"></i></button>
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

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>




{{-- MODAL VER DOCUMENTO --}}
<div class="modal fade bs-example-modal-xl" id="modal_ver_documento" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal_ver_documento_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_ver_documento_content">

            </div>
        </div>
    </div>
</div>

@if (\Auth::id() == $correspondencia->users_id)
    {{-- AGREGAR CORRESPONDENCIA Y EDITAR--}}
    <div class="modal fade bs-example-modal-xl" id="modal_add_respuesta_correspondencia" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modal_title_correspondencia_respuesta">Agregar Correspondencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="/terceros/correspondencia/respuesta/create" id="" method="POST" onsubmit="cargar_btn_form(this)" enctype="multipart/form-data">
                        @csrf

                        <div class="container">
                            <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <label class="col-sm-12 col-form-label">Asunto</label>
                                        <input class="form-control" type="text" id="asunto_corre_respuesta" name="asunto" placeholder="Escriba el asunto" required>
                                    </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <label class="col-sm-12 col-form-label">Mensaje</label>
                                    <textarea type="text" class="form-control" id="mensaje_corre_respuesta" name="mensaje" required rows="3"></textarea>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <label for="adjunto_file">Agregar Adjunto</label>
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="file" id="adjunto" class="form-control" name="adjunto" id="adjunto_file_respuesta">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <input type="hidden" name="correspondencia_id" id="correspondencia_id" value="{{$correspondencia->id}}" />
                        <input type="hidden" name="respuesta_id" id="respuesta_id" value="" />

                        <div class="mt-5 text-center">
                            <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit" id="btn_enviar_correspondencia_respuesta">Agregar Respuesta</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endif


@endsection