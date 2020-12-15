@section('title') HSEQ @endsection

@extends('layouts.app')

@section('jsMain') <script src="{{ asset('assets/js/hseq.js') }}"></script> @endsection

@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row p-xl-5 p-md-3">
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

                            @if (!Request::is('hseq/list'))
                                <a href="{{ url()->previous() }}"><button type="button" class="btn btn-dark btn-lg mb-3 mt-0">Atras</button></a>
                            @endif

                            @if ($files->where('type', '=', 'dir')->count() > 0)
                                <h3 class="col-12">Carpetas</h3>
                                <hr class="w-100">

                                @foreach ($files->where('type', '=', 'dir') as $dir)
                                    <div class="col-lg-3 mb-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="media">
                                                    <div class="icons-md mr-3">
                                                        <i class="uim uim-layer-group"></i>
                                                    </div>
                                                    <div class="media-body">
                                                        <p class="mb-1">{{ $dir['name'] }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-footer text-center">
                                                <a href="/hseq/list/{{ $dir['path'] }}">Entrar</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if ($files->where('type', '=', 'file')->count() > 0)
                                <h3 class="col-12">Documentos</h3>
                                <hr class="w-100">

                                @foreach ($files->where('type', '=', 'file') as $file)
                                    <div class="col-lg-3">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="media">
                                                    <div class="icons-md mr-3">
                                                        <i class="uim uim-document-layout-right"></i>
                                                    </div>
                                                    <div class="media-body">
                                                        <p class="mb-1">{{ $file['name'] }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-footer text-center">
                                                @if ($file['mimetype'] == 'image/jpeg')
                                                    <a href="javascript:ver_imagen('{{ $file['basename'] }}')">Ver Imagen</a>
                                                @else
                                                    <a href="javascript:ver_documento('{{ $file['basename'] }}')">Ver Documento</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

{{-- Modal Ver Documento --}}
<div class="modal fade bs-example-modal-xl" id="modal_ver_documento" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-correo">Ver Documento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="content_modal_ver_documento"></div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Asignar Tarea --}}
<div class="modal fade bs-example-modal-lg" id="modal_agregar_tarea" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-correo">Asignar Tarea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/tareas/agregar" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-group-custom mb-4">
                                    <select name="asignado" class="form-control" id="asignado" required>
                                        <option value=""></option>
                                    </select>
                                    <label for="asignado">Asignar tarea a</label>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-4">
                                <label for="tarea">Descripcion</label>
                                <textarea name="tarea" id="tarea" rows="10" class="form-control" required placeholder="Escriba la descripcion de la tarea"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" data-language="es" data-date-format="yyyy-mm-dd" id="fecha_limite" name="fecha_limite" required>
                                    <label for="fecha_limite">Fecha limite</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-group-custom mb-4">
                                    <input type="file" class="form-control" id="adjunto" name="adjunto">
                                    <label for="adjunto">Adjunto (opcional)</label>
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
@endsection






