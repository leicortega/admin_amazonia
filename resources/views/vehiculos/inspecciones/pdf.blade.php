<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inspeccion - Amazonia C&L</title>

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
        table {
            border-collapse: collapse;
        }
        .rows td {
            border: 1px solid brown;
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
                <td width="150px"><b>CODIGO <br><br>SIG-F-89</b></td>
            </tr>
            <tr>
                <td><b>INSPECCIÓN DE VEHÍCULOS</b></td>
                <td><b>VIGENCIA <br><br>12-01-2019</b></td>
            </tr>

    </table>

    <br><br><br><br>

    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="padding: 3px;line-height: 16px;">

        <tr>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="1">Reportado por: </td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;" colspan="3">{{ $inspeccion->users->name }}</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="1">Placa: </td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;" colspan="1">{{ $inspeccion->vehiculo->placa }}</td>
        </tr>

        <tr>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="1">No interno: </td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;" colspan="1">{{ $inspeccion->vehiculo->numero_interno }}</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="1">Inspector: </td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;" colspan="1">N/A</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="1">Admin GPS</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;" colspan="1"></td>
        </tr>

        <tr>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="3">Datos de INICIO: </td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="3">Datos de CIERRE</td>
        </tr>

        <tr>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="1">Fecha: </td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="1">Hora:</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="1">Kilometraje: </td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="1">Fecha: </td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="1">Hora:</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="1">Kilometraje: </td>
        </tr>

        <tr>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;" colspan="1">{{ \Carbon\Carbon::createFromDate($inspeccion->fecha_inicio)->format('Y-m-d') }}</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;" colspan="1">{{ \Carbon\Carbon::createFromDate($inspeccion->fecha_inicio)->format('H:i:s') }}</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;" colspan="1">{{ $inspeccion->kilometraje_inicio }}</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;" colspan="1">{{ \Carbon\Carbon::createFromDate($inspeccion->fecha_final)->format('Y-m-d') }}</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;" colspan="1">{{ \Carbon\Carbon::createFromDate($inspeccion->fecha_final)->format('H:i:s') }}</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;" colspan="1">{{ $inspeccion->kilometraje_final }}</td>
        </tr>

        <tr>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="1">Estado</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;" colspan="1">{{ ($inspeccion->kilometraje_final == NULL)  ? 'Iniciada' : 'Cerrada'}}</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="1">Total kilometros</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;" colspan="1">{{ $inspeccion->kilometraje_inicio - $inspeccion->kilometraje_final }}</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;" colspan="1">Total tiempo</td>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;" colspan="1">{{ $inspeccion->fecha_inicio }}</td>
        </tr>

        <tr>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;text-align: left;height:80px;" colspan="6"><p style="margin-top: -40px;"><b>Observacions: </b>{{ $inspeccion->observaciones_inicio }} <b>al cierre</b> {{ $inspeccion->observaciones_final }}</p></td>
        </tr>

        <tr>
            <td style="color: #000000;padding: 3px;border: 1px solid #000;font-weight: bold;text-align: center;" colspan="6">No Aplica (NA) No Dispone (ND) Malo (M) Regular (R) Bueno (B)</td>
        </tr>

    </table>


</body>
</html>

