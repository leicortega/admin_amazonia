<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mantenimiento PDF - Amazonia C&L</title>

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
                <td width="150px"><b>CODIGO <br><br>SIG-F-82</b></td>
            </tr>
            <tr>
                <td><b>MANTENIMIENTO DE VEHICULOS</b></td>
                <td><b>VIGENCIA <br><br>05-06-2019</b></td>
            </tr>

    </table>

    <br><br><br><br>

    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="padding: 5px;line-height: 16px;">

        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="4">Fecha y hora de diligenciamiento: {{ $mantenimiento->fecha }}</td>
        </tr>

        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="1">No interno: </td>
            <td style="color: #000000; border: 1px solid #000;padding: 5px;" colspan="1"></td>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="1">Inspector: </td>
            <td style="color: #000000; border: 1px solid #000;padding: 5px;" colspan="1">N/A</td>
        </tr>

    </table>


</body>
</html>

