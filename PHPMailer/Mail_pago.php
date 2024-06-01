<?php

$smtp_debug = 0;
$smtp_host = 'smtp.titan.email';
$smtp_port = 465;
$smtp_secure = 'ssl';
$smtp_user = 'info@imporfactoryusa.com';
$smtp_pass = 'Mark2demasiado.';
$smtp_from = $smtp_user;
$smtp_from_name = 'ImporSuit';


date_default_timezone_set('America/Bogota');
// Establecer la configuración regional a español
// Establecer la configuración regional a español directamente en el script
setlocale(LC_TIME, 'es_ES.utf8', 'es_ES');

// Obtener el nombre del día de la semana
$nombreDia = strftime('%A');

// Restaurar la configuración regional a la original (opcional)
setlocale(LC_TIME, '');

switch ($nombreDia) {
    case 'Monday':
        $nombreDia = 'Lunes';
        break;

    case 'Tuesday':
        $nombreDia = 'Martes';
        break;

    case 'Wednesday':
        $nombreDia = 'Miércoles';
        break;

    case 'Thursday':
        $nombreDia = 'Jueves';
        break;

    case 'Friday':
        $nombreDia = 'Viernes';
        break;

    case 'Saturday':
        $nombreDia = 'Sábado';
        break;

    case 'Sunday':
        $nombreDia = 'Domingo';
        break;
}


$message_body = '<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        body {
            background-color: #6c757d !important;
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
        }

        .container {
            margin-right: auto;
            margin-left: auto;
            padding-right: 15px;
            padding-left: 15px;
            margin-top: 3rem !important;
            margin-bottom: 3rem !important;
        }

        .text-center {
            text-align: center !important;
        }

        .bg-white {
            background-color: #fff !important;
        }

        .rounded-5 {
            border-radius: .2rem !important;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .p-5 {
            padding: 1.5rem !important;
        }

        .shadow-lg {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .border-5 {
            border-width: 5px !important;
        }

        .border-top {
            border-top: 1px solid #007bff !important;
        }

        h1,
        h2 {
            margin-bottom: .5rem !important;
        }

        p {
            margin-top: 0 !important;
            margin-bottom: 1rem !important;
        }

        a {
            color: #007bff !important;
            text-decoration: none !important;
            background-color: transparent !important;
        }

        .btn {
            display: inline-block !important;
            font-weight: 400 !important;
            text-align: center !important;
            white-space: nowrap !important;
            vertical-align: middle !important;
            user-select: none !important;
            border: 1px solid transparent !important;
            padding: .375rem .75rem !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
            border-radius: .25rem !important;
        }

        .btn-success {
            color: #fff !important;
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }
        .bg-secondary {
            background-color: #6c757d !important;
        }
        @media (max-width: 576px) {
            .p-5 {
                padding: 1rem !important;
            }
            .container {
                max-width: 80% !important;
            }
        }
        @media (min-width: 768px) {
            .container {
                max-width: 60% !important;
            }
        }
        @media (min-width: 992px) {
            .container {
                max-width: 50% !important;
            }
        }
        @media (min-width: 1200px) {
            .container {
                max-width: 40% !important;
            }
        }   

    </style>
</head>

<body class="bg-secondary">
<br>
<br>

    <div class="container">
        <section class="text-center bg-white rounded-5 mt-5 p-5 shadow-lg border-5 border-top border-primary">
            <article>
            <img src="https://content.app-sources.com/s/96314659917631607/uploads/Images/imporsuit-logo-3305646.png" alt="Logo ImporSuit" width="200px">   
            </article>
            <article>
                <h2>Solicitud de Pago</h2>
            </article>
            <article>
                <p>Saludos, de parte de <strong>' . $nombre . '</strong> con la cedula <strong> ' . $cedula . ' </strong>solicito coordialmente hoy ' . $nombreDia . ' <strong>' . date("d/m/Y") . ' a las ' . date("h:i:sa") . '
                :</p>
                <p>El pago de la cantidad de $' . $cantidad . ' de las utilidades de la tienda: <strong> ' . $tienda . '</strong></p>
                <p>Por favor realizar el con los siguientes datos: </p>
                <p>Banco:<strong> ' . $banco . '</strong></p>
                <p>Tipo de cuenta: <strong>' . $tipo_cuenta . '</strong></p>
                <p>Numero de cuenta: <strong>' . $numero_cuenta . '</strong></p>
            </article>
            <article>
                <p>Para cualquier duda o aclaratoria, comunicarse al telefono: <strong>' . $telefono . '</strong></p>
                <p>o al correo: <strong>' . $correo . '</strong></p>

            </article>
            <article>
                <p>Gracias por su atencion.</p>
            </article>
        </section>
    </div>
    <br>
    <br>

    </body>

</html>


';

// mensaje al solicitante de pago
$message_body2 = '<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        body {
            background-color: #6c757d !important;
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
        }

        .container {
            margin-right: auto;
            margin-left: auto;
            padding-right: 15px;
            padding-left: 15px;
            margin-top: 3rem !important;
            margin-bottom: 3rem !important;
        }

        .text-center {
            text-align: center !important;
        }

        .bg-white {
            background-color: #fff !important;
        }

        .rounded-5 {
            border-radius: .2rem !important;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .p-5 {
            padding: 1.5rem !important;
        }

        .shadow-lg {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .border-5 {
            border-width: 5px !important;
        }

        .border-top {
            border-top: 1px solid #007bff !important;
        }

        h1,
        h2 {
            margin-bottom: .5rem !important;
        }

        p {
            margin-top: 0 !important;
            margin-bottom: 1rem !important;
        }

        a {
            color: #007bff !important;
            text-decoration: none !important;
            background-color: transparent !important;
        }

        .btn {
            display: inline-block !important;
            font-weight: 400 !important;
            text-align: center !important;
            white-space: nowrap !important;
            vertical-align: middle !important;
            user-select: none !important;
            border: 1px solid transparent !important;
            padding: .375rem .75rem !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
            border-radius: .25rem !important;
        }

        .btn-success {
            color: #fff !important;
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }
        .bg-secondary {
            background-color: #6c757d !important;
        }
        @media (max-width: 576px) {
            .p-5 {
                padding: 1rem !important;
            }
            .container {
                max-width: 80% !important;
            }
        }
        @media (min-width: 768px) {
            .container {
                max-width: 60% !important;
            }
        }
        @media (min-width: 992px) {
            .container {
                max-width: 50% !important;
            }
        }
        @media (min-width: 1200px) {
            .container {
                max-width: 40% !important;
            }
        }   

    </style>
</head>

<body class="bg-secondary">
<br>
<br>

    <div class="container">
        <section class="text-center bg-white rounded-5 mt-5 p-5 shadow-lg border-5 border-top border-primary">
            <article>
            <img src="https://content.app-sources.com/s/96314659917631607/uploads/Images/imporsuit-logo-3305646.png" alt="Logo ImporSuit" width="200px">   
            </article>
            <article>
                <h2>Solicitud de Pago</h2>
            </article>
            <article>
                <p>Saludos, de parte del departamento de CONTABILIDAD de IMPORFACTORY</p>
                <p>Su solicitud de pago ha sido recibida y sera procesada en las proximas 24 horas laborables.</p>
                </article>
                <article>
                <p>Gracias por su atencion.</p>
            </article>
        </section>
    </div>
    <br>
    <br>
    
    </body>

</html>
';


// Se pago

$message_body3 = '<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        body {
            background-color: #6c757d !important;
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
        }

        .container {
            margin-right: auto;
            margin-left: auto;
            padding-right: 15px;
            padding-left: 15px;
            margin-top: 3rem !important;
            margin-bottom: 3rem !important;
        }

        .text-center {
            text-align: center !important;
        }

        .bg-white {
            background-color: #fff !important;
        }

        .rounded-5 {
            border-radius: .2rem !important;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .p-5 {
            padding: 1.5rem !important;
        }

        .shadow-lg {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .border-5 {
            border-width: 5px !important;
        }

        .border-top {
            border-top: 1px solid #007bff !important;
        }

        h1,
        h2 {
            margin-bottom: .5rem !important;
        }

        p {
            margin-top: 0 !important;
            margin-bottom: 1rem !important;
        }

        a {
            color: #007bff !important;
            text-decoration: none !important;
            background-color: transparent !important;
        }

        .btn {
            display: inline-block !important;
            font-weight: 400 !important;
            text-align: center !important;
            white-space: nowrap !important;
            vertical-align: middle !important;
            user-select: none !important;
            border: 1px solid transparent !important;
            padding: .375rem .75rem !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
            border-radius: .25rem !important;
        }

        .btn-success {
            color: #fff !important;
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }
        .bg-secondary {
            background-color: #6c757d !important;
        }
        @media (max-width: 576px) {
            .p-5 {
                padding: 1rem !important;
            }
            .container {
                max-width: 80% !important;
            }
        }
        @media (min-width: 768px) {
            .container {
                max-width: 60% !important;
            }
        }
        @media (min-width: 992px) {
            .container {
                max-width: 50% !important;
            }
        }
        @media (min-width: 1200px) {
            .container {
                max-width: 40% !important;
            }
        }   

    </style>
</head>

<body class="bg-secondary">
<br>
<br>

    <div class="container">
        <section class="text-center bg-white rounded-5 mt-5 p-5 shadow-lg border-5 border-top border-primary">
            <article>
            <img src="https://content.app-sources.com/s/96314659917631607/uploads/Images/imporsuit-logo-3305646.png" alt="Logo ImporSuit" width="200px">   
            </article>
            <article>
                <h2>Solicitud de Pago</h2>
            </article>
            <article>
                <p>Saludos, de parte del departamento de CONTABILIDAD de IMPORFACTORY</p>
                <p>Su solicitud de pago ha sido procesada y se ha realizado el pago de la cantidad de $' . $cantidad . ' de las utilidades de la tienda: <strong> ' . $tienda . '</strong></p>
                <p>Por favor verificar en su cuenta bancaria.</p>
                </article>
                <article>
                <p>Gracias por su atencion.</p>
            </article>
        </section>
    </div>
    <br>
    <br>

    </body>

</html>

            ';
