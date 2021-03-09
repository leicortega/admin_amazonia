@extends('personal.carnet_virtual.layout.app')

@section('title','Carnet Virtual')
@section('content')
    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern shadow-none">
                        <div class="card-body">
                            <div class="text-center mt-4">
                                <div class="mb-3">
                                    <a href="/" class="logo logo-dark">
                                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="50">
                                    </a>
                
                                </div>
                            </div>
                            <div class="p-3"> 
                                <h4 class="font-18 text-center">Información</h4>
                                <form class="form-horizontal" action="index.html">
                                    <div class="user-thumb text-center mb-4">
                                        @if ($persona->imagen)
                                            <img src="{{ asset('storage/'.$persona->imagen )}}" class="rounded-circle img-thumbnail thumb-lg" alt="Foto">
                                        @else
                                            <img src="{{ asset('assets/images/perfil.jpg') }}"class="rounded-circle img-thumbnail thumb-lg"  alt="Foto" >
                                        @endif
                                        <h6 class="mt-3">{{$persona->nombres}} {{$persona->primer_apellido}} {{$persona->segundo_apellido}}</h6>
                                    </div>

                                    <hr>

                                    <div class="user-thumb text-center mb-4">
                                        <h6 class="mt-3">Identificacion: {{$persona->identificacion}}</h6>
                                    </div>
                                    <div class="user-thumb text-center mb-4">
                                        <h6 class="mt-3">Sede: {{$persona->sede}}</h6>
                                    </div>
                                    <div class="user-thumb text-center mb-4">
                                        @if (count($persona->cargos_personal)>0)
                                            <h6 class="mt-3">Cargo: 
                                                </br>
                                                @foreach ($persona->cargos_personal as $cargo)
                                                    {{$cargo->cargos->nombre}}</br>
                                                @endforeach
                                                
                                            </h6>
                                        @else
                                            <h6 class="mt-3">Sin cargos</h6>
                                        @endif
                                        
                                    </div>
                                    <div class="user-thumb text-center mb-4">
                                        <h6 class="mt-3">Estado: {{$persona->estado}}</h6>
                                    </div>
                                    <div class="user-thumb text-center mb-4">
                                        <h6 class="mt-3">RH: {{$persona->rh}}</h6>
                                    </div>
                                </form>
            
                            </div>
                
                        </div>
                    </div>
                    @include('personal.carnet_virtual.layout.footer')
                </div>
            </div>
        </div>
    </div>
@endsection
