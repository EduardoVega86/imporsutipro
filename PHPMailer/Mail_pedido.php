<?php
$smtp_debug = 0;
$smtp_host = 'smtp.titan.email';
$smtp_port = 465;
$smtp_secure = 'ssl';
$smtp_user = 'info@imporfactoryusa.com';
$smtp_pass = 'Mark2demasiado.';
$smtp_from = $smtp_user;
$smtp_from_name = 'ImporSuit';
$smtp_subject = 'Nuevo pedido';
$message_body_pedido = '<!DOCTYPE html>
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
                <h1>ImporSuit</h1>
            </article>
            <article>
                <h2>Nuevo pedido</h2>
            </article>
            <article>
                <p>Estimado usuario, nos comunicamos con usted para darle a conocer que ha recibido un nuevo pedido a su
                    tienda.</p>

            </article>
        </section>
    </div>
    <br>
    <br>

</body>

</html>';
