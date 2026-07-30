<?php

namespace App\Services;

require_once __DIR__ . '/../Libraries/PHPMailer/Exception.php';
require_once __DIR__ . '/../Libraries/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../Libraries/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer;

final class EmailService
{
    private array $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../Config/mail.php';
    }

    /**
     * @throws PHPMailerException se o envio falhar.
     */
    public function enviar(string $paraEmail, string $paraNome, string $assunto, string $corpoHtml): void
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = $this->config['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $this->config['smtp_username'];
        $mail->Password   = $this->config['smtp_password'];
        $mail->SMTPSecure = $this->config['smtp_encryption'];
        $mail->Port       = $this->config['smtp_port'];
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($this->config['from_email'], $this->config['from_name']);
        $mail->addAddress($paraEmail, $paraNome);

        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body    = $corpoHtml;
        $mail->AltBody = strip_tags($corpoHtml);

        $mail->send();
    }
}
