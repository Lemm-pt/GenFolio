<?php
namespace core\classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EnviarEmail {
    
    // ============================================================
    // ENVIAR EMAIL DE CONFIRMAÇÃO DE REGISTO
    // ============================================================
    public function enviar_confirmacao_registo($email, $token, $slug) {
    $link = BASE_URL . 'index.php?a=confirmar_email&token=' . $token;
        $site_link = BASE_URL . $slug . '/';
        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = EMAIL_FROM;
            $mail->Password   = EMAIL_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL para porta 465
            $mail->Port       = EMAIL_PORT;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(EMAIL_FROM, APP_NAME);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = APP_NAME . ' - Confirmação de registo';
            
            $html = '<h2>Bem-vindo à ' . APP_NAME . '!</h2>';
            $html .= '<p>Para ativar a sua conta, clique no link abaixo:</p>';
            $html .= '<p><a href="'.$link.'">Confirmar Email</a></p>';
          
            $html .= '<p><small>Este link expira em 24 horas.</small></p>';
            
            $mail->Body = $html;
            $mail->AltBody = "Confirme o seu email acedendo a: $link";
            
            $mail->send();
            return true;
     } catch (Exception $e) {
    error_log("ERRO EMAIL COMPLETO: " . $e->getMessage());
    error_log("ERRO EMAIL LINE: " . $e->getLine());
    error_log("ERRO EMAIL FILE: " . $e->getFile());
    error_log("EMAIL DESTINO: " . $email);
    error_log("LINK: " . $link);
    error_log("SMTP HOST: " . EMAIL_HOST);
    error_log("SMTP PORT: " . EMAIL_PORT);
    return false;
}
    }
    
public function enviar_recuperacao_codigo($email, $token, $slug)
{
      // 🔥 O link DEVE incluir o slug para identificar o cliente
    $link = BASE_URL . 'index.php?a=recuperar_codigo_confirmar&token=' . urlencode($token) . '&slug=' . urlencode($slug);
    
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = EMAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = EMAIL_FROM;
        $mail->Password   = EMAIL_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = EMAIL_PORT;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom(EMAIL_FROM, APP_NAME);
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = APP_NAME . ' - Recuperação de código de acesso';
        
        $html = '<h2>🔐 Recuperação de Código de Acesso</h2>';
        $html .= '<p>Clique no link abaixo para criar um novo código de acesso (1 a 7 dígitos):</p>';
        $html .= '<p><a href="'.$link.'">Redefinir Código</a></p>';
        $html .= '<p>Se não solicitou esta alteração, ignore este email.</p>';
        
        $mail->Body = $html;
        $mail->AltBody = "Redefina o seu código de acesso acedendo a: $link";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
    error_log("❌ ERRO EMAIL RECUPERAÇÃO: " . $e->getMessage());
    error_log("❌ ERRO EMAIL LINE: " . $e->getLine());
    error_log("❌ ERRO EMAIL FILE: " . $e->getFile());
    error_log("❌ EMAIL DESTINO: " . $email);
    error_log("❌ TOKEN: " . $token);
    return false;
}
}
    
    // ============================================================
    // ENVIAR CONTACTO
    // ============================================================
    public function enviar_contacto($nome, $email, $telefone, $mensagem, $destinatario = null) {
        $destinatario = $destinatario ?? EMAIL_FROM;
        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = EMAIL_FROM;
            $mail->Password   = EMAIL_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = EMAIL_PORT;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(EMAIL_FROM, APP_NAME);
            $mail->addAddress($destinatario);
            $mail->isHTML(true);
            $mail->Subject = "Nova mensagem de contacto - $nome";
            $mail->Body    = "<strong>Nome:</strong> $nome<br><strong>Email:</strong> $email<br><strong>Telefone:</strong> $telefone<br><strong>Mensagem:</strong><br>$mensagem";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erro ao enviar contacto: " . $mail->ErrorInfo);
            return false;
        }
    }
}