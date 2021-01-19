@section('title') Vehiculo  @endsection

@section('MainCSS')
    <link href="{{ asset('assets/css/timeline.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('jsMain')
    <script src="{{ asset('assets/js/vehiculos.js') }}"></script>
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
                            <div class="table-responsive mb-3">

                                @if ($errors->any())
                                    <div class="alert alert-danger mb-0" role="alert">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <a href="/vehiculos/ver/{{ $id }}"><button type="button" class="btn btn-dark btn-lg mb-2">Atras</button></a>

                                <div class="container mt-5">
                                    <div class="row my-5">
                                        <ul class="timeline" id="timeline">
                                            @foreach ($trazabilidad as $item)
                                                {{-- INSPECCIÓN --}}
                                                <li class="li complete">
                                                    <a href="/vehiculos/inspecciones/ver/{{ $item->inspeccion->id }}">
                                                        <div class="timestamp">
                                                            <span class="author">{{ App\User::find($item->inspeccion->users_id)->name }}</span>
                                                            <span class="date">{{ date("d/m/Y H:m:s", strtotime($item->inspeccion->fecha_inicio)) }}<span>
                                                        </div>
                                                        <div class="status">
                                                            <h4> Inspección </h4>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="li {{ $item->inspeccion->certificado ? 'complete' : '' }}">
                                                    <a href="{{ $item->inspeccion->certificado ? '/vehiculos/inspecciones/certificado/'.$item->inspeccion->id : '/vehiculos/inspecciones/ver/'.$item->inspeccion->id }}" {{ $item->inspeccion->certificado ? 'target="_blank"' : '' }}>
                                                        <div class="timestamp">
                                                            <span class="author">{{ $item->inspeccion->certificado ? 'Certificado generado' : 'Aun no hay certificado' }}</span>
                                                            <span class="date">{{ $item->inspeccion->certificado ? 'Ver certificado' : 'Generear certificado' }}<span>
                                                        </div>
                                                        <div class="status">
                                                            <h4> Certificado Hallazgos</h4>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="li {{ $item->mantenimiento->persona_autoriza ? 'complete' : '' }}">
                                                    <a href="/vehiculos/ver/mantenimiento/{{ $item->mantenimiento->id }}">
                                                        <div class="timestamp">
                                                            <span class="author">{{ App\Models\Personal::find($item->mantenimiento->personal_id)->nombres }}</span>
                                                            <span class="date">{{ $item->mantenimiento->persona_autoriza ? 'Autorizado' : 'Sin Autorizar' }}<span>
                                                        </div>
                                                        <div class="status">
                                                            <h4> Mantenimiento </h4>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="li {{ $item->mantenimiento->persona_cierre ? 'complete' : '' }}">
                                                    <a href="/vehiculos/print/mantenimiento/{{ $item->mantenimiento->id }}" target="_blank">
                                                        <div class="timestamp">
                                                            <span class="author">{{ $item->mantenimiento->persona_cierre ?? 'Aun no hay cierre' }}</span>
                                                            <span class="date">{{ ($item->mantenimiento->fecha_cierre != '') ? date("d/m/Y H:m:s", strtotime($item->mantenimiento->fecha_cierre)) : 'Mantenimiento sin cierre' }}<span>
                                                        </div>
                                                        <div class="status">
                                                            <h4> Cierre </h4>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    {{ $trazabilidad->links() }}
                                </div>

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
@endsection
