@section('title') Dashnoard @endsection

@extends('layouts.app')

@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h5>Bienvenido</h5>
                                <p class="text-muted">{{ Auth::user()->name }}</p>
                            </div>
                        </div>
                        <div class="row mt-5">

                            <iframe src="http://docs.google.com/gview?url=http://admin.amazoniacl.com/assets/covid/PREGUNTAS_Y_RESPUESTAS_COVID_19.pptx&embedded=true" style="width:100% ;height:500px;" frameborder="0"></iframe>

                            <video class="col-12 mt-3" controls>
                                <source src="{{ asset('assets/covid/protocolo_conductores.mp4') }}" type="video/mp4">
                            </video>

                            <video class="col-12 mt-3" controls>
                                <source src="{{ asset('assets/covid/protocolo_limpieza.mp4') }}" type="video/mp4">
                            </video>
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- end row -->

    </div> <!-- container-fluid -->
</div>
@endsection