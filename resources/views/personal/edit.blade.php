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

                                @if (session()->has('update') && session('update') == 1)
                                    <div class="alert alert-primary">
                                        clave actualizada correctamente
                                    </div>
                                @endif

                                @if (session()->has('cargo_delete') && session('cargo_delete') == 1)
                                    <div class="alert alert-primary">
                                        Cargo eliminado correctamente
                                    </div>
                                @endif
                                
                                <h3>Editar clave</h3> 
                                
                                <form action="/personal/update_clave" id="form_clave" method="POST" onsubmit="cargarbtn('#submit_modal_clave')">
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
                                    

                                    <input type="hidden" name="identificacion" value="{{ $personal->identificacion }}">
                                    
                                    
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1">Roles</label>
                                                
                                                <select class="form-control" name="tipo" id="tipo_user" onchange="selectTipo(this.value)" required>
                                                    <option value="">Seleccione el tipo</option>
                                                    @foreach (\Spatie\Permission\Models\role::all() as $rol)
                                                        <option value="{{$rol->name}}" {{$user->roles()->first()->name==$rol->name?'selected':''}}>{{$rol->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="table-responsive {{$user->roles()->first()->name=='general'?'':'d-none'}}" id="div_permisos">
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
                                                                    @if ($permiso->name=='universal')
                                                                        <td colspan="4"><input type="checkbox" id="{{ $permiso->name }}" name="permisos[]" value="{{ $permiso->name }}" title="Acceso total" {{in_array($permiso->name, $permisosuser, true)?'checked':''}}></td>      
                                                                    @else
                                                                        <td><input type="checkbox" name="permisos[]" value="{{$permiso->name}}" id="{{$permiso->name}}" {{in_array($permiso->name, $permisosuser, true)?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="permisos[]" value="{{$permiso->name}}.create" id="{{$permiso->name}}.create" {{in_array(($permiso->name.".create"), $permisosuser, true)?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="permisos[]" value="{{$permiso->name}}.edit" id="{{$permiso->name}}.edit" {{in_array(($permiso->name.".edit"), $permisosuser, true)?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="permisos[]" value="{{$permiso->name}}.destroy" id="{{$permiso->name}}.destroy" {{in_array(($permiso->name.".destroy"), $permisosuser, true)?'checked':''}}></td>
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
                                        <button class="btn btn-primary btn-lg waves-effect waves-light" id="submit_modal_clave" type="submit">Editar</button>
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

@endsection
