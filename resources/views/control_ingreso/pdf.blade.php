<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Control de Ingreso - Amazonia C&L</title>

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

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        
            <tr>
                <td style="font-size:18px;"><b>Control COVID-19</b></td>
                <td style="text-align: right;" width="150px">
                    <img src="https://app.amazoniacl.com/images/logo_amazonia2.png" width="95px" alt="">
                </td>
                {{-- <td><b>SISTEMA INTEGRADO DE GESTION</b></td> --}}
                {{-- <td width="150px"><b>CODIGO <br><br>SIG-F-100</b></td>   --}}
            </tr>
        
    </table>

    <br><br><br><br><br><br><br><br><br>

    <div style="display: inline-flex; width:100%;">
        <p>Neiva, {{ $ingreso[0]['ingresos'][0]['fecha'] }} </p>
    </div>   

    <p style="line-height: 16px;">
    <br>Nombre: {{ $ingreso[0]['name'] }}
    <br>Identificación: {{ $ingreso[0]['identificacion'] }}
    <br>Edad: {{ $ingreso[0]['edad'] }}
    <br>Correo: {{ $ingreso[0]['email'] }}
    <br>Telefono: {{ $ingreso[0]['telefono'] }}</p>

    <br><br><b>Temperatura: {{ $ingreso[0]['ingresos'][0]['temperatura'] }}</b></p>

    <br>
    <br>
    <br>
    <br>

    <p style="line-height: 19px;">1. ¿Usted se encuentra con síntomas de enfermedad o problemas respiratorios? <br><br>&#160;&#160;&#160;&#160;<b>Respuesta: {{ $ingreso[0]['ingresos'][0]['pregunta_one'] }}</b></p>

    <p style="line-height: 19px;">2. ¿Ha tenido contacto con una persona que presente síntomas gripales y que haya venido del extranjero? <br><br>&#160;&#160;&#160;&#160;<b>Respuesta: {{ $ingreso[0]['ingresos'][0]['pregunta_two'] }}</b></p>

    <p style="line-height: 19px;">3. ¿Ha estado en contacto cercano con una persona diagnosticada con COVID-19? <br><br>&#160;&#160;&#160;&#160;<b>Respuesta: {{ $ingreso[0]['ingresos'][0]['pregunta_three'] }}</b></p>
    
    <p style="line-height: 19px;">4. ¿Presenta alguno de los siguientes síntomas? 
        <br><br>&#160;&#160;&#160;&#160;<b>Fiebre: {{ $ingreso[0]['ingresos'][0]['fiebre'] }}</b>  
        <br>&#160;&#160;&#160;&#160;<b>Tos: {{ $ingreso[0]['ingresos'][0]['tos'] }}</b>  
        <br>&#160;&#160;&#160;&#160;<b>Gripa: {{ $ingreso[0]['ingresos'][0]['gripa'] }}</b>  
        <br>&#160;&#160;&#160;&#160;<b>Malestar General: {{ $ingreso[0]['ingresos'][0]['malestar'] }}</b>  
        <br>&#160;&#160;&#160;&#160;<b>Dolor de cabeza: {{ $ingreso[0]['ingresos'][0]['dolor_cabeza'] }}</b>  
        <br>&#160;&#160;&#160;&#160;<b>Fatiga: {{ $ingreso[0]['ingresos'][0]['fatiga'] }}</b>  
        <br>&#160;&#160;&#160;&#160;<b>Secreción nasal: {{ $ingreso[0]['ingresos'][0]['secrecion_nasal'] }}</b>  
        <br>&#160;&#160;&#160;&#160;<b>Dificultad para respirar: {{ $ingreso[0]['ingresos'][0]['dificultad_respirar'] }}</b>  
        <br>&#160;&#160;&#160;&#160;<b>Dolor de garganta: {{ $ingreso[0]['ingresos'][0]['dolor_garganta'] }}</b>  
        <br>&#160;&#160;&#160;&#160;<b>Perdida del olfato o gusto: {{ $ingreso[0]['ingresos'][0]['olfato_gusto'] }}</b>  
    </p>

    <p style="line-height: 19px;">5. ¿Presenta alguno de los siguientes factores de riesgo?
        <br><br>&#160;&#160;&#160;&#160;<b>Diabetes: {{ $ingreso[0]['ingresos'][0]['diabetes'] }}</b>  
        <br>&#160;&#160;&#160;&#160;<b>Hipertension arterial: {{ $ingreso[0]['ingresos'][0]['hipertension'] }}</b>  
        <br>&#160;&#160;&#160;&#160;<b>Mayor de 60 años: {{ $ingreso[0]['ingresos'][0]['mayor_edad'] }}</b>  
        <br>&#160;&#160;&#160;&#160;<b>Cancer: {{ $ingreso[0]['ingresos'][0]['cancer'] }}</b>  
        <br>&#160;&#160;&#160;&#160;<b>Inmunodeficiencia (incluyendo VIH): {{ $ingreso[0]['ingresos'][0]['inmunodeficiencia'] }}</b>  
    </p>

    <div class="footer">
        <table width="100%"  cellspacing="0" cellpadding="0" style="text-align: center;">
        
            <tr>
                <td width="150px" style="text-align: right;line-height: 16px;">
                    Página 1 de 1 <br>Impreso por APP AMAZONIA C&L
                </td>  
            </tr>
        
    </table>
    </div>
    
</body>
</html>

