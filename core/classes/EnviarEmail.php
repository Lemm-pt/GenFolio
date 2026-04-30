<?php
namespace core\classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EnviarEmail {
   public function enviar_contacto($nome, $email, $telefone, $mensagem, $destinatario = null) {
    $destinatario = $destinatario ?? DS_EMAIL;
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = EMAIL_FROM;
            $mail->Password   = EMAIL_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = EMAIL_PORT;

            $mail->setFrom(EMAIL_FROM, 'Site JoFolio');
            $mail->addAddress(DS_EMAIL, 'Joaquina Cruz');
            $mail->isHTML(true);
            $mail->Subject = "Nova mensagem de contacto - $nome";
            $mail->Body    = "<strong>Nome:</strong> $nome<br><strong>Email:</strong> $email<br><strong>Telefone:</strong> $telefone<br><strong>Mensagem:</strong><br>$mensagem";
            $mail->AltBody = "Nome: $nome\nEmail: $email\nTelefone: $telefone\nMensagem:\n$mensagem";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}