<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }
    public function enviarConfirmacion()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('higuerak047@gmail.com');
        $mail->addAddress('higuerak047@gmail.com');
        $mail->Subject = 'Confirma tu cuenta';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola: </strong>" . $this->nombre . ".</p>";
        $contenido .= "<p>Has creado tu cuents en UpTask solo confirma tu cuenta</p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['UPTASK_URL'] . "/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "Si tu no creaste la cuenta Ignora el mensaje";
        $contenido .= '</html>';

        $mail->Body = $contenido;

        //! Enviar email
        $mail->send();
    }
    public function recuperarPassword()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('higuerak047@gmail.com');
        $mail->addAddress('higuerak047@gmail.com');
        $mail->Subject = 'Reestablecer Password';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola: </strong>" . $this->nombre . ".</p>";
        $contenido .= "<p>Has intentado cambiar tu password</p>";
        $contenido .= "<p>Presiona aquí para continuar: <a href='" . $_ENV['UPTASK_URL'] . "/reestablecer?token=" . $this->token . "'>Reestablecer password</a></p>";
        $contenido .= "Si tu no creaste la cuenta Ignora el mensaje";
        $contenido .= '</html>';

        $mail->Body = $contenido;

        //! Enviar email
        $mail->send();
    }
}
