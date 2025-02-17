<?php

namespace App\Lib;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public $email;

    public $name;

    public $token;

    public $host;

    public function __construct($email, $name, $token)
    {
        $this->email = $email;
        $this->name = $name;
        $this->token = $token;
    }

    public function setup($subject, $content)
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
        $mail->Subject = $subject;
        $mail->CharSet = 'utf-8';
        $mail->isHTML(true);
        $mail->Body = $content;
        $mail->send();
    }

    public function sendConfirmation()
    {
        $this->host = $_ENV['HOST_URL'];

        $content = '<html>';
        $content .= '<p>Hola<strong> '.ucfirst($this->name).'</strong><br>Has creado tú cuenta en Fake.com. Solo debes confirmarla presionando en el siguiente enlace</p>';
        $content .= "<a href='{$this->host}/confirmation/{$this->token}'>Confirmar tú cuenta aquí</a><br>";
        $content .= '<p>Sí tu no has realizado ningun proceso con nuestro sitio web puedes ignorar este mensaje</p>';
        $content .= '</html>';

        $subject = 'Confirmación de la cuenta';
        $this->setup($subject, $content);
    }

    public function sendOrder($dataOrder)
    {

        $styles = '
<style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: #333;
    }
    .container {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
    }
    h3, h4 {
        color: #2c3e50;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
    }
    .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #3498db;
        color: #ffffff;
        text-decoration: none;
        border-radius: 5px;
    }
</style>
';
        $content = '<html><head>'.$styles.'</head><body><div class="container">';
        $content .= '<h4>Detalles del Pedido:</h4>';
        $content .= '<ul>';
        $content .= '<li>Fecha: '.$dataOrder['fecha'].'</li>';
        $content .= '<li>Hora: '.$dataOrder['hora'].'</li>';
        $content .= '<li>Estado: '.$dataOrder['estado'].'</li>';
        $content .= '<li>Total: €'.number_format($dataOrder['coste'], 2).'</li>';
        $content .= '</ul>';

        $content .= '<h4>Dirección de Envío:</h4>';
        $content .= '<p>'.$dataOrder['direccion'].'<br>';
        $content .= $dataOrder['localidad'].', '.$dataOrder['provincia'].'</p>';

        $content .= '<h4>Productos:</h4>';
        $content .= '<table>';
        $content .= '<tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>';

        foreach ($dataOrder['productos'] as $producto) {
            $content .= '<tr>';
            $content .= '<td>'.$producto['nombre'].'</td>';
            $content .= '<td>'.$producto['cantidad'].'</td>';
            $content .= '<td>€'.number_format($producto['precio'], 2).'</td>';
            $content .= '<td>€'.number_format($producto['cantidad'] * $producto['precio'], 2).'</td>';
            $content .= '</tr>';
        }

        $content .= '</table>';
        $content .= '</div></body></html>';

        $subject = 'Resumen de Envio';
        $this->setup($subject, $content);
    }
}
