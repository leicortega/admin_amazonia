<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cotizacion - Amazonia C&L</title>

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
<body>

    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="text-align: center;">

            <tr>
                <td rowspan="2" style="padding: 5px" width="150px">
                    {{-- <img src="{{ asset('assets/images/logo_amazonia.png') }}" alt=""> --}}
                    <img src="https://app.amazoniacl.com/images/logo_amazonia2.png" width="95px" alt="">
                </td>
                <td><b>SISTEMA INTEGRADO DE GESTION</b></td>
                <td width="150px"><b>CODIGO <br><br>SIG-F-100</b></td>
            </tr>
            <tr>
                <td><b>COTIZACION</b></td>
                <td><b>VIGENCIA <br><br>19-08-2019</b></td>
            </tr>

    </table>

    <br><br><br><br>

    <p style="line-height: 16px; text-align:justify;">
        <?php echo str_replace(array("\r\n", "\n\r", "\r", "\n"), "<br />", $cotizacion['cotizacion_parte_uno']); ?>
    </p>

    <br><br>

    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="text-align: center;line-height: 16px;">

        <tr style="background: #22852d;">
            <td style="color: #fff; border: 1px solid #000;font-weight: bold;" colspan="2">Fechas</td>
            <td style="color: #fff; border: 1px solid #000;font-weight: bold;" rowspan="2" width="350px">Descripción</td>
            <td style="color: #fff; border: 1px solid #000;font-weight: bold;" colspan="3">Costos</td>
        </tr>
        <tr style="background: #22852d;">
            <td style="color: #fff; border: 1px solid #000;font-weight: bold;" width="65px">Inicio</td>
            <td style="color: #fff; border: 1px solid #000;font-weight: bold;" width="65px">Final</td>
            <td style="color: #fff; border: 1px solid #000;font-weight: bold;">Valor Unit.</td>
            <td style="color: #fff; border: 1px solid #000;font-weight: bold;">Cant.</td>
            <td style="color: #fff; border: 1px solid #000;font-weight: bold;">Valor total</td>
        </tr>
        @php
            $cot=0;
            $a=0;
        @endphp
        @foreach ($cotiza as $cotizaciones)
        @php
            $a++;
        @endphp
        <tr>
            <td>{{ $cotizaciones['fecha_ida'] }}</td>
            <td>{{ $cotizaciones['fecha_regreso'] }}</td>
            <td style="text-align: justify; padding:5px;">
                {{ $cotizaciones['descripcion_table'] }}
            </td>
            <td>${{ number_format($cotizaciones['valor_unitario']) }}</td>
            <td>{{ $cotizaciones['cantidad'] }}</td>
            <td>${{ number_format($cotizaciones['total']) }}</td>
            @php
                $cot+=$cotizaciones['total'];
            @endphp
        </tr>
        @endforeach

            @if ($a>1)
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>Valor Total</b></td>
                    <td>{{$cot}}</td>
            @endif


    </table>

    <br><br>

    <p style="line-height: 16px; text-align:justify;">
        <?php echo str_replace(array("\r\n", "\n\r", "\r", "\n"), "<br />", $cotizacion['cotizacion_parte_dos']); ?>
    </p>

    <div class="footer">
        <table width="100%"  cellspacing="0" cellpadding="0" style="text-align: center;">

            <tr>
                <td style="padding: 5px; border-right: none;" width="150px">
                    <img src="https://app.amazoniacl.com/images/supertransporte.png" width="150px" alt="">
                </td>
                <td width="150px" style="border-right: none;">
                    <img src="https://app.amazoniacl.com/sive/img/logo_mintransporte.png" width="150px" alt="">
                </td>
                <td width="150px" style="text-align: right;line-height: 16px;">
                    Página 1 de 1 <br>Impreso por APP AMAZONIA C&L
                </td>
            </tr>

    </table>
    </div>

</body>
</html>

