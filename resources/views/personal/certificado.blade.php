<?php
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificado Laboral {{ $contrato->personal->nombres }}</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-size: 12px;
            font-family:Arial, Helvetica, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            margin-top: 10px;
        }
        table th, table td {
            text-align: center;
            border: 1px solid;
        }
        table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
        }
        .saltopagina{
            page-break-after: always;
        }
        .footer {
            position: fixed;
            left: 0;
            bottom: 50px;
            width: 100%;
        }
    </style>

</head>
<body>

    <br><br><br><br><br><br><br>

    <div style="text-align: center;">
        <img src="{{ public_path('assets/images/logo.png') }}" width="320px;" alt="">
    </div>

    <br><br><br><br><br><br>

    <p style="text-align: center"><b>CERTIFICA</b></p>

    <br><br><br><br><br>

    <p style="text-align: justify; font-size: 14px;">
        Que el señor <span style="text-transform: uppercase"><b>{{ $contrato->personal->nombres }} {{ $contrato->personal->primer_apellido }} {{ $contrato->personal->segundo_apellido ?? '' }}</b></span> con {{ $contrato->personal->tipo_identificacion }} No <b>{{ $contrato->personal->identificacion }}</b>, labora en nuestra empresa mediante contrato de <b>{{ $contrato->tipo_contrato }}</b> desde el <b>{{ $contrato->fecha_inicio }}</b> <?php echo ($contrato->fecha_fin) ? 'hasta el <b>'.$contrato->fecha_fin.'</b>' : '' ?>, en el cargo de <b>{{ $contrato->cargo }}</b>, demostrando responsabilidad y seriedad en las labores encomendadas.
        {{-- {{ $otro_si['descripcion'] }} --}}

        <br>
        <br>
        Dado en Neiva a los {{ \Carbon\Carbon::now()->format('d') }} días del mes de {{ $meses[\Carbon\Carbon::now()->format('m') - 1] }} de {{ \Carbon\Carbon::now()->format('Y') }} a solicitud del interesado.
    </p>

    <br><br><br><br><br><br>

    <div style="text-align: center;">
        <img src="{{ public_path('assets/images/firma_mauricio.png') }}" width="320px;" alt="">
    </div>

    <table class="footer" style="border: none; margin-top:60px; font-size: 14px;">
        <tbody style="justify-content: space-between !important;">
            <tr style="border: none;">
                <td style="border: none;text-align:left;">
                    <p>
                        <b>
                            Neiva Calle 19 Sur NO 10-18 Tel: 098 8600663 Cel: 315 928 0528 <br>
                            El reaujil Calle 6 No 5-52 Centro Cel: 316 8756699 <br>
                            E-mail: info@amazoniacl.com gerencia@amazonia.com <br>
                            www.amazoniacl.com <br>
                        </b>
                    </p>
                </td>
                <td style="border: none;">
                    <img src="{{ public_path('assets/images/logo2.jpg') }}" alt="">
                </td>
            </tr>
        </tbody>
    </table>

</body>
</html>
