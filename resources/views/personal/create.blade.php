@section('title') Persona  @endsection

@section('jsMain')
    <script src="{{ asset('assets/js/personal.js') }}"></script>
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

                                <a href="/personal/ver/{{$personal->id}}"</a><button type="button" class="btn btn-dark btn-lg mb-2" onclick="cargarbtn(this)">Atras</button></a>

                                @if (session()->has('create') && session('create') == 1)
                                    <div class="alert alert-primary">
                                        clave creada correctamente
                                    </div>
                                @endif

                                @if (session()->has('cargo_delete') && session('cargo_delete') == 1)
                                    <div class="alert alert-primary">
                                        Cargo eliminado correctamente
                                    </div>
                                @endif
                                
                                <h3>Crear clave</h3> 
                                <form action="/personal/crear_clave" id="form_clave" method="POST" onsubmit="cargarbtn('#submit_modal_clave')">
                                    @csrf

                                    <input type="hidden" name="user_id" value="{{ $personal->id }}">
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="text" name="name" placeholder="Escriba el nombre" autocomplete="off" value="{{ $personal->nombres }} {{ $personal->primer_apellido }} {{ $personal->segundo_apellido }}" readonly required />
                                        </div>
                                    </div>
                
                                    <div class="form-group row">
                                        <label for="identificacion" class="col-sm-2 col-form-label">Identificacion</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="number" name="identificacion" placeholder="Escriba la identificacion" readonly value="{{ $personal->identificacion }}" autocomplete="off" required />
                                        </div>
                                    </div>
                
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-2 col-form-label">Correo (opcional)</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="email" name="email" placeholder="Escriba el correo" readonly value="{{ $personal->correo }}" autocomplete="off" />
                                        </div>
                                    </div>
                
                                    <div class="form-group row">
                                        <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                        <div class="col-sm-10">
                                            <select name="estado" id="estado_user" class="form-control" required>
                                                <option value="">Seleccione el estado</option>
                                                <option value="Activo">Activo</option>
                                                <option value="Inactivo">Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                
                                    <div class="form-group row">
                                        <label for="password" class="col-sm-2 col-form-label">Contraseña</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="password" name="password" placeholder="Escriba la contraseña" autocomplete="off" />
                                        </div>
                                    </div>
                
                                    <div class="form-group row">
                                        <label for="tipo" class="col-sm-2 col-form-label">Tipo</label>
                                        <div class="col-sm-10">
                                            <select name="tipo" id="tipo_user" class="form-control" onchange="selectTipo(this.value)" required>
                                                <option value="">Seleccione el tipo</option>

                                                @foreach (\Spatie\Permission\Models\role::all() as $rol)
                                                    <option value="{{$rol->name}}">{{$rol->name}}</option>
                                                @endforeach
                                                
                                            </select>
                                        </div>
                                    </div>
                
                                    <div class="table-responsive d-none" id="div_permisos">
                                        <table class="table table-sm table-borderless">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">Permisos</th>
                                                <th scope="col">Ver</th>
                                                <th scope="col">Crear</th>
                                                <th scope="col">Editar</th>
                                                <th scope="col">Eliminar</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                                @php $currentProvince = '.' @endphp
                                                @foreach ($permisos as $permiso)
                                                    @if (!strpos($permiso->name, $currentProvince))
                                                        <tr>
                                                            <th scope="row">{{ $permiso->name }}</th>
                                                            @if ($permiso->name!='hseq')
                                                                <td colspan="4"><input class="" type="checkbox"id="{{ $permiso->name }}" name="permisos[]" value="{{ $permiso->name }}" title="Acceso total"></td>      
                                                            @else
                                                                <td><input class="" type="checkbox" name="permisos[]" value="{{$permiso->name}}" id="ver"></td>
                                                                <td><input class="" type="checkbox" name="permisos[]" value="{{$permiso->name}}.create" id="{{$permiso->name}}.create"></td>
                                                                <td><input class="" type="checkbox" name="permisos[]" value="{{$permiso->name}}.edit" id="{{$permiso->name}}.edit"></td>
                                                                <td><input class="" type="checkbox" name="permisos[]" value="{{$permiso->name}}.destroy" id="{{$permiso->name}}.destroy"></td>
                                                            @endif
                                                        
                                                        </tr>
                                                    @endif
                                                    
                                                @endforeach
                                                
                                                
                                            </tbody>
                                        </table>
                                    </div>
                
                                    <div class="mt-4 text-center">
                                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="submit_modal_clave" type="submit">Crear</button>
                                    </div>
                
                                </form>
                                
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

{{-- AGREGAR CONTRATO --}}
<div class="modal fade bs-example-modal-xl" id="agg_contrato" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Agregar contrato</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/personal/crear_contrato" id="form_crear_contrato" method="POST">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-3">
                                <label for="salario">Salario</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" id="salario" name="salario" placeholder="Escriba el salario" required="">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label for="estado">Estado</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="estado" id="estado" class="form-control" required>
                                        <option value="">Seleccione</option>
                                        <option value="Activo">Activo</option>
                                        <option value="Terminado">Terminado</option>
                                        <option value="Suspendido">Suspendido</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label for="cargo">Cargo</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="cargo" id="cargo" class="form-control" required>
                                        <option value="">Seleccione</option>
                                        @foreach ($personal->cargos_personal as $cargo)
                                            <option value="{{ $cargo->cargos['nombre'] }}">{{ $cargo->cargos['nombre'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label for="tipo_contrato">Tipo contrato</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_contrato" id="tipo_contrato" onchange="tipo_contrato_select(this.value)" class="form-control" required>
                                        <option value="">Seleccione</option>
                                        <option value="Obra labor">Obra labor</option>
                                        <option value="Termino fijo">Termino fijo</option>
                                        <option value="Termino indefinido">Termino indefinido</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 d-none" id="fecha_inicio_div">
                                <label for="fecha_inicio">Fecha inicio</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" name="fecha_inicio"  id="fecha_inicio" required>
                                </div>
                            </div>
                            <div class="col-sm-6 d-none" id="fecha_fin_div">
                                <label for="fecha_fin">Fecha fin</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" name="fecha_fin"  id="fecha_fin" required>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row d-none" id="clausulas_div">
                            <div class="col-sm-12">
                                <label for="clausulas_parte_uno">Clausulas parte uno</label>
                                <div class="form-group mb-4">
                                    <textarea name="clausulas_parte_uno" id="clausulas_parte_uno" class="form-control" rows="15"></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="col-sm-12" id="clausulas_div">
                                <label for="clausulas_parte_dos">Clausulas parte Dos</label>
                                <div class="form-group mb-4">
                                    <textarea name="clausulas_parte_dos" id="clausulas_parte_dos" class="form-control" rows="15"></textarea>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="personal_id" value="{{ $personal->id }}">
                        <input type="hidden" name="contrato_id" >

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- AGREGAR OTRO SI --}}
<div class="modal fade bs-example-modal-xl" id="agg_otro_si" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Agregar Otro Si</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/personal/crear_otro_si" id="form_agg_otro_si" method="POST">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="fecha">Fecha</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" name="fecha"  id="fecha" required>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="descripcion">Clausulas parte uno</label>
                                <div class="form-group mb-4">
                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="15" required>Entre los suscritos, a saber JOIMER OSORIO BAQUERO, identificado con cedula de ciudadanía 7.706.232 de Neiva Huila, actuando en su calidad de Representante Legal de Amazonia C&L SAS, identificada con Nit. 900447438-6, con domicilio principal en la ciudad de Neiva Huila, quien para los efectos del presente documento se denominara EL EMPLEADOR de una parte, y, de la otra ALIETH NATHALIE CASTRO TENGONO mayor de edad, identificado con cedula de ciudadanía número 1075262366 de XXXXXXXXXX, domiciliado en XXXXXXXXX, obrando en nombre propio quien para efectos de este documento se denominará EL EMPLEADO, hemos convenido en modificar el contrato de trabajo de fecha 2020-09-01 celebrado entre EL EMPLEADOR y EL EMPLEADO el cual quedará así:</textarea>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="contratos_personal_id" id="contratos_personal_id">

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- AGREGAR DOCUMENTO --}}
<div class="modal fade bs-example-modal-xl" id="agg_documento" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="agg_documento_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/personal/agg_documento" id="form_agg_documento" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="tipo" id="consecutivo_title">Tipo Documento</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="tipo" name="tipo" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="fecha_expedicion">Fecha expedición</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" id="fecha_expedicion" name="fecha_expedicion" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 d-none" id="fecha_inicio_vigencia_div">
                                <label for="fecha_inicio_vigencia">Fecha inicio de vigencia</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" name="fecha_inicio_vigencia"  id="fecha_inicio_vigencia">
                                </div>
                            </div>
                            <div class="col-sm-6 d-none" id="fecha_fin_vigencia_div">
                                <label for="fecha_fin_vigencia">Fecha fin de vigencia</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" autocomplete="off" data-language="es" data-date-format="yyyy-mm-dd" name="fecha_fin_vigencia"  id="fecha_fin_vigencia">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="observaciones">Observaciones</label>
                                <div class="form-group mb-4">
                                    <textarea name="observaciones" id="observaciones" class="form-control" rows="10" ></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="adjunto">Agregar Adjunto</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="file" class="form-control" name="adjunto" id="adjunto" required>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="id_table" id="id_table">
                        <input type="hidden" name="personal_id" value="{{ $personal->id }}">

                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- MOSTRAR PERSONAL --}}
<div class="modal fade bs-example-modal-xl" id="editar_personal_modal" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-personal">Editar a {{ $personal->nombres }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/personal/update" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container p-3">

                        <div class="row">
                            <div class="col-sm-4">
                                <label for="tipo_identificacion">Tipo de identificacion</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_identificacion" class="form-control" id="tipo_identificacion" required>
                                        <option value=""></option>
                                        <option {{ $personal->tipo_identificacion == 'Cedula de ciudadania' ? 'selected' : '' }} value="Cedula de ciudadania">Cedula de ciudadania</option>
                                        <option {{ $personal->tipo_identificacion == 'Cedula de Extranjeria' ? 'selected' : '' }} value="Cedula de Extranjeria">Cedula de Extranjeria</option>
                                        <option {{ $personal->tipo_identificacion == 'Nit' ? 'selected' : '' }} value="Nit">Nit</option>
                                        <option {{ $personal->tipo_identificacion == 'Registro Civil' ? 'selected' : '' }} value="Registro Civil">Registro Civil</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="identificacion">Numero de identificacion</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="number" class="form-control" id="identificacion" name="identificacion" value="{{ $personal->identificacion }}" required="">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="nombres">Nombre</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="nombres" name="nombres" value="{{ $personal->nombres }}" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <label for="primer_apellido">Primer Apellido</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" value="{{ $personal->primer_apellido }}" required="">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="segundo_apellido">Segundo Apellido</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" value="{{ $personal->segundo_apellido }}">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="fecha_ingreso">Fecha de ingreso</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control datepicker-here" data-language="es" data-date-format="yyyy-mm-dd" value="{{ $personal->fecha_ingreso }}" id="fecha_ingreso" name="fecha_ingreso" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <label for="direccion">Direccion</label>
                                <div class="form-group form-group-custom mb-4">
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $personal->direccion }}" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <h5 class="font-size-14">Sexo</h5>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio6" name="sexo" class="custom-control-input" {{ $personal->sexo == 'Hombre' ? 'checked' : '' }} value="Hombre">
                                    <label class="custom-control-label" for="custominlineRadio6">Hombre</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio7" name="sexo" class="custom-control-input" {{ $personal->sexo == 'Mujer' ? 'checked' : '' }} value="Mujer">
                                    <label class="custom-control-label" for="custominlineRadio7">Mujer</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio8" name="sexo" class="custom-control-input" {{ $personal->sexo == 'Otro' ? 'checked' : '' }} value="Otro">
                                    <label class="custom-control-label" for="custominlineRadio8">Otro</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <h5 class="font-size-14">Estado</h5>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio9" name="estado" class="custom-control-input" {{ $personal->estado == 'Activo' ? 'checked' : '' }} value="Activo">
                                    <label class="custom-control-label" for="custominlineRadio9">Activo</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custominlineRadio10" name="estado" class="custom-control-input" {{ $personal->estado == 'Inactivo' ? 'checked' : '' }} value="Inactivo">
                                    <label class="custom-control-label" for="custominlineRadio10">Inactivo</label>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <label for="rh">RH</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="rh" name="rh" value="{{ $personal->rh }}" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <label for="tipo_vinculacion">Tipo Vinculacion</label>
                                <div class="form-group form-group-custom mb-4">
                                    <select name="tipo_vinculacion" class="form-control" id="tipo_vinculacion" required>
                                        <option value=""></option>
                                        <option {{ $personal->tipo_vinculacion == 'AMAZONIA C&L' ? 'selected' : '' }} value="AMAZONIA C&L">AMAZONIA C&L</option>
                                        <option {{ $personal->tipo_vinculacion == 'EXTERNO' ? 'selected' : '' }} value="EXTERNO">EXTERNO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label for="correo">Correo</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="correo" name="correo" value="{{ $personal->correo }}" required="">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label for="telefonos">Telefonos</label>
                                <div class="form-group form-group-custom mb-4">
                                    <input type="text" class="form-control" id="telefonos" name="telefonos" value="{{ $personal->telefonos }}" required="">
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <label for="" class="ml-1">Firma</label>
                                <div class="container">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label class="input-group-btn">
                                                <span class="btn btn-primary btn-file">
                                                    <i class="fas fa-cloud-upload-alt"></i> <input class="d-none" name="firma" type="file" id="banner">
                                                </span>
                                            </label>
                                            <input class="form-control" id="banner_captura" readonly="readonly" name="banner_captura" type="text" value="{{basename($personal->firma)}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <input type="hidden" name="id" value="{{ $personal->id }}">

                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL GENERAR CLAVE --}}
<div class="modal fade bs-example-modal-lg" id="modal-create-clave" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="title_modal_clave">Crear Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/personal/crear_clave" id="form_clave" method="POST" onsubmit="cargarbtn('#submit_modal_clave')">
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="name" placeholder="Escriba el nombre" autocomplete="off" value="{{ $personal->nombres }} {{ $personal->primer_apellido }} {{ $personal->segundo_apellido }}" readonly required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="identificacion" class="col-sm-2 col-form-label">Identificacion</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="number" name="identificacion" placeholder="Escriba la identificacion" readonly value="{{ $personal->identificacion }}" autocomplete="off" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Correo (opcional)</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="email" name="email" placeholder="Escriba el correo" readonly value="{{ $personal->correo }}" autocomplete="off" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                        <div class="col-sm-10">
                            <select name="estado" id="estado_user" class="form-control" required>
                                <option value="">Seleccione el estado</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Contraseña</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="password" name="password" placeholder="Escriba la contraseña" autocomplete="off" />
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Roles</label>
                                <select class="form-control" id="exampleFormControlSelect1" onchange="selectTipo(this.value)" required>
                                    <option value="">Seleccione el tipo</option>

                                    <option value="admin">Admin</option>
                                    <option value="general"> General</option>
                                </select>
                            </div>
                            <div class="table-responsive d-none" id="div_permisos">
                                <table class="table table-sm table-borderless">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Permisos</th>
                                        <th scope="col">Ver</th>
                                        <th scope="col">Crear</th>
                                        <th scope="col">Editar</th>
                                        <th scope="col">Eliminar</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                        @php $currentProvince = '.' @endphp
                                        @foreach ($permisos as $permiso)
                                            @if (!strpos($permiso->name, $currentProvince))
                                                <tr>
                                                    <th scope="row">{{ $permiso->name }}</th>
                                                    @if ($permiso->name!='hseq')
                                                        <td colspan="4"><input class="" type="checkbox"id="{{ $permiso->name }}" name="permisos[]" value="{{ $permiso->name }}" title="Acceso total"></td>      
                                                    @else
                                                        <td><input class="" type="checkbox" name="permisos[]" value="{{$permiso->name}}" id="ver"></td>
                                                        <td><input class="" type="checkbox" name="permisos[]" value="{{$permiso->name}}.create" id="{{$permiso->name}}.create"></td>
                                                        <td><input class="" type="checkbox" name="permisos[]" value="{{$permiso->name}}.edit" id="{{$permiso->name}}.edit"></td>
                                                        <td><input class="" type="checkbox" name="permisos[]" value="{{$permiso->name}}.destroy" id="{{$permiso->name}}.destroy"></td>
                                                    @endif
                                                
                                                </tr>
                                            @endif
                                            
                                        @endforeach
                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="submit_modal_clave" type="submit">Crear</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
