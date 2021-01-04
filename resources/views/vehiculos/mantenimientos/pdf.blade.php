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
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;text-align:center;" colspan="4">DATOS DE MANTENIMIENTO</td>
        </tr>

        <tr>
            <td style="color: #000000; border: 1px solid #000;padding: 5px;" colspan="1">PLACA: {{ $mantenimiento->vehiculo->placa }}</td>
            <td style="color: #000000; border: 1px solid #000;padding: 5px;" colspan="1">MODELO: {{ $mantenimiento->vehiculo->modelo }}</td>
            <td style="color: #000000; border: 1px solid #000;padding: 5px;" colspan="2">MARCA: {{ \App\Models\Sistema\Marca::find($mantenimiento->vehiculo->marca_id)->nombre }}</td>
        </tr>

        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="4">ENCARGADO: {{ $mantenimiento->personal->nombres }} {{ $mantenimiento->personal->primer_apellido }} {{ $mantenimiento->personal->segundo_apellido ?? '' }}</td>
        </tr>
        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="4">ESTADO: {{ $mantenimiento->estado }}</td>
        </tr>
        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="4">DESCRIPCION: {{ $mantenimiento->descripcion_solicitud }}</td>
        </tr>

        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;text-align:center;" colspan="4">DATOS DE LA APROBACIÓN</td>
        </tr>

        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="4">FECHA AUTORIZACIÓN: {{ $mantenimiento->fecha_autorizacion }}</td>
        </tr>
        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="4">PERSONA QUE AUTORIZA: {{ $mantenimiento->persona_autoriza }}</td>
        </tr>
        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="4">OBSERVACIONES: {{ $mantenimiento->observaciones_autorizacion }}</td>
        </tr>

        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;text-align:center;" colspan="4">DATOS DE CONTABILIDAD</td>
        </tr>

        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="4">FECHA: {{ $mantenimiento->fecha_contabilidad }}</td>
        </tr>
        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="4">NOMBRE: {{ $mantenimiento->persona_contabilidad }}</td>
        </tr>
        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="4">OBSERVACIONES: {{ $mantenimiento->observaciones_contabilidad }}</td>
        </tr>

        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;text-align:center;" colspan="4">DATOS DE CIERRE</td>
        </tr>

        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="4">FECHA: {{ $mantenimiento->fecha_cierre }}</td>
        </tr>
        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="4">NOMBRE: {{ $mantenimiento->persona_cierre }}</td>
        </tr>
        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="4">OBSERVACIONES: {{ $mantenimiento->observaciones_cierre }}</td>
        </tr>
        <?php $valor = 0; ?>
        @foreach ($mantenimiento->facturas as $factura)
            <?php
                $valor = $valor + $factura->valor;
            ?>
        @endforeach
        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;" colspan="4">Costo Total Mantenimiento: ${{ number_format($valor) }}</td>
        </tr>

    </table>

    <br><br>

    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="padding: 5px;line-height: 16px;">

        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;text-align:center;" colspan="4">DATOS DE LA ACTIVIDAD</td>
        </tr>

        @foreach ($mantenimiento->actividades as $actividad)
            <tr>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;text-align:center;font-weight: bold;" colspan="1">FECHA</td>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;text-align:center;font-weight: bold;" colspan="1">TIPO ACTIVIDAD</td>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;text-align:center;font-weight: bold;" colspan="2">OBSERVACION</td>
            </tr>
            <tr>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;font-weight: bold;" colspan="1">{{ $actividad->fecha }}</td>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;font-weight: bold;" colspan="1">{{ $actividad->tipo }}</td>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;font-weight: bold;" colspan="2">{{ $actividad->observaciones }}</td>
            </tr>

            <tr>
                <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;text-align:center;" colspan="4">DATOS DE LAS SUB ACTIVIDADES</td>
            </tr>
            <tr>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;font-weight: bold;text-align:center;" colspan="2">GRAFICA ACTIVIDAD</td>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;font-weight: bold;text-align:center;" colspan="2">DESCRIPCION ACTIVIDAD</td>
            </tr>
            @foreach ($actividad->detalle_actividades as $detalle)
            <tr>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;font-weight: bold;max-width:390px;" colspan="2"><img src="../public/storage/{{ $detalle->imagen_soporte }}" style="max-width:390px;" alt=""></td>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;font-weight: bold;" colspan="2">{{ $detalle->descripcion }}</td>
            </tr>
            @endforeach
        @endforeach

    </table>

    <br><br>

    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="padding: 5px;line-height: 16px;">

        <tr>
            <td style="color: #000000; border: 1px solid #000;font-weight: bold;padding: 5px;text-align:center;" colspan="4">FACTURAS DEL MANTENIMIENTO</td>
        </tr>

        @foreach ($mantenimiento->facturas as $factura)
            <tr>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;text-align:center;font-weight: bold;" colspan="1">PROVEEDOR</td>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;text-align:center;font-weight: bold;" colspan="1">VALOR FACTURA</td>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;text-align:center;font-weight: bold;" colspan="2">FACTURA</td>
            </tr>
            <tr>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;font-weight: bold;" colspan="1">{{ $factura->proveedor }}</td>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;font-weight: bold;" colspan="1">{{ $factura->valor }}</td>
                <td style="color: #000000; border: 1px solid #000;padding: 5px;font-weight: bold;" colspan="2" style="max-width: 240px;"><img src="../public/storage/{{ $factura->factura_imagen }}" alt="" style="max-width: 240px;"></td>
            </tr>
        @endforeach

    </table>


</body>
</html>

