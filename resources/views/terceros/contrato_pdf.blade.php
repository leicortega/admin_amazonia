<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contrato Servicio Especial PDF</title>

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
            <td width="150px"><b>CODIGO <br><br>SIG-F-71</b></td>
        </tr>
        <tr>
            <td><b>CONTRATO DE PRESTACIÓN DE SERVICIOS DE
                TRANSPORTE TERRESTRE ESPECIAL
                </b></td>
            <td><b>VIGENCIA <br><br>19-JUNIO DE 2019</b></td>
        </tr>

    </table>

    <br>
    <p style="text-align: center;font-weight: bold;">CONTRATO No. {{ $data['contrato']['id'] }}</p>

    <br>
    <br>

    <p style="text-align: justify; font-size: 14px;">
        {{ $data['contrato']['contrato_parte_uno'] }} {{ $data['contrato']['contrato_parte_dos'] }}
    </p>

    <br><br><br>

    <table style="border: none; margin-top:60px; font-size: 14px;">
        <thead style="border: none">
            <tr style="border: none">
                <th style="border: none;font-size: 12px;font-family:Arial, Helvetica, sans-serif;color: #000 !important;text-align: rigth;padding:0;">
                    <b>CONTRATISTA</b>
                </th>
                <th style="border: none;font-size: 12px;font-family:Arial, Helvetica, sans-serif;color: #000 !important;text-align: rigth;padding:0;">
                    <b>CONTRATANTE</b>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr style="border: none;">
                <td style="border: none;text-align:rigth;">
                    <br><br><br><br>
                    <b>____________________________</b><br>
                    <b>JOIMER OSORIO BAQUERO</b><br>
                    <b>C.C. 7.706.232 de Neiva</b><br>
                    <b>REPRESENTANTE LEGAL</b><br>
                </td>
                <td style="border: none;text-align:rigth;">
                    <br><br><br><br>
                    <b>____________________________</b><br>
                    <b>{{ $data['tercero']['nombre'] }}</b><br>
                    <b>CC. {{ $data['tercero']['identificacion'] }}</b><br>
                    <b>CONTRATANTE</b>
                </td>
            </tr>
        </tbody>
    </table>

</body>
</html>
