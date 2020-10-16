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

                                                        <button type="button" class="btn btn-outline-secondary btn-sm" {{ !$cotizacion->tercero_id ? 'disabled' : '' }} onclick="createContrato({{ $cotizacion->id }}, {{ $cotizacion->tercero_id }})" data-toggle="tooltip" data-placement="top" title="Crear Contrato">
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

{{-- Modal Crear Cotrato --}}
<div class="modal fade bs-example-modal-xl" id="modal-crear-contrato" tabindex="-1" role="dialog" aria-labelledby="modal-blade-title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modal-title-contrato">Generar Contrato</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="/cotizaciones/generar-contrato" id="form-generar-contrato" method="POST">
                    @csrf

                    <div class="container">
                        <div class="form-group row">

                            <h5 class="col-12">RESPONSABLE</h5>

                            <div class="col-sm-12 d-flex">
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Seleccione responsable</label>
                                    <select name="select_responsable" id="select_responsable" onchange="cargar_responsable_contrato(this.value)" class="form-control" required>
                                        <option value="">Seleccione tipo</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Numero Identificación</label>
                                    <input class="form-control" type="number" name="identificacion_responsable" id="identificacion_responsable" placeholder="Escriba la identificación" required="">
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Nombre Completo</label>
                                    <input class="form-control" type="text" name="nombre_responsable" id="nombre_responsable" placeholder="Escriba el nombre" required="">
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Correo</label>
                                    <input class="form-control" type="text" name="correo_responsable" id="correo_responsable" placeholder="Escriba la correo" required="">
                                </div>
                            </div>

                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Telefono</label>
                                    <input class="form-control" type="number" name="telefono_responsable" id="telefono_responsable" placeholder="Escriba el Telefono" required="">
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Tipo Contrato</label>
                                    <select name="tipo_contrato" class="form-control" required>
                                        <option value="">Seleccione tipo</option>
                                        <option value="ASALARIADO">ASALARIADO</option>
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Objeto del contrato</label>
                                    <textarea name="objeto_contrato" rows="3" class="form-control" placeholder="Escriba el objeto del contrato" required=""></textarea>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="form-group row">

                            <h5 class="col-12">VEHICULO</h5>

                            <div class="col-sm-12 d-flex">

                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Vehiculo</label>
                                    <select name="vehiculo_id" class="form-control" required>
                                        <option value="">Seleccione vehiculo</option>
                                        @foreach (\App\Models\Vehiculo::all() as $vehiculo)
                                            <option value="{{ $vehiculo->id }}">{{ $vehiculo->placa }} - {{ $vehiculo->numero_interno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Conductor</label>
                                    <select name="conductor_id" class="form-control" required>
                                        <option value="">Seleccione vehiculo</option>
                                        @foreach (\App\Models\Cargos_personal::with('personal')->with('cargos')->whereHas('cargos', function($query) {
                                            $query->where('cargos.nombre', 'Conductor');
                                        })->get() as $conductor)
                                            <option value="{{ $conductor->personal->id }}">{{ $conductor->personal->nombres }} {{ $conductor->personal->primer_apellido }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>

                        <hr>

                        <div class="form-group row mb-3">

                            <h5 class="col-12">CONTRATO</h5>

                            <div class="col-sm-12 d-flex">
                                <div class="col-sm-12">
                                    <textarea name="contrato_parte_uno" rows="5" class="form-control" required="">Entre los suscritos a saber, AMAZONIA CONSULTORIA & LOGISTICA SAS, Identificada con Nit. 900447438-6 sociedad domiciliada en la ciudad de Neiva, representada legalmente por, JOIMER OSORIO BAQUERO, mayor de edad, vecino de Neiva - Huila, identificado con la cédula de ciudadanía No. 7706232 de Neiva Huila, quien en adelante se denominará El CONTRATISTA, por una parte, y por la otra Leiner Fabian Ortega , Identificado(a) Cédula de Ciudadania  No 1075262366 domiciliado(a)en la ciudad de Barranquilla
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 d-flex mt-2">
                                <div class="col-sm-12">
                                    <textarea name="contrato_parte_dos" rows="15" class="form-control" required="">quien en lo sucesivo se denominará El CONTRATANTE, se ha celebrado un contrato de prestación de servicios de transporte terrestre, que se rige por la legislación civil y comercial colombiana, además de las siguientes <u>CLÁUSULAS</u>: PRIMERA.OBJETO; EL CONTRATISTA, presta el servicio de transporte solicitado por el contratante en XXXXXXXXXXXXX, el cual se encontrará ajustado a las normas y especificaciones técnicas contempladas en el reglamento de uso y manejo de vehículos del CONTRATANTE y las demás exigencias establecidas de Ley. El servicio se prestará en vehículos preferiblemente XXXXXXXXXXXX con toda la documentación legal al día (SOAT, Revisión Técnico Mecánica, Póliza de daño material todo riesgo que cuente con la cobertura de responsabilidad contractual y extracontractual. Copia del último mantenimiento preventivo, el cual, por ley, se debe realizar cada dos meses, y debe venir elaborado por un ingeniero mecánico con matrícula profesional vigente o por un CDA autorizado, los vehículos deben tener llantas adecuadas en buen estado. Llanta de repuesto, espejos laterales, luces direccionales, luces de freno, freno de parqueo, pito principal, extintor operativo, botiquín de primeros auxilios, equipo de carretera, los vehículos deberán permanecer limpios y en buen estado de mantenimiento mecánico, con el fin de garantizar la seguridad del personal a movilizar. PARAGRAFO 1; Lugares de desplazamiento: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX. PARAGRAFO 2; Horarios: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXS, no obstante, podrá variarse según condiciones que se presenten en las vías y requerimientos en los desplazamientos. Previsiblemente los vehículos saldrán desde XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX SEGUNDA: PRECIO; el valor del servicio prestado será de XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX, pagaderos quince (15) días después de la radicación de la factura. PARAGRAFO: para cada servicio se llevará un control mediante un acta de servicio diario, la cual hace parte integral del contrato. TERCERA; DURACION: La vigencia del presente contrato será de XXXXXXXXXXXXXXXXXXXXXXXXXXXXX y podrá ampliarse de acuerdo a la necesidad del contratante. PARÁGRAFO 1: la prestación de servicio Público de Transporte, se facturará sin IVA teniendo en cuenta que el servicio es EXCLUIDO según el Art 476 Numeral 2 del Estatuto Tributario. CUARTA. -Los gastos de mantenimiento preventivo, correctivo, instalación sistema GPS el cual debe ser suministrado el usuario y contraseña, monitoreo del mismo, al igual el vehículo Backup cuando requiera mantenimiento preventivo o correctivo que debe ser acorde a las exigencias de CONTRATANTE, serán asumidos por el CONTRATISTA, sin que esto implique cobro alguno o adicional para el CONTRATANTE,es responsabilidad del CONTRATISTA mantener los vehículos en perfecto estado mecánico y de funcionamiento. El CONTRATISTA deberá cumplir con todas las normas legalmente establecidas en materia de tránsito y transporte, todos los vehículos deberán contar con las revisiones técnico-mecánicas actualizadas y conforme a las normas de tránsito y transporte. El CONTRATISTA mantendrá el seguro obligatorio del vehículo y seguro todo riesgo vigente. QUINTA. –El control del combustible, alimentación y hospedaje de los conductores será de responsabilidad total del CONTRATANTE. SEXTA. El vehículo será destinado exclusivamente para las labores propias del contrato. SEPTIMA: EL CONTRATISTA está en la obligación de mantener vigente toda la documentación y entregar copia al contratante que legalmente deben tener los vehículos para poder ser  operados, incluyendo las pólizas que debe tener cualquier empresa de transporte legalmente constituida: pólizas de responsabilidad civil contractual, extracontractual, seguro todo riesgo que cubra todo tipo de siniestros y SOAT o seguro obligatorio, tecno mecánica vigente. Los vehículos no podrán ser de un modelo anterior al año 2014. NOVENA: TERMINACIÓN DEL CONTRATO: El contrato se podrá dar por terminado por cualquiera de las siguientes causas: 1. Por vencimiento del plazo pactado. 2. Por incumplimiento de parte del CONTRATISTA o CONTRATANTE a cualquiera de sus obligaciones generadas y o levantamiento del servicio en la zona de operación del objeto del presente contrato- 3. Por la terminación del contrato de prestación de servicios suscrito entre Leiner Fabian Ortega y sus CLIENTES. 4. Por decisión unilateral de cualquiera de las partes. 5. Por incumplimiento del contratista a las normas o requisitos de HSEQ, establecidos por los clientes los cuales acepta haber leído y entendido antes de la firma del presente documento. 6.Por mutuo acuerdo entre las partes. DECIMA:CLAUSULA PENAL: la parte que incumpla cualquiera de las cláusulas del presente contrato incurrirá en una sanción de 10 SMLMV, la cual podrá ser exigible por la parte afectada. sin prejuicio a las acciones legales que hubiere lugar como consecuencia del incumplimiento. DECIMO PRIMERA: INDEMNIDAD: En todo caso será obligación del CONTRATISTA mantener indemne y libre al CONTRATANTE de cualquier reclamación o demanda que se llegare a presentar proveniente de terceros, que tengan como causa las actuaciones del CONTRATISTA.DECIMA SEGUNDA-Gastos: Los gastos de impuestos de timbre y demás que se ocasionen por el otorgamiento de este contrato, sus prórrogas y renovaciones, en lo no previsto en este contrato, serán asumidos por partes iguales entre los contratantes.DECIMA TERCERA-Notificaciones: Las notificaciones que cualquiera de las partes remita a la otra, deben formularse con certificación de entrega a las siguientes direcciones: El CONTRATANTE: cc 222 2 Barranquilla-Atlántico 54544 lenero;   , El CONTRATISTA: Calle 19 sur # 10 18 0f 105 Neiva Huila, Tel: 8600663 –3168756444, Email: gerencia@amazoniacl.com. DÉCIMO CUARTA-Resolución de Controversias. En caso de conflicto entre las partes de este Contrato de prestación de servicios de transporte, su ejecución y liquidación, deberá agotarse una diligencia de conciliación ante cualquier entidad autorizada para efectuarla, si esta fracasa, se llevará las diferencias ante el Juez Ordinario que sea competente.DÉCIMO QUINTA- MÉRITO EJECUTIVO: El presente contrato presta mérito ejecutivo por contener obligaciones expresas, claras y exigibles a cargo de las partes, para las reclamaciones y exigencias que se derivan del presente contrato como obligaciones del contratista de cara al contratante y viceversa, incluso éste contrato presta mérito ejecutivo en los términos del artículo 422° del Código General del Proceso para el cobro de multas y clausula penal que deba realizar el CONTRATANTE. El presente contrato se firma en la ciudad de Neiva, a los 18 días del mes de Septiembre de 2020.
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="cotizacion_id_contrato" id="cotizacion_id_contrato" />
                    <input type="hidden" name="tercero_id_contrato" id="tercero_id_contrato" />

                    <div class="mt-5 text-center">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection







