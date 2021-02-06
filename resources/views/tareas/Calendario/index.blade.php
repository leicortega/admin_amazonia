@section('title') Calendario @endsection


@section('Plugins')

    <link href="{{asset('assets/libs/fullcalendar/fullcalendar.min.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{asset('assets/libs/moment/min/moment.min.js')}}"></script>
    <script src="{{asset('assets/libs/fullcalendar/fullcalendar.min.js')}}"></script>
    <script src="{{asset('assets/libs/fullcalendar/locale/es.js')}}"></script>
    {{-- <script src="{{asset('assets/js/pages/calendar.init.js')}}"></script> --}}
    <script src="{{asset('assets/js/tareas.js')}}"></script>
    
@endsection

@extends('layouts.app')
@section('content')
<style>
    .fc-state-active, .fc-state-disabled, .fc-state-down{
        background-color:#2fa97c !important;
        color:white !important;
    }
    .fc-event{
        font-size: .8125rem !important;
        margin: 1px 3px !important;
        padding: 3px 3px !important;
        text-align: center !important;
    }
</style>
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

                                <a href="{{ route('index') }}"><button type="button" class="btn btn-dark btn-lg mb-4" >Atras</button></a>
                                
                                <div class="col-sm-6 form-group form-group-custom mb-4 float-right">
                                    <select name="asignado" class="form-control" id="asignado" onchange="cambiarurl(this.value)" required>
                                        <option value=""></option>
                                        <option value="1">Mis tareas</option>
                                        <option value="3">Asignados a mi</option>
                                        <option value="2">Asignados por mi</option>
                                        <option value="0">Todos</option>
                                    </select>
                                    <label for="asignado">Ver por</label>
                                </div>

                                <div id="calendar"></div>

                            </div>

                            

                        </div>
                    </div>
                </div>
            </div>

        </div> 

    </div> 
</div>



<div class="modal fade bs-example-modal-lg" id="modalVerActivities" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tarea</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>x</span></button>
        </div>
        <div class="modal-body" id="body_ver">
            
        </div>
      </div>
    </div>
</div>



<div class="modal fade bs-example-modal-xl" id="modalCrearActivities" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="titulo_crear_tarea">Crear Tarea</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>x</span></button>
        </div>

        <div class="modal-body">

        <form action="/tareas/agregar" method="POST" enctype="multipart/form-data">
            @csrf
            
                <div class="container p-3">

                            <div class="row">
                                <div class="col-sm-12 d-none" id="asingado_none">
                                    <div class="form-group form-group-custom mb-4">
                                        <select name="asignado" class="form-control" id="asignado">
                                            <option value=""></option>
                                            @foreach (\App\User::all() as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="asignado">Asignar tarea a</label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control" name="name_tarea" id="name_tarea" required>
                                        <label for="name_tarea">Nombre Tarea</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 mb-4">
                                    <label for="tarea">Descripcion</label>
                                    <textarea name="tarea" id="tarea" rows="5" class="form-control" required placeholder="Escriba la descripcion de la tarea"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control datepicker-here" data-language="es"  id="fecha" data-date-format="yyyy-mm-dd" name="fecha" required>
                                        <label for="fecha">Fecha Inicial</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="time" class="form-control" id="time_fecha" name="time_fecha">
                                        <label>Hora inicial</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control datepicker-here" data-language="es" data-date-format="yyyy-mm-dd" id="fecha_limite" name="fecha_limite" required>
                                        <label for="fecha_limite">Fecha Final</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="time" class="form-control" id="time_fecha_final" name="time_fecha_final">
                                        <label>Hora final</label>
                                    </div>
                                </div>

                            </div>

                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="file" class="form-control" id="adjunto" name="adjunto">
                                        <label for="adjunto">Adjunto (opcional)</label>
                                    </div>
                                </div>
                            </div>

                            <input type="text" id="id_editar" name="id_editar" class="d-none">

                            <div class="row mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" onchange="cambiar_asignador()">
                                    <label class="form-check-label" for="flexCheckChecked">
                                      Asignar tarea
                                    </label>
                                  </div>
                            </div>

                            

                </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
      </div>
    </div>
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



@endsection