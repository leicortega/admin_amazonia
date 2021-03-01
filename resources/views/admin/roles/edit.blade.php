@section('title','Roles')

@extends('layouts.app')

@section('jsMain')
    <script src="{{ asset('assets/js/admin.js') }}"></script>
@endsection

@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
            
                        <h4 class="header-title mb-3">Roles</h4>

                        @if (session()->has('update') && session('update') == 2)
                            <div class="alert alert-primary" role="alert">
                                Rol editado correctamente
                            </div>
                        @endif
                        <a href="{{route('roles.index')}}" class="btn btn-secondary my-3 btn-lg">Atras</a>
                        <form action="{{route('roles.update',$role->id)}}" id="form_clave" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input class="form-control" type="text" name="name" id="name" value="{{$role->name}}" placeholder="Escriba el nombre" required />
                            </div>


                            <hr>
                            <h3>Permisos</h3>      
                            <hr>
                            <h3>Lista de permisos</h3>
                            <div class="form-group">
                                @foreach($permissions as $permission)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="{{$permission->name}}" name="permisos[]" value="{{$permission->name}}" {{$role->hasPermissionTo($permission->name)?'checked':''}}>
                                        <label class="form-check-label" for="{{$permission->name}}">{{$permission->name}}</label>
                                    </div>
                                
                                @endforeach
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Actualizar</button>
                            </div>
                            
                        </form>
                        

                    </div>
                </div>
            </div>

        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>
@endsection
