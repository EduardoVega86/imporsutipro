<?php

$smtp_debug = 0;
$smtp_host = 'smtp.titan.email';
$smtp_port = 465;
$smtp_secure = 'ssl';
$smtp_user = 'info@imporfactoryusa.com';
$smtp_pass = 'Mark2demasiado.';
$smtp_from = $smtp_user;
$smtp_from_name = MARCA;

$message_body = '
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correo Compatible</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f4f4;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
        <tr>
            <td align="center" bgcolor="#191731" style="padding: 40px 0 30px 0;">
                <img src="https://tiendas.imporsuitpro.com/imgs/LOGOS-IMPORSUIT.png" alt="Logo" width="300" height="120" style="display: block;">
            </td>
        </tr>
        <tr>
            <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="color: #191731; font-family: Arial, sans-serif; font-size: 24px;">
                            <b>Bienvenido a Imporsuit</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
                            Hola Suiter 👋🏻 queremos darte la bienvenida a la Comunidad de ECOMMERCE
                            en IMPORSUIT. 🚀
                            Para acompañarte de la mejor manera, queremos entregarte estos tutoriales en donde
                            encontraras material muy útil para crear tu ecommerce exitoso. 😁
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Para acceder a los tutoriales, haz clic en el siguiente botón:

                        </td>
                    </tr>
                    <tr>

                        <td style="padding: 20px 0 30px;">
                            <a href="https://danielbonilla522-9.funnels.mastertools.com/" target="_blank" style="background-color: #191731; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px; display: inline-block;">Ver
                                tutoriales</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor=" #191731" style="padding: 30px 30px 30px 30px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;">
                            &copy; 2024 Todos los derechos reservados.<br>
                            Si no deseas recibir más correos, <a target="_blank" href="https://new.imporsuitpro.com" style="color: #ffffff;">haz clic
                                aquí para
                                ir a Imporsuit</a>.
                        </td>
                        <td align="right">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <a href="https://www.facebook.com/profile.php?id=61562089709806" target="_blank">
                                            <img src="https://tiendas.imporsuitpro.com/fb.png" alt="Facebook" width="38" height="38" style="display: block;">
                                        </a>
                                    </td>
                                    <td style="font-size: 0; line-height: 0;" width="20">
                                        &nbsp;
                                    </td>
                                    <td>
                                        <a href="https://www.tiktok.com/@imporfactoryllc" target="_blank">
                                            <img src="https://tiendas.imporsuitpro.com/tiktok.png" alt="Tiktok" width="38" height="38" style="display: block;">
                                        </a>
                                    </td>
                                    <td style="font-size: 0; line-height: 0;" width="20">
                                        &nbsp;
                                    </td>
                                    <td>
                                        <a href="https://www.instagram.com/imporsuit/" target="_blank">
                                            <img src="https://tiendas.imporsuitpro.com/instagram.png" alt="Instagram" width="38" height="38" style="display: block;">
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
';
