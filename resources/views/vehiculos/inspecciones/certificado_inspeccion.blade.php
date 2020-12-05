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
            bottom: 50px;
            width: 100%;
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

    <img src="https://app.amazoniacl.com/images/logo_amazonia2.png" width="150px" alt="" style="float: right; margin-top:20px;">

    <?php echo $contenido; ?>

    <table class="footer" style="border: none; margin-top:60px;position:absolute; font-size: 14px;">
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


