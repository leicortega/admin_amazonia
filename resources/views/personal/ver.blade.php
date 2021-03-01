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


                                <a href="/personal/datos-personal"><button type="button" class="btn btn-dark btn-lg mb-2" onclick="cargarbtn(this)">Atras</button></a>

                                <a href="/informacion/personal?identificacion={{ $personal->identificacion }}" class="btn btn-primary btn-lg mb-2 ml-2 float-right" target="_blank"><i class="uim fa fa-qrcode"></i> Qr</a>
                                <button type="button" class="btn btn-primary btn-lg mb-2 ml-2 float-right" data-toggle="modal" data-target="#editar_personal_modal">Editar</button>
                                <button type="button" class="btn btn-primary btn-lg mb-2 mr-2 float-right" onclick="generar_clave({{ $personal->identificacion }}, this,{{ $personal->id }})">Clave</button>

                                @if (session()->has('update') && session('update') == 1)
                                    <div class="alert alert-primary">
                                        Personal actualizado correctamente
                                    </div>
                                @endif

                                @if (session()->has('create') && session('create') == 1)
                                    <div class="alert alert-primary">
                                        clave creada correctamente
                                    </div>
                                @endif

                                @if (session()->has('cargo') && session('cargo') == 1)
                                    <div class="alert alert-primary">
                                        Cargo agregado correctamente
                                    </div>
                                @endif

                                @if (session()->has('cargo_delete') && session('cargo_delete') == 1)
                                    <div class="alert alert-primary">
                                        Cargo eliminado correctamente
                                    </div>
                                @endif
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center table-bg-dark">
                                            <th colspan="6">Personal <b>{{ $personal->nombres }}</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="table-bg-dark"><b>Identifiacion</b></td>
                                            <td>{{ $personal->identificacion }}</td>
                                            <td class="table-bg-dark"><b>Nombre</b></td>
                                            <td>{{ $personal->nombres }} {{ $personal->primer_apellido }} {{ $personal->segundo_apellido ?? '' }}</td>
                                            <td colspan="2" rowspan="5">
                                                <form action="/personal/agg_cargo_personal" method="POST" onsubmit="cargarbtn('#agregar_cargo_ve')">
                                                    @csrf

                                                    <div class="form-row align-items-center">
                                                        <div class="col-auto">
                                                            <div class="mt-3 mr-sm-2">
                                                                <label class="sr-only" for="inlineFormInput">Name</label>
                                                                <select name="cargos_id" class="form-control" id="cargos_id" required>
                                                                    <option value="">Seleccione un cargo</option>
                                                                    @foreach (\App\Models\Sistema\Cargo::all() as $item)
                                                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" value="{{ $personal->id }}" name="personal_id" id="personal_id">
                                                        <input type="hidden" value="1" name="view_ver" id="view_ver">
                                                        <div class="col-auto mt-3 mr-sm-2">
                                                            <button type="submit" class="btn btn-primary" id="agregar_cargo_ve"> Agregar cargo</button>
                                                        </div>
                                                    </div>
                                                </form>

                                                <table class="table table-bordered mt-3 mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($personal->cargos_personal as $cargo)
                                                        <tr>
                                                            <th scope="row">{{ $cargo->cargos['nombre'] }}</th>
                                                            <td><a href="/personal/delete_cargo_personal/{{ $cargo->id }}"><button type="button" class="btn btn-danger btn-sm" onclick="cargarbtn(this)"><i class="fa fa-trash"></i></button></a></td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Sexo</b></td>
                                            <td>{{ $personal->sexo }}</td>
                                            <td class="table-bg-dark"><b>RH</b></td>
                                            <td>{{ $personal->rh }}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Telefono</b></td>
                                            <td>{{ $personal->telefonos }}</td>
                                            <td class="table-bg-dark"><b>Correo</b></td>
                                            <td>{{ $personal->correo }}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-bg-dark"><b>Estado</b></td>
                                            <td>{{ $personal->estado }}</td>
                                            <td class="table-bg-dark"><b>Vinculacion</b></td>
                                            <td>{{ $personal->tipo_vinculacion }}</td>
                                        </tr>
                                        <tr>
                                            <td  class="table-bg-dark"><b>Direccion</b></td>
                                            <td >{{ $personal->direccion }}</td>
                                            <td class="table-bg-dark"><b>Firma</b></td>
                                            <td >
                                                @if ($personal->firma != "" && $personal->firma != "")
                                                    <button class="btn btn-primary" data-toggle="modal" data-target="#verFirma" target="_blank">
                                                        Ver
                                                    </button>
                                                    <a href="{{ asset('storage/'.$personal->firma) }}" download class="btn btn-secondary" target="_blank">
                                                        Descargar
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div id="accordion" class="col-12">
                                {{-- TAB CONTRATO LABORAL --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_contratos({{ $personal->id }})" data-parent="#accordion" href="#collapseContratos" aria-expanded="false" aria-controls="collapseContratos" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">CONTRATO LABORAL</h5>
                                        </div>
                                    </a>

                                    <div id="collapseContratos" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" data-toggle="modal" data-target="#agg_contrato"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Tipo Contrato</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Fecha Inicio</th>
                                                        <th class="text-center table-bg-dark">Fecha Final</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                        <th class="text-center table-bg-dark">Otro si</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_contratos">
                                                    <tr>
                                                        <td colspan="6" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB HOJA DE VIDA --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('HOJA DE VIDA', 'content_table_hoja_vida', {{ $personal->id }})" data-parent="#accordion" href="#collapseHojaVida" aria-expanded="false" aria-controls="collapseHojaVida" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">HOJA DE VIDA</h5>
                                        </div>
                                    </a>

                                    <div id="collapseHojaVida" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('HOJA DE VIDA', 'content_table_hoja_vida')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_hoja_vida">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB CÉDULA DE CIUDADANÍA --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('CÉDULA DE CIUDADANÍA', 'content_table_cedula', {{ $personal->id }})" data-parent="#accordion" href="#collapseCedula" aria-expanded="false" aria-controls="collapseCedula" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">CÉDULA DE CIUDADANÍA</h5>
                                        </div>
                                    </a>

                                    <div id="collapseCedula" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('CÉDULA DE CIUDADANÍA', 'content_table_cedula')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_cedula">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB LICENCIA DE CONDUCCIÓN --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('LICENCIA DE CONDUCCIÓN', 'content_table_licencia', {{ $personal->id }})" data-parent="#accordion" href="#collapseLicencia" aria-expanded="false" aria-controls="collapseLicencia" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">LICENCIA DE CONDUCCIÓN</h5>
                                        </div>
                                    </a>

                                    <div id="collapseLicencia" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('LICENCIA DE CONDUCCIÓN', 'content_table_licencia')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_licencia">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB RUNT --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('RUNT', 'content_table_runt', {{ $personal->id }})" data-parent="#accordion" href="#collapseRunt" aria-expanded="false" aria-controls="collapseRunt" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">RUNT</h5>
                                        </div>
                                    </a>

                                    <div id="collapseRunt" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('RUNT', 'content_table_runt')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_runt">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB SIMIT --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('SIMIT', 'content_table_simit', {{ $personal->id }})" data-parent="#accordion" href="#collapseSimit" aria-expanded="false" aria-controls="collapseSimit" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">SIMIT</h5>
                                        </div>
                                    </a>

                                    <div id="collapseSimit" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('SIMIT', 'content_table_simit')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_simit">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB COMPETENCIA --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('COMPETENCIA', 'content_table_competencia', {{ $personal->id }})" data-parent="#accordion" href="#collapseCompetencia" aria-expanded="false" aria-controls="collapseCompetencia" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">COMPETENCIA</h5>
                                        </div>
                                    </a>

                                    <div id="collapseCompetencia" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('COMPETENCIA', 'content_table_competencia')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_competencia">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB CERTIFICADO DE MANEJO DEFENSIVO --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('CERTIFICADO DE MANEJO DEFENSIVO', 'content_table_manejo_defensivo', {{ $personal->id }})" data-parent="#accordion" href="#collapseManejo" aria-expanded="false" aria-controls="collapseManejo" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">CERTIFICADO DE MANEJO DEFENSIVO</h5>
                                        </div>
                                    </a>

                                    <div id="collapseManejo" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('CERTIFICADO DE MANEJO DEFENSIVO', 'content_table_manejo_defensivo')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_manejo_defensivo">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB EXPERIENCIA LABORAL --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('EXPERIENCIA LABORAL', 'content_table_experiencia_laboral', {{ $personal->id }})" data-parent="#accordion" href="#collapseExperiencia" aria-expanded="false" aria-controls="collapseExperiencia" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">EXPERIENCIA LABORAL</h5>
                                        </div>
                                    </a>

                                    <div id="collapseExperiencia" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('EXPERIENCIA LABORAL', 'content_table_experiencia_laboral')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_experiencia_laboral">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB OTROS SOPORTES HV --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('OTROS SOPORTES HV', 'content_table_soportes_hv', {{ $personal->id }})" data-parent="#accordion" href="#collapseSoportesHV" aria-expanded="false" aria-controls="collapseSoportesHV" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">OTROS SOPORTES HV</h5>
                                        </div>
                                    </a>

                                    <div id="collapseSoportesHV" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('OTROS SOPORTES HV', 'content_table_soportes_hv')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_soportes_hv">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB CONSENTIMIENTO TOMA DE EXAMEN --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('CONSENTIMIENTO TOMA DE EXAMEN', 'content_table_consentimiento_examen', {{ $personal->id }})" data-parent="#accordion" href="#collapseConsentimiento" aria-expanded="false" aria-controls="collapseConsentimiento" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">CONSENTIMIENTO TOMA DE EXAMEN</h5>
                                        </div>
                                    </a>

                                    <div id="collapseConsentimiento" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('CONSENTIMIENTO TOMA DE EXAMEN', 'content_table_consentimiento_examen')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_consentimiento_examen">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB EXAMENES MEDICOS --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('EXAMENES MEDICOS', 'content_table_examenes_medicos', {{ $personal->id }})" data-parent="#accordion" href="#collapseExamenesMedicos" aria-expanded="false" aria-controls="collapseExamenesMedicos" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">EXAMENES MEDICOS</h5>
                                        </div>
                                    </a>

                                    <div id="collapseExamenesMedicos" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('EXAMENES MEDICOS', 'content_table_examenes_medicos')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_examenes_medicos">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB SEGURIDAD SOCIAL --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('SEGURIDAD SOCIAL', 'content_table_seguridad_social', {{ $personal->id }})" data-parent="#accordion" href="#collapseSeguridadSocial" aria-expanded="false" aria-controls="collapseSeguridadSocial" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">SEGURIDAD SOCIAL</h5>
                                        </div>
                                    </a>

                                    <div id="collapseSeguridadSocial" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('SEGURIDAD SOCIAL', 'content_table_seguridad_social')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_seguridad_social">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB CONTRATO DE TRABAJO --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('CONTRATO DE TRABAJO', 'content_table_contrato_trabajo', {{ $personal->id }})" data-parent="#accordion" href="#collapseContratoTrabajo" aria-expanded="false" aria-controls="collapseContratoTrabajo" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">CONTRATO DE TRABAJO</h5>
                                        </div>
                                    </a>

                                    <div id="collapseContratoTrabajo" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('CONTRATO DE TRABAJO', 'content_table_contrato_trabajo')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_contrato_trabajo">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB PERFIL DEL CARGO --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('PERFIL DEL CARGO', 'content_table_perfil_cargo', {{ $personal->id }})" data-parent="#accordion" href="#collapsePerfilCargo" aria-expanded="false" aria-controls="collapsePerfilCargo" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">PERFIL DEL CARGO</h5>
                                        </div>
                                    </a>

                                    <div id="collapsePerfilCargo" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('PERFIL DEL CARGO', 'content_table_perfil_cargo')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_perfil_cargo">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB TEMAS DE INDUCCION --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('TEMAS DE INDUCCION', 'content_table_temas_induccion', {{ $personal->id }})" data-parent="#accordion" href="#collapseTemasInduccion" aria-expanded="false" aria-controls="collapseTemasInduccion" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">TEMAS DE INDUCCION</h5>
                                        </div>
                                    </a>

                                    <div id="collapseTemasInduccion" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('TEMAS DE INDUCCION', 'content_table_temas_induccion')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_temas_induccion">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB EVALUACIÓN DE INDUCCIÓN --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('EVALUACIÓN DE INDUCCIÓN', 'content_table_evaluacion_induccion', {{ $personal->id }})" data-parent="#accordion" href="#collapseEvaluacionInduccion" aria-expanded="false" aria-controls="collapseEvaluacionInduccion" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">EVALUACIÓN DE INDUCCIÓN</h5>
                                        </div>
                                    </a>

                                    <div id="collapseEvaluacionInduccion" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('EVALUACIÓN DE INDUCCIÓN', 'content_table_evaluacion_induccion')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_evaluacion_induccion">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB EVALUACION TEORICA --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('EVALUACION TEORICA', 'content_table_evaluacion_teorica', {{ $personal->id }})" data-parent="#accordion" href="#collapseEvaluacionTeorica" aria-expanded="false" aria-controls="collapseEvaluacionTeorica" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">EVALUACION TEORICA</h5>
                                        </div>
                                    </a>

                                    <div id="collapseEvaluacionTeorica" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('EVALUACION TEORICA', 'content_table_evaluacion_teorica')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_evaluacion_teorica">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB EVALUACIÓN PRACTICA --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('EVALUACIÓN PRACTICA', 'content_table_evaluacion_practica', {{ $personal->id }})" data-parent="#accordion" href="#collapseEvaluacionPractica" aria-expanded="false" aria-controls="collapseEvaluacionPractica" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">EVALUACIÓN PRACTICA</h5>
                                        </div>
                                    </a>

                                    <div id="collapseEvaluacionPractica" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('EVALUACIÓN PRACTICA', 'content_table_evaluacion_practica')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_evaluacion_practica">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB TRATAMIENTO DATOS PERSONALES --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('TRATAMIENTO DATOS PERSONALES', 'content_table_datos_personales', {{ $personal->id }})" data-parent="#accordion" href="#collapseDatosPersonales" aria-expanded="false" aria-controls="collapseDatosPersonales" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">TRATAMIENTO DATOS PERSONALES</h5>
                                        </div>
                                    </a>

                                    <div id="collapseDatosPersonales" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('TRATAMIENTO DATOS PERSONALES', 'content_table_datos_personales')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_datos_personales">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB SEGUMIENTO EMPLEADO --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('SEGUMIENTO EMPLEADO', 'content_table_seguimiento_empleado', {{ $personal->id }})" data-parent="#accordion" href="#collapseSeguimientoEmpleado" aria-expanded="false" aria-controls="collapseSeguimientoEmpleado" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">SEGUMIENTO EMPLEADO</h5>
                                        </div>
                                    </a>

                                    <div id="collapseSeguimientoEmpleado" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('SEGUMIENTO EMPLEADO', 'content_table_seguimiento_empleado')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_seguimiento_empleado">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB TERMINACIÓN CONTRATO LABORAL --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('TERMINACIÓN CONTRATO LABORAL', 'content_table_terminacion_contrato', {{ $personal->id }})" data-parent="#accordion" href="#collapseTerminacionContrato" aria-expanded="false" aria-controls="collapseTerminacionContrato" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">TERMINACIÓN CONTRATO LABORAL</h5>
                                        </div>
                                    </a>

                                    <div id="collapseTerminacionContrato" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('TERMINACIÓN CONTRATO LABORAL', 'content_table_terminacion_contrato')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_terminacion_contrato">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB NOTIFICACIÓN EXAMEN MÉDICO DE RETIRO --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('NOTIFICACIÓN EXAMEN MÉDICO DE RETIRO', 'content_table_examen_retiro', {{ $personal->id }})" data-parent="#accordion" href="#collapseExamenRetiro" aria-expanded="false" aria-controls="collapseExamenRetiro" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">NOTIFICACIÓN EXAMEN MÉDICO DE RETIRO</h5>
                                        </div>
                                    </a>

                                    <div id="collapseExamenRetiro" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('NOTIFICACIÓN EXAMEN MÉDICO DE RETIRO', 'content_table_examen_retiro')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_examen_retiro">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB SEGUIMIENTO --}}
                                <div class="card mb-0">
                                    <a data-toggle="collapse" onclick="cargar_documentos('SEGUIMIENTO', 'content_table_seguimiento', {{ $personal->id }})" data-parent="#accordion" href="#collapseSeguimiento" aria-expanded="false" aria-controls="collapseSeguimiento" class="text-dark collapsed">
                                        <div class="card-header bg-dark" id="headingOne">
                                            <h5 class="m-0 font-size-14 text-white">SEGUIMIENTO</h5>
                                        </div>
                                    </a>

                                    <div id="collapseSeguimiento" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">

                                            <button class="btn btn-info waves-effect waves-light mb-2 float-right" onclick="modal_agg_documento('SEGUIMIENTO', 'content_table_seguimiento')"><i class="fas fa-plus"></i></button>

                                            <table class="table table-bordered">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th class="text-center table-bg-dark">No</th>
                                                        <th class="text-center table-bg-dark">Expedicion</th>
                                                        <th class="text-center table-bg-dark">Inicio Vigencia</th>
                                                        <th class="text-center table-bg-dark">Fin Vigencia</th>
                                                        <th class="text-center table-bg-dark">Dias</th>
                                                        <th class="text-center table-bg-dark">Observación</th>
                                                        <th class="text-center table-bg-dark">Estado</th>
                                                        <th class="text-center table-bg-dark">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="content_table_seguimiento">
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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




{{-- Ver firma --}}
<div class="modal fade bs-example-modal-xl" id="verFirma" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="agg_documento_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <iframe src="{{route('index')}}/storage/{{$personal->firma}}" style="width:100% ;height:500px;" frameborder="0"></iframe>
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
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group form-group-custom mb-4">
                                    <div class="form-group form-group-custom mb-4">
                                        <select name="sede" class="form-control" id="sede" required>
                                            <option value=""></option>
                                            <option value="Oficina principal" {{ $personal->sede == 'Oficina principal' ? 'selected' : '' }}>Oficina principal</option>
                                            <option value="Sucursal Pitalito" {{ $personal->sede == 'Sucursal Pitalito' ? 'selected' : '' }}>Sucursal Pitalito</option>
                                            <option value="Sucursal Bogota"   {{ $personal->sede == 'Sucursal Bogota' ? 'selected' : '' }}>Sucursal Bogota</option>
                                        </select>
                                        <label for="sede">Sede(*)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="container">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label class="input-group-btn">
                                                <span class="btn btn-primary btn-file">
                                                    <i class="fas fa-cloud-upload-alt"></i> <input class="d-none" name="imagen" type="file" id="avatar">
                                                </span>
                                            </label>
                                            <input class="form-control" id="avatar_captura" readonly="readonly" name="avatar_captura" type="text" value="{{basename($personal->imagen)}}">
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

                    <div class="form-group row">
                        <label for="tipo" class="col-sm-2 col-form-label">Tipo</label>
                        <div class="col-sm-10">
                            <select name="tipo" id="tipo_user" class="form-control" onchange="selectTipo(this.value)" required>
                                <option value="">Seleccione el tipo</option>
                                <option value="admin">admin</option>
                                <option value="general">general</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row d-none" id="div_permisos">
                        <label for="permiso" class="col-sm-2 col-form-label">Permiso</label>
                        <div class="col-sm-10 mt-2">
                            @foreach (\Spatie\Permission\Models\Permission::all() as $permiso)
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="{{ $permiso->name }}" name="permisos[]" value="{{ $permiso->name }}">
                                    <label class="custom-control-label" for="{{ $permiso->name }}">{{ $permiso->name }}</label>
                                </div>
                            @endforeach
                            <input type="hidden" name="user_id" id="user_id">
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
