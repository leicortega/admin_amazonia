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

                            <iframe src="http://docs.google.com/gview?url=http://admin.amazoniacl.com/assets/covid/PREGUNTAS_Y_RESPUESTAS_COVID_19.pptx&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- end row -->

    </div> <!-- container-fluid -->
</div>
@endsection