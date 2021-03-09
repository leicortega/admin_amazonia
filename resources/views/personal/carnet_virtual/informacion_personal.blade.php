@extends('personal.carnet_virtual.layout.app')

@section('title','Personal')
@section('content')
    <div class="account-page-full-height bg-primary">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-xl-3 bg-white vh-100">
                    <div class="account-page-full-height">
                        <div class="p-3 mt-5">
                            <div>
                                <div class="text-center py-4">
                                    <a href="/" class="logo logo-dark">
                                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="50">
                                    </a>
                                </div>
                                <div class="text-left p-3">
                                    <h4 class="font-18 text-center">Bienvenido</h4>
                                    <p class="text-muted text-center">Plataforma de información del personal.</p>
            
                                    <form class="form-horizontal mt-5" action="/informacion/personal" method="GET">          
                                        <div class="form-group">
                                            <label for="identificacion">Identificación</label>
                                            <input type="text" class="form-control" id="identificacion" name="identificacion" placeholder="Ingrese la identificación" value="{{$identification!=0?$identification:''}}" required>
                                            @isset($msg)
                                                <div class="alert alert-danger" role="alert">
                                                    {{$msg}}
                                                </div>
                                            @endisset
                                        </div>
                                        
                                        <div class="form-group row text-center">
                                            <div class="col-12">
                                                <button class="btn btn-primary w-md waves-effect waves-light" id="button_buscar_personal" type="submit">Buscar</button>
                                            </div>
                                        </div>
                            
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="text-white">
                        <div class="row">
                            @if ($identification==0 || $qr=='')
                                <div class="col-xl-12 text-center" id="content-logo">
                                    <div class="text-center account-process p-4">
                                        <img src="{{ asset('assets/images/logo-light.png') }}">
                                        {{-- <h4 class="text-white h6 mt-2">Busca un usuario</h4> --}}
                                    </div>
                                </div>
                            @else
                                <div class="col-xl-12 text-center" id="content-qr">
                                    <div class="text-center account-process p-4">
                                        {!!QrCode::size(400)->generate(Request::root()."/informacion/personal/".$qrcrypt) !!}
                                        <p class="text-white h6 mt-2">Escanear el Codigo QR para  <a href="{{Request::root()}}/informacion/personal/{{$qrcrypt}}" target="_blanck">ver la información.</a></p>
                                    </div>
                                </div>
                            @endif
                            

                        

                        </div>
                    </div>
                    @include('personal.carnet_virtual.layout.footer')
                </div>
                

                
            </div>
            <!-- end row -->
        </div>
        <!-- end container-fluid -->
    </div>
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/informacion_personal.js') }}"></script>
@endsection