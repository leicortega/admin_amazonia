<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Otro Si</title>

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
            page-break-after:always;
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
            <td width="150px"><b>CODIGO <br><br>SIG-F-68</b></td>
        </tr>
        <tr>
            <td><b>OTRO SI AL CONTRATO DE TRABAJO
                </b></td>
            <td><b>VIGENCIA <br><br>23-04-2019</b></td>
        </tr>

    </table>

    <br>

    <p style="text-align: justify; font-size: 14px;">
        {{ $otro_si['descripcion'] }}

        <br>
        <br>
        De conformidad con lo anterior se firma el presente otro sí el {{ \Carbon\Carbon::now()->format('d/m/Y') }}
    </p>

    <br><br><br>

    <table style="border: none; margin-top:60px; font-size: 14px;">
        <tbody style="justify-content: space-between !important;">
            <tr style="border: none;">
                <td style="border: none;">
                    <br><br>
                    <b>____________________________</b><br>
                    <b>JOIMER OSORIO BAQUERO</b><br>
                    <b>C.C. 7.706.232 de Neiva</b><br>
                    <b>EMPLEADOR</b>
                </td>
                <td style="border: none;">
                    <br><br>
                    <b>____________________________</b><br>
                    <b style="text-transform: uppercase;">{{ $otro_si['contratos_personal']['personal']['nombres'] }} {{ $otro_si['contratos_personal']['personal']['primer_apellido'] }} {{ $otro_si['contratos_personal']['personal']['segundo_apellido'] ?? '' }}</b><br>
                    <b>CC. {{ $otro_si['contratos_personal']['personal']['identificacion'] }}</b><br>
                    <b>EMPLEADO</b>
                </td>
            </tr>
        </tbody>
    </table>

</body>
</html>
