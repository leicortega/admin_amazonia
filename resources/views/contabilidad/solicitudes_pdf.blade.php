<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Solicitudes PDF - Amazonia C&L</title>

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

        <!-- datepicker -->
        <link href="{{ asset('assets/libs/air-datepicker/css/datepicker.min.css') }}" rel="stylesheet" type="text/css" />

        {{-- SELECTIZE --}}
        <link href="{{ asset('assets/libs/selectize/css/selectize.css') }}" rel="stylesheet" type="text/css" />

        <!-- jvectormap -->
        <link href="{{ asset('assets/libs/jqvmap/jqvmap.min.css') }}" rel="stylesheet" />

        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <style>

        body {
            font-size: 12px;
            padding: 0px 0px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            line-height: 8px;
            height: 100%;
            width: 100%;
            color: #000000;
        }
        .footer {
            position: fixed;
            left: 0;
            bottom: 20px;
            text-align: center;
        }
    </style>

</head>

    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="text-align: center;">

            <tr>
                <td rowspan="2" style="padding: 5px" width="150px">
                    {{-- <img src="{{ asset('assets/images/logo_amazonia.png') }}" alt=""> --}}
                    <img src="https://app.amazoniacl.com/images/logo_amazonia2.png" width="95px" alt="">
                </td>
                <td><b>SISTEMA INTEGRADO DE GESTION</b></td>
                <td width="150px"><b>CODIGO <br><br>SIG-F-82</b></td>
            </tr>
            <tr>
                <td><b>SOLICITUD DE DINERO</b></td>
                <td><b>VIGENCIA <br><br>05-06-2019</b></td>
            </tr>

    </table>

    <br><br><br><br>

    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="text-align: center;">
            
                <tr class="">
                    <td style="padding: 10px" width="150px"> <b>Tipo Solicitud</b></td>
                    <td> <b>Fecha Y Hora</b></td>
                    <td> <b>Solicitante</b></td>
                    <td> <b>Beneficiario</b></td>
                </tr>

                <tr>
                    <td style="padding: 10px" width="150px">{{$solicitud->tipo_solicitud}}</td>
                    <td>{{$solicitud->fecha_solicitud}}</td>
                    <td>{{$solicitud->name}}</td>
                    <td>{{$solicitud->nombres}} {{$solicitud->primer_apellido}} {{$solicitud->segundo_apellido}}</td>
                </tr>
                <tr>
                    <td style="padding: 10px" width="150px" colspan='4'> <b>Descripcion</b></td>
                </tr>
                <tr>
                    <td style="padding: 10px; line-height:135%" width="150px" colspan='4' >{{$solicitud->descripcion}} </td>
                </tr>
                <tr>
            
    </table>

    <br><br><br><br>


    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="text-align: center;">
        
                <tr>
                    <td style="padding: 10px" width="150px" colspan='5' class="align-middle"><h5>Descripcion Productos Y Servicios</h5></td>
                </tr>
                <tr>
                    <td style="padding: 10px" width="150px"><b>Concepto</b></td>
                    <td><b>Valor</b></td>
                    <td><b>Fecha De Creación</b></td>
                    <td><b>Estado</b></td>
                    <td><b>Descripción De Estado</b></td>
                </tr>


        @php
            $valor_total=0;
        @endphp

            @foreach($conceptos as $concepto)
                @php
                    $valor_total=$valor_total+$concepto->valor_entregado;
                    $estado= (\App\Models\Estados_solicitud::where('conceptos_id', $concepto->id)->orderBy('created_at', 'desc')->first());
                @endphp
                <tr>

                    <td style="padding: 10px" width="150px">{{$concepto->nombre}}</td>

                    <td>{{$concepto->valor_entregado}}</td>

                    <td>{{$concepto->created_at}}</td>

                    <td>{{$estado->estado}} </td>

                    <td>{{$estado->descripcion}}</td>

                </tr>
            @endforeach

            <tr>
                <td style="padding: 10px" width="150px" align=center><b>Total</b></td>
                <td>{{$valor_total}}</td>
            <tr>

                

    </table>


<div align=center style="margin-top:100px">

    <div style="width:15%; margin-right:20%; margin-left:7%" class="float-left">
        <hr style="border:0.5px solid black">
        <span>Firma quien recibe.</span>
    </div>
    <div style="width:15%; margin-right:20%" class="float-left">
        <hr style="border:0.5px solid black">
        <span>Firma quien aprueba</span>
    </div>
    <div style="width:15%" class="float-left">
        <hr style="border:0.5px solid black">
        <span>Firma contabildad.</span>
    </div>

</div>