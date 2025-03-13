<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Clase MailSender
 *
 * Centraliza el envío de correos usando PHPMailer y permite configurar SMTP.
 *
 * @category Class
 * @package  Class\MailSender
 * @version  1.1
 * @since    2025-03-11
 * @author   Jeimy Jara
 */
class MailSender
{
    private PHPMailer $mail;

    /**
     * Constructor de MailSender
     *
     * @param string $tipo Define si se usará SMTP o API en el futuro.
     * @throws Exception
     */
    public function __construct(string $tipo = 'smtp')
    {
        require_once 'PHPMailer/PHPMailer.php';
        require_once 'PHPMailer/SMTP.php';
        require_once 'PHPMailer/Exception.php';

        $this->mail = new PHPMailer(true); // Excepciones habilitadas
        if ($tipo === 'smtp') {
            $this->configureSMTP();
        }
    }

    /**
     * Configura el servidor SMTP
     * @return void
     * @throws Exception
     */
    private function configureSMTP(): void
    {
        global $smtp_host, $smtp_user, $smtp_pass, $smtp_secure, $smtp_from, $smtp_from_name;

        try {
            $this->mail->isSMTP();
            $this->mail->SMTPDebug = 0;
            $this->mail->Host = $smtp_host;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $smtp_user;
            $this->mail->Password = $smtp_pass;
            $this->mail->SMTPSecure = $smtp_secure;
            $this->mail->Port = 465;
            $this->mail->isHTML();
            $this->mail->CharSet = 'UTF-8';
            $this->mail->setFrom($smtp_from, $smtp_from_name);
        } catch (Exception $e) {
            throw new Exception("Error al configurar SMTP: " . $e->getMessage() . " Data: " . json_encode($this->mail));
        }
    }

    /**
     * Envía un correo electrónico
     *
     * @param string $destinatario Dirección de correo.
     * @param string $asunto Asunto del correo.
     * @param string $cuerpo Contenido HTML.
     * @return bool True si se envía correctamente, False en caso de error.
     * @throws Exception
     */
    public function sendMail(string $destinatario, string $asunto, string $cuerpo): bool
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($destinatario);
            $this->mail->Subject = $asunto;
            $this->mail->Body = $cuerpo;

            return $this->mail->send();
        } catch (Exception $e) {
            throw new Exception("Error al enviar correo: " . $e->getMessage());
        }
    }
}
