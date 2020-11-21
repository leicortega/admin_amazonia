<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificado inspeccion - Amazonia C&L</title>

    <style>
        body {
            font-size: 13px;
            padding: 0px 0px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            line-height: 18px;
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
                <td rowspan="1" style="padding: 5px" width="150px">
                    {{-- <img src="{{ asset('assets/images/logo_amazonia.png') }}" alt=""> --}}
                    <img src="https://app.amazoniacl.com/images/logo_amazonia2.png" width="95px" alt="">
                </td>
                <td><b>SISTEMA INTEGRADO DE GESTION</b></td>
                <td width="150px"><b>CODIGO <br><br>###</b></td>
            </tr>
            {{-- <tr>
                <td><b>INSPECCIÓN DE VEHÍCULOS</b></td>
                <td><b>VIGENCIA <br><br>12-01-2019</b></td>
            </tr> --}}

    </table>

    <br><br><br><br>

    <?php echo $contenido; ?>


</body>
</html>


