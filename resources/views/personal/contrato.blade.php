<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contarto {{ $contrato['personal']['nombres'] }}</title>

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
            <td width="150px"><b>CODIGO <br><br>SIG-F-69</b></td>
        </tr>
        <tr>
            <td><b>CONTRATO INDIVIDUAL DE TRABAJO</b></td>
            <td><b>VIGENCIA <br><br>08-02-2018</b></td>
        </tr>

    </table>

    <br>

    <table style="border: none; font-size: 14px;">
        <tbody style="justify-content: flex-start !important;">
            <tr style="border: none;text-align:left;">
                <td style="border: none;text-align:left;"><b>Nombre del Empleador:</b></td>
                <td style="border: none;text-align:left;"><b>AMAZONIA C&L SAS</b></td>
            </tr>
            <tr style="border: none;text-align:left;">
                <td style="border: none;text-align:left;"><b>NIT:</b></td>
                <td style="border: none;text-align:left;"><b>900447438-6</b></td>
            </tr>
            <tr style="border: none;text-align:left;">
                <td style="border: none;text-align:left;"><b>Representante legal:</b></td>
                <td style="border: none;text-align:left;"><b>JOIMER OSORIO BAQUERO</b></td>
            </tr>
            <tr style="border: none;text-align:left;">
                <td style="border: none;text-align:left;"><b>Nombre del empleado:</b></td>
                <td style="border: none;text-align:left;text-transform:uppercase;"><b>{{ $contrato['personal']['nombres'] }} {{ $contrato['personal']['primer_apellido'] }} {{ $contrato['personal']['segundo_apellido'] ?? '' }}</b></td>
            </tr>
            <tr style="border: none;text-align:left;">
                <td style="border: none;text-align:left;"><b>Identificado con cédula No:</b></td>
                <td style="border: none;text-align:left;"><b>{{ $contrato['personal']['identificacion'] }}</b></td>
            </tr>
            <tr style="border: none;text-align:left;">
                <td style="border: none;text-align:left;"><b>Lugar de Residencia:</b></td>
                <td style="border: none;text-align:left;"><b>{{ $contrato['personal']['direccion'] ?? '' }}</b></td>
            </tr>
            <tr style="border: none;text-align:left;">
                <td style="border: none;text-align:left;"><b>Teléfonos Nro.:</b></td>
                <td style="border: none;text-align:left;"><b>{{ $contrato['personal']['telefonos'] ?? '' }}</b></td>
            </tr>
            <tr style="border: none;text-align:left;">
                <td style="border: none;text-align:left;"><b>Cargo a desempeñar: </b></td>
                <td style="border: none;text-align:left;"><b>{{ $contrato['cargo'] }}</b></td>
            </tr>
            <tr style="border: none;text-align:left;">
                <td style="border: none;text-align:left;"><b>Fecha de iniciación:</b></td>
                <td style="border: none;text-align:left;"><b>{{ $contrato['fecha_inicio'] }}</b></td>
            </tr>
            @if ($contrato['fecha_fin'])
                <tr style="border: none;text-align:left;">
                    <td style="border: none;text-align:left;"><b>Fecha de finalización :</b></td>
                    <td style="border: none;text-align:left;"><b>{{ $contrato['fecha_fin'] }}</b></td>
                </tr>
            @endif
            <tr style="border: none;text-align:left;">
                <td style="border: none;text-align:left;"><b>Tipo de contrato:</b></td>
                <td style="border: none;text-align:left;"><b>{{ $contrato['tipo_contrato'] }}</b></td>
            </tr>
            <tr style="border: none;text-align:left;">
                <td style="border: none;text-align:left;"><b>Asignación salarial:</b></td>
                <td style="border: none;text-align:left;"><b>{{ $contrato['salario'] }}</b></td>
            </tr>
        </tbody>
    </table>

    <br>

    <p style="text-align: justify; font-size: 12px;">
        {{ $contrato['clausulas_parte_uno'] }} {{ $contrato['clausulas_parte_dos'] ?? '' }}
    </p>

    <br><br>

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
                    <b style="text-transform: uppercase;">{{ $contrato['personal']['nombres'] }} {{ $contrato['personal']['primer_apellido'] }} {{ $contrato['personal']['segundo_apellido'] ?? '' }}</b><br>
                    <b>CC. {{ $contrato['personal']['identificacion'] }}</b><br>
                    <b>EMPLEADO</b>
                </td>
            </tr>
        </tbody>
    </table>

</body>
</html>
