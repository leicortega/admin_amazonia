@section('title') Control de Ingreso @endsection 

@section('Plugins') 
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script> 
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script> 
@endsection

@extends('layouts.app')

@section('jsMain') 
    <script src="{{ asset('assets/js/peticiones.js') }}"></script> 
    @if (isset($_GET['create']) && $_GET['create'] == 1)
        <script>registrarIngreso('<?php echo $_GET["id"] ?>', '<?php echo $_GET["name"] ?>')</script>
    @endif
@endsection

@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
        
                        <h4 class="mt-0 header-title">Control de ingreso de Funcionarios</h4>
                        
                        <hr>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-0" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
        
                        @if (session()->has('create') && session('create') == 1)
                            <div class="alert alert-success">
                                <h6>El Funcionario se creo correctamente.</h6>
                            </div>
                        @endif
                        
                        @if (session()->has('create') && session('create') == 0)
                            <div class="alert alert-danger">
                                <h6>Ocurrio un error, contacte al desarrollador.</h6>
                            </div>
                        @endif
        
                        @if (session()->has('ingreso') && session('ingreso') == 1)
                            <div class="alert alert-success">
                                <h6>Se registro el ingreso correctamente</h6>
                            </div>
                        @endif
                        
                        @if (session()->has('ingreso') && session('ingreso') == 0)
                            <div class="alert alert-danger">
                                <h6>Ocurrio un error, contacte al desarrollador.</h6>
                            </div>
                        @endif
        
                        @if (session()->has('ingreso') && session('ingreso') == 2)
                            <div class="alert alert-danger">
                                <h6>Ya hay un registro hoy de esta persona.</h6>
                            </div>
                        @endif
        
                        <div class="row p-xl-3 p-md-3">                   
                            <div class="table-responsive" id="Resultados">
                                
                                @php
                                    $hoy = \Carbon\Carbon::now('America/Bogota')->isoFormat('Y-MM-DD');
                                @endphp
        
                                <div class="py-2">
                                    <div class="row mx-0 align-items-center">

                                        @if (Auth::user()->hasRole('admin'))
                                        <form class="row col-12 justify-content-center" method="POST" action="/control_ingreso/search"> 
                                            @csrf
        
                                            <div class="form-group mb-0 col-lg-6">
                                                <div class="input-group mb-0">
                                                    <input type="text" class="form-control" placeholder="Numero de identificacion" name="identificacion_search" required />
                                                    <div class="input-group-append">
                                                        <button class="btn btn-success" type="submit" id="project-search-addon"><i class="mdi mdi-magnify search-icon font-12"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form> 
                                        @endif
        
                                        <div class="col-8">
                                            <h6>Fecha: {{ $hoy }} </h6>
                                        </div>
                                        @if (Auth::user()->hasRole('admin'))
                                        <div class="col-4">
                                            <div class="float-right">
                                                <button class="btn btn-primary dropdown-toggle arrow-none waves-effect waves-light float-right" data-toggle="modal" data-target="#crearFuncionario" type="button">
                                                    <i class="mdi mdi-plus mr-2"></i> Agregar
                                                </button>
                                            </div>
                                        </div>  
                                        @endif
                                        
                                    </div> <!-- end row -->
                                </div>
                                <table class="table table-centered table-hover table-bordered mb-0 mt-0">
                                    <thead>
                                    <!--Fin parte de busqueda de datos-->
                                        <tr>
                                            <th scope="col">N°</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Tipo</th>
                                            <th scope="col">Ultimo Ingreso</th>
                                            <th scope="col">Temperatura</th>
                                            <th scope="col" class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($funcionarios as $item)
                                            @php
                                                $ultimoIngreso = isset($item->ingresos[0]['fecha']) ? $item->ingresos[0]['fecha'] : '2000-00-00';
                                            @endphp
                                            <tr>
                                                <th scope="row">
                                                    <a href="#">{{ $item->identificacion }}</a>
                                                </th>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->tipo }}</td>
                                                <td>{{ isset($item->ingresos[0]['fecha']) ? Carbon\Carbon::parse($item->ingresos[0]['fecha'])->format('d-m-Y') : '' }}</td>
                                                <td>{{ isset($item->ingresos[0]['fecha']) ? $item->ingresos[0]['temperatura'] : '' }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-outline-info btn-sm" <?php echo $hoy <= $ultimoIngreso ? 'disabled' : '' ?> onclick="registrarIngreso({{ $item->id }}, '{{ $item->name }}')" data-toggle="tooltip" data-placement="top" title="Registrar ingreso">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </button>
                                                    
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="historialIngresos({{ $item->id }}, '{{ $item->name }}')" data-toggle="tooltip" data-placement="top" title="Ver Historial">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button>

                                                    <a href="<?php echo $hoy > $ultimoIngreso ? '#' : '/control_ingreso/print/'.$item->id.'/'.$hoy ?>" <?php echo $hoy > $ultimoIngreso ? '' : 'target="_blank"' ?>>
                                                        <button type="button" class="btn btn-outline-primary btn-sm <?php echo $hoy > $ultimoIngreso ? 'disabled' : '' ?>">
                                                            <i class="mdi mdi-file"></i>
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
        
                        
                        {{ $funcionarios->links() }}
        
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

{{-- Modal Agregar --}}
<div class="modal fade bs-example-modal-lg" id="crearFuncionario" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-blade-title">Agregar Funcionario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-blade-body">
                <form action="/control_ingreso/create" method="POST">
                    @csrf

                    <div class="form-group row">
                        <label for="identificacion" class="col-sm-2 col-form-label">Identificacion</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="number" name="identificacion" id="identificacion" placeholder="Escriba la identificacion" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="name" id="name" placeholder="Escriba el nombre" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="telefono" class="col-sm-2 col-form-label">Telefono</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="number" name="telefono" id="telefono" placeholder="Escriba el telefono" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="edad" class="col-sm-2 col-form-label">Edad</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="number" name="edad" id="edad" placeholder="Escriba la edad" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Correo</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="email" name="email" id="email" placeholder="Escriba el correo"  />
                        </div>
                    </div>

                    <input type="hidden" value="{{ Request::path() == 'control_ingreso/funcionarios' ? 'Funcionario' : 'Cliente' }}" name="tipo" />

                    <div class="mt-3">
                        <button class="btn btn-success btn-lg waves-effect waves-light" type="submit">Agregar</button>
                    </div> 

                </form>   
            </div>
        </div>
    </div>
</div>

{{-- Modal Registrar Ingreso --}}
<div class="modal fade bs-example-modal-lg" id="registrarIngreso" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="registrarIngreso-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-blade-body">
                <form action="/control_ingreso/registrar" method="POST">
                    @csrf

                    <div class="form-group row">
                        <div class="col-sm-6 d-flex">
                            <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                            <div class="col-sm-10">
                                <input class="form-control disabled" type="text" name="fecha" id="fecha" value="{{ $hoy }}" />
                            </div>
                        </div>
                        <div class="col-sm-6 d-flex">
                            <label for="temperatura" class="col-sm-4 col-form-label">Temperatura</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="number" step="any" name="temperatura" id="temperatura" placeholder="Escriba la temperatura" required />
                            </div>
                        </div>
                    </div>

                    <h5 class="text-center py-4">Por favor conteste con sinceridad las siguientes preguntas, son muy importantes para prevenir cualquier contacto y/o enfermedad por COVID-19.</h5>

                    <div class="form-group row">
                        <label for="pregunta_one" class="col-sm-9 col-form-label">1. ¿Usted se encuentra con síntomas de enfermedad o problemas respiratorios?</label>
                        <div class="col-sm-3">
                            <select name="pregunta_one" id="pregunta_one" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label for="pregunta_two" class="col-sm-9 col-form-label">2. ¿Ha tenido contacto con una persona que presente síntomas gripales y que haya venido del extranjero?</label>
                        <div class="col-sm-3">
                            <select name="pregunta_two" id="pregunta_two" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label for="pregunta_three" class="col-sm-9 col-form-label">3. ¿Ha estado en contacto cercano con una persona diagnosticada con COVID-19?</label>
                        <div class="col-sm-3">
                            <select name="pregunta_three" id="pregunta_three" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label for="" class="col-sm-12 col-form-label">4. ¿Presenta alguno de los siguientes síntomas?</label>
                        
                        <label for="fiebre" class="col-sm-2 col-form-label">Fiebre</label>
                        <div class="col-sm-2 mb-3">
                            <select name="fiebre" id="fiebre" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                        <label for="tos" class="col-sm-2 col-form-label">Tos</label>
                        <div class="col-sm-2 mb-3">
                            <select name="tos" id="tos" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                        <label for="gripa" class="col-sm-2 col-form-label">Gripa</label>
                        <div class="col-sm-2 mb-3">
                            <select name="gripa" id="gripa" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                        <label for="malestar" class="col-sm-2 col-form-label">Malestar General</label>
                        <div class="col-sm-2">
                            <select name="malestar" id="malestar" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                        <label for="dolor_cabeza" class="col-sm-2 col-form-label">Dolor de cabeza</label>
                        <div class="col-sm-2">
                            <select name="dolor_cabeza" id="dolor_cabeza" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                        <label for="fatiga" class="col-sm-2 col-form-label">Fatiga</label>
                        <div class="col-sm-2">
                            <select name="fatiga" id="fatiga" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                        <label for="secrecion_nasal" class="col-sm-2 col-form-label">Secreción nasal</label>
                        <div class="col-sm-2">
                            <select name="secrecion_nasal" id="secrecion_nasal" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                        <label for="dificultad_respirar" class="col-sm-2 col-form-label">Dificultad para respirar</label>
                        <div class="col-sm-2">
                            <select name="dificultad_respirar" id="dificultad_respirar" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                        <label for="dolor_garganta" class="col-sm-2 col-form-label">Dolor de garganta</label>
                        <div class="col-sm-2">
                            <select name="dolor_garganta" id="dolor_garganta" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                        <label for="olfato_gusto" class="col-sm-2 col-form-label">Perdida del olfato o gusto</label>
                        <div class="col-sm-2">
                            <select name="olfato_gusto" id="olfato_gusto" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label for="" class="col-sm-12 col-form-label">5. ¿Presenta alguno de los siguientes factores de riesgo?</label>
                        
                        <label for="diabetes" class="col-sm-2 col-form-label">Diabetes</label>
                        <div class="col-sm-2 mb-3">
                            <select name="diabetes" id="diabetes" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                        <label for="hipertension" class="col-sm-2 col-form-label">Hipertension arterial</label>
                        <div class="col-sm-2 mb-3">
                            <select name="hipertension" id="hipertension" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                        <label for="mayor_edad" class="col-sm-2 col-form-label">Mayor de 60 años</label>
                        <div class="col-sm-2 mb-3">
                            <select name="mayor_edad" id="mayor_edad" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                        <label for="cancer" class="col-sm-2 col-form-label">Cancer</label>
                        <div class="col-sm-2">
                            <select name="cancer" id="cancer" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                        <label for="inmunodeficiencia" class="col-sm-2 col-form-label">Inmunodeficiencia (incluyendo VIH)</label>
                        <div class="col-sm-2">
                            <select name="inmunodeficiencia" id="inmunodeficiencia" class="form-control" required>
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                    </div>

                    <input type="hidden" value="{{ Request::path() == 'control_ingreso/funcionarios' ? 'Funcionario' : 'Cliente' }}" name="tipo" />

                    <input type="hidden" value="" name="control_ingreso_id" id="control_ingreso_id" />

                    <div class="mt-3">
                        <button class="btn btn-success btn-lg waves-effect waves-light" type="submit">Registrar</button>
                    </div> 

                </form>   
            </div>
        </div>
    </div>
</div>

{{-- Modal Historial --}}
<div class="modal fade bs-example-modal-lg" id="historialIngresos" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-historial-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-historial-body">
                
            </div>
        </div>
    </div>
</div>
@endsection







