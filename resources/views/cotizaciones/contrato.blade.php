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
    <p style="text-align: center;font-weight: bold;">CONTRATO No. {{ $data['cotizacion']['id'] }}</p>

    <br>
    <br>

    <p style="text-align: justify; font-size: 14px;">
        {{ $data['cotizacion']['contrato_parte_uno'] }} {{ $data['cotizacion']['contrato_parte_dos'] }}
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

    <div clas='saltopagina' style="page-break-after:always !important;"></div>

    <table class="table">
        <tbody>
            <tr>
                <td style="border: none"><img width="230" src="{{ public_path() }}/assets/images/logo1.png" alt=""></td>
                <td style="border: none"><img width="90" src="{{ public_path() }}/assets/images/logo2.jpg" alt=""></td>
                <td style="border: none"><img width="190" src="{{ public_path() }}/assets/images/logo.png" alt=""></td>
            </tr>
        </tbody>
    </table>

    <p style="text-align: center;font-weight: bold;">FORMATO UNICO DE CONTRATO DE SERVICIO PUBLICO DE TRANSPORTE AUTOMOTOR ESPCIAL</p>
    <p style="text-align: center;font-weight: bold;">No: 441000112202003{{ $data['cotizacion']['id'] }}</p>

    <br>

    <p style="font-size: 11.5px !important;"><b>AMAZONIA C&L S.A.S</b></p>
	<p><b>Nit:  900.447.438 - 6</b></p>
    <p><b>CONTRATO Nº: {{ $data['cotizacion']['id'] }}</b></p>
    <br>
	<p><b>CONTRATANTE: {{ $data['tercero']['nombre'] }}</b></p>
	<p><b>NIT/CC: {{ $data['tercero']['identificacion'] }}</b></p>
	<p><b>OBJETO CONTRATO: {{ $data['cotizacion']['objeto_contrato'] }}</b></p>
	<p><b>ORIGEN-DESTINO: {{ $data['cotizacion']['ciudad_origen'] }} - {{ $data['cotizacion']['ciudad_destino'] }}</b></p>

    <br>

    <p style="text-align: center;font-weight: bold;">VIGENCIA DEL CONTRATO</p>

    <table class="table">
        <tbody>
            <tr>
                <td style="padding: 5px;"><b>FECHA INICIAL</b></td>
                <td style="padding: 5px;"><b>DIA: </b>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $data['cotizacion']['fecha_ida'])->day }}</td>
                <td style="padding: 5px;"><b>MES: </b>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $data['cotizacion']['fecha_ida'])->month }}</td>
                <td style="padding: 5px;"><b>AÑO: </b>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $data['cotizacion']['fecha_ida'])->year }}</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><b>FECHA DE VENCIMIENTO</b></td>
                <td style="padding: 5px;"><b>DIA: </b>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $data['cotizacion']['fecha_ida'])->day }}</td>
                <td style="padding: 5px;"><b>MES: </b>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $data['cotizacion']['fecha_ida'])->month }}</td>
                <td style="padding: 5px;"><b>AÑO: </b>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $data['cotizacion']['fecha_ida'])->year }}</td>
            </tr>
        </tbody>
    </table>

    <br>

    <p style="text-align: center;font-weight: bold;">CARACTERÍSTICAS DEL VEHÍCULO</p>

    <table class="table">
        <tbody>
            <tr>
                <td style="padding: 3px;" colspan="1"><b>PLACA: </b>{{ $data['vehiculo']['placa'] }}</td>
                <td style="padding: 3px;" colspan="1"><b>MODELO: </b>{{ $data['vehiculo']['modelo'] }}</td>
                <td style="padding: 3px;" colspan="2"><b>MARCA: </b>{{ \App\Models\Sistema\Marca::find($data['vehiculo']['marca_id'])->nombre }}</td>
                <td style="padding: 3px;" colspan="2"><b>CLASE: </b>{{ \App\Models\Sistema\Tipo_Vehiculo::find($data['vehiculo']['tipo_vehiculo_id'])->nombre }}</td>
            </tr>
            <tr>
                <td style="padding: 3px;" colspan="3"><b>NÚMERO INTERNO: </b><br>{{ $data['vehiculo']['numero_interno'] }}</td>
                <td style="padding: 3px;" colspan="3"><b>NÚMERO TARJETA DE OPERACIÓN: </b><br>{{ $data['vehiculo']['tarjeta_operacion'] }}</td>
            </tr>
            <tr>
                <td style="padding: 3px; background:#ccc;" colspan="1"><b>DATOS DEL CONDUCTOR 1 </b></td>
                <td style="padding: 3px;" colspan="2"><b>NOMBRES Y APELLIDOS </b><br>{{ $data['conductor']['nombres'] }} {{ $data['conductor']['primer_apellido'] }} {{ $data['conductor']['segundo_apellido'] ?? '' }}</td>
                <td style="padding: 3px;" colspan="1"><b>Nº DE IDENTIFICACIÓN </b><br>{{ $data['conductor']['identificacion'] }}</td>
                <td style="padding: 3px;" colspan="1"><b>NºLICENCIA CONDUCCIÓN </b><br>{{ $data['conductor']['identificacion'] }}</td>
                <td style="padding: 3px;" colspan="1"><b>VIGENCIA </b><br>{{ $data['conductor']['fecha_ingreso'] }}</td>
            </tr>
            <tr>
                <td style="padding: 3px; background:#ccc;" colspan="1"><b>DATOS DEL CONDUCTOR 2 </b></td>
                <td style="padding: 3px;" colspan="2"><b>NOMBRES Y APELLIDOS </b><br>{{ $data['conductor']['nombres'] }} {{ $data['conductor']['primer_apellido'] }} {{ $data['conductor']['segundo_apellido'] ?? '' }}</td>
                <td style="padding: 3px;" colspan="1"><b>Nº DE IDENTIFICACIÓN </b><br>{{ $data['conductor']['identificacion'] }}</td>
                <td style="padding: 3px;" colspan="1"><b>NºLICENCIA CONDUCCIÓN </b><br>{{ $data['conductor']['identificacion'] }}</td>
                <td style="padding: 3px;" colspan="1"><b>VIGENCIA </b><br>{{ $data['conductor']['fecha_ingreso'] }}</td>
            </tr>
            <tr>
                <td style="padding: 3px; background:#ccc;" colspan="1"><b>DATOS DEL CONDUCTOR 3 </b></td>
                <td style="padding: 3px;" colspan="2"><b>NOMBRES Y APELLIDOS </b><br>{{ $data['conductor']['nombres'] }} {{ $data['conductor']['primer_apellido'] }} {{ $data['conductor']['segundo_apellido'] ?? '' }}</td>
                <td style="padding: 3px;" colspan="1"><b>Nº DE IDENTIFICACIÓN </b><br>{{ $data['conductor']['identificacion'] }}</td>
                <td style="padding: 3px;" colspan="1"><b>NºLICENCIA CONDUCCIÓN </b><br>{{ $data['conductor']['identificacion'] }}</td>
                <td style="padding: 3px;" colspan="1"><b>VIGENCIA </b><br>{{ $data['conductor']['fecha_ingreso'] }}</td>
            </tr>
            <tr>
                <td style="padding: 3px; background:#ccc;" colspan="1"><b>RESPONSABLE DEL CONTRATANTE </b></td>
                <td style="padding: 3px;" colspan="2"><b>NOMBRES Y APELLIDOS </b><br>{{ $data['responsable']['nombre'] }}</td>
                <td style="padding: 3px;" colspan="1"><b>Nº DE IDENTIFICACIÓN </b><br>{{ $data['responsable']['identificacion'] }}</td>
                <td style="padding: 3px;" colspan="1"><b>CORREO </b><br>{{ $data['responsable']['correo'] }}</td>
                <td style="padding: 3px;" colspan="1"><b>TELÉFONO </b><br>{{ $data['responsable']['telefono'] }}</td>
            </tr>
            <tr>
                <td style="padding: 3px; text-align:left;" colspan="4">
                    <b> Dirección:</b> CALL 19 SUR No 10-18 OF 105 <br>
                    <b> Oficina:</b> NEIVA HUILA <br>
                    <b> Teléfono:</b> 098 860 0663 <br>
                    <b> Celular:</b> 316 875 6633 <br>
                    <b> E mail:</b> transporte@amazoniacl.com <br>
                </td>

                <td style="padding: 3px;" colspan="2"><b><br><br><br><br><br><br><br>FIRMA Y SELLO GERENTE</b></td>
            </tr>
        </tbody>
    </table>

    <div clas='saltopagina' style="page-break-after:always !important;"></div>

    <br><br>

    <p style="text-align: center;font-weight: bold;">INSTRUCTIVO PARA LA DETERMINACIÓN DEL NÚMERO CONSECUTIVO DEL FORMATO ÚNICO DE EXTRACTO DEL CONTRATO - FUEC</p>

    <br><br>

    <p style="text-align: justify;">El Formato Único de Extracto del Contrato - FUEC estará constituido por los siguientes números:</p>

    <p style="text-align: justify;">a) Los tres primeros dígitos de izquierda a derecha corresponderán al código de la Dirección Territorial que otorgó la habilitación o de aquella a la cual se hubiese trasladado la empresa de Servicio Público de Transporte Terrestre Automotor Especial.
    </p>

    <br>

    <table class="table">
        <tbody>
            <tr>
                <td colspan="6" style="text-align: center;"><b>LISTADO DIRECCIÓN TERRIOTORIAL</b></td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: left" colspan="2">Antioquia-Chocó</td>
                <td style="padding: 5px;" align="center" colspan="1">305</td>
                <td style="padding: 5px; text-align: left" colspan="2">Huila-Caquetá </td>
                <td style="padding: 5px;" align="center" colspan="1">441</td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: left" colspan="2">Atlántico</td>
                <td style="padding: 5px;" align="center" colspan="1">208</td>
                <td style="padding: 5px; text-align: left" colspan="2">Magdalena</td>
                <td style="padding: 5px;" align="center" colspan="1">247</td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: left" colspan="2">Bolívar-San Andrés y Providencia</td>
                <td style="padding: 5px;" align="center" colspan="1">213</td>
                <td style="padding: 5px; text-align: left" colspan="2">Meta-Vaupés-Vichada</td>
                <td style="padding: 5px;" align="center" colspan="1">550</td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: left" colspan="2">Boyacá-Casanare </td>
                <td style="padding: 5px;" align="center" colspan="1">415</td>
                <td style="padding: 5px; text-align: left" colspan="2">Nariño-Putumayo </td>
                <td style="padding: 5px;" align="center" colspan="1">352</td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: left" colspan="2">Caldas</td>
                <td style="padding: 5px;" align="center" colspan="1">317</td>
                <td style="padding: 5px; text-align: left" colspan="2">N/Santander-Arauca </td>
                <td style="padding: 5px;" align="center" colspan="1">454</td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: left" colspan="2">Cauca</td>
                <td style="padding: 5px;" align="center" colspan="1">319</td>
                <td style="padding: 5px; text-align: left" colspan="2">Quindío</td>
                <td style="padding: 5px;" align="center" colspan="1">363</td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: left" colspan="2">Cesar</td>
                <td style="padding: 5px;" align="center" colspan="1">220</td>
                <td style="padding: 5px; text-align: left" colspan="2">Risaralda</td>
                <td style="padding: 5px;" align="center" colspan="1">366</td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: left" colspan="2">Córdoba-Sucre</td>
                <td style="padding: 5px;" align="center" colspan="1">223</td>
                <td style="padding: 5px; text-align: left" colspan="2">Santander</td>
                <td style="padding: 5px;" align="center" colspan="1">468</td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: left" colspan="2">Cundinamarca</td>
                <td style="padding: 5px;" align="center" colspan="1">425</td>
                <td style="padding: 5px; text-align: left" colspan="2">Tolima</td>
                <td style="padding: 5px;" align="center" colspan="1">473</td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: left" colspan="2">Guajira</td>
                <td style="padding: 5px;" align="center" colspan="1">241</td>
                <td style="padding: 5px; text-align: left" colspan="2">Valle del Cauca</td>
                <td style="padding: 5px;" align="center" colspan="1">376</td>
            </tr>
        </tbody>
    </table>

    <br>

    <p style="text-align: justify;">b) Los cuatro dígitos siguientes señalarán el número de resolución mediante la cual se otorgó la habilitación de la Empresa. En caso que la resolución no tenga estos dígitos, los faltantes serán completados con ceros a la izquierda;
    </p>

    <p style="text-align: justify;">c) Los dos siguientes dígitos, corresponderán a los dos últimos del año en que la empresa fue habilitada;</p>

    <p style="text-align: justify;">d) A continuación, cuatro dígitos que corresponderán al año en el que se expide el extracto del contrato;</p>

    <p style="text-align: justify;">e) Posteriormente, cuatro dígitos que identifican el número del contrato.</p>

    <p style="text-align: justify;">f) Finalmente, los cuatro últimos dígitos corresponderán a los cuatro dígitos del número consecutivo del extracto de contrato. Se debe expedir un nuevo extracto por vencimiento del plazo inicial del mismo o por cambio de vehículo
    </p>

    <p style="text-align: justify;"><b>EJEMPLO:</b> <br>Finalmente, los cuatro últimos dígitos corresponderán a los cuatro dígitos del número consecutivo del extracto de contrato. Se debe expedir un nuevo extracto por vencimiento del plazo inicial del mismo o por cambio de vehículo
    </p>

    <br><br><br><br><br><br><br>

    <div style="width:100%;text-align:center !important;">
        {{-- <img src="{{ public_path('assets/images/qr/'.$contrato[0]->codeQR) }}"  style="text-align: justify;margin-bottom:0;" width="100px" /> --}}
        <p style="text-align: justify;color:#cccccc;font-size:10px;margin-top:0;"><b>Escanea el codigo QR</b></p>
    </div>

</body>
</html>
