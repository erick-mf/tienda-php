<?php

namespace App\Lib;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public $email;

    public $name;

    public $token;

    public function __construct($email, $name, $token)
    {
        $this->email = $email;
        $this->name = $name;
        $this->token = $token;
    }

    public function sendConfirmation()
    {
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Port = 587;
        $mail->Username = $_ENV['USER_EMAIL'];
        $mail->Password = $_ENV['PASS_EMAIL'];
        $mail->setFrom($_ENV['USER_EMAIL'], 'Fake.com');
        $mail->addAddress($this->email);
        $mail->Subject = 'Confirmación de cuenta';
        $mail->CharSet = 'utf-8';

        $mail->isHTML(true);

        $content = '<html>';
        $content .= '<p>Hola<strong> '.ucfirst($this->name).'</strong><br>Has creado tú cuenta en Fake.com. Solo debes confirmarla presionando en el siguiente enlace</p>';
        $content .= "<a href='http://localhost/confirmation/{$this->token}'>Confirmar tú cuenta aquí</a><br>";
        // $content .= "<a href='http://localhost/confirmation'>Confirmar tú cuenta aquí</a><br>";
        $content .= '<p>Sí tu no has realizado ningun proceso con nuestro sitio web puedes ignorar este mensaje</p>';
        $content .= '</html>';
        $mail->Body = $content;

        $mail->send();
    }
}
