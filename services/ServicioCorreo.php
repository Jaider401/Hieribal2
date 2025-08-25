<?php
namespace Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

final class ServicioCorreo {
    private array $cfg;

    public function __construct(array $config) {
        $this->cfg = $config['mail'] ?? [];
    }

    private function mailer(): PHPMailer {
        $m = new PHPMailer(true);
        $m->isSMTP();
        $m->Host       = $this->cfg['host']      ?? 'smtp.gmail.com';
        $m->Port       = (int)($this->cfg['port'] ?? 587);
        $m->SMTPAuth   = true;

        $secure = strtolower((string)($this->cfg['secure'] ?? 'tls'));
        $m->SMTPSecure = $secure === 'ssl'
            ? PHPMailer::ENCRYPTION_SMTPS
            : PHPMailer::ENCRYPTION_STARTTLS;

        // ✅ usa las claves correctas
        $m->Username   = (string)($this->cfg['username'] ?? '');
        // si por accidente dejaste espacios en la app password, quítalos:
        $m->Password   = str_replace(' ', '', (string)($this->cfg['password'] ?? ''));

        $m->CharSet    = 'UTF-8';
        $m->setFrom(
            (string)($this->cfg['from_email'] ?? $this->cfg['username'] ?? 'no-reply@example.com'),
            (string)($this->cfg['from_name']  ?? 'Hieribal')
        );

        if (!empty($this->cfg['reply_to'])) {
            $m->addReplyTo(
                (string)$this->cfg['reply_to'],
                (string)($this->cfg['reply_to_name'] ?? 'Soporte')
            );
        }

        // Debug sólo si necesitas diagnosticar:
        // $m->SMTPDebug = SMTP::DEBUG_SERVER;
        // $m->Debugoutput = static function ($str, $level) { error_log("PHPMailer[$level]: $str"); };

        return $m;
    }

    public function enviarVerificacion(string $paraEmail, string $paraNombre, string $link): bool {
        try {
            $mail = $this->mailer();
            $mail->addAddress($paraEmail, $paraNombre);
            $mail->isHTML(true);
            $mail->Subject = 'Activa tu cuenta';
            $safeNombre = htmlspecialchars($paraNombre, ENT_QUOTES, 'UTF-8');
            $safeLink   = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
            $mail->Body =
                "<h2>¡Bienvenido a Hieribal, {$safeNombre}!</h2>" .
                "<p>Haz clic para activar tu cuenta:</p>" .
                "<p><a href=\"{$safeLink}\" target=\"_blank\">{$safeLink}</a></p>" .
                "<p>Si no creaste esta cuenta, ignora este mensaje.</p>";
            $mail->AltBody =
                "¡Bienvenido a Hieribal, {$paraNombre}!\n\nActiva tu cuenta con este enlace:\n{$link}\n";

            return $mail->send();
        } catch (Exception $e) {
            error_log('Error enviando verificación: ' . $e->getMessage());
            if (isset($mail)) { error_log('PHPMailer ErrorInfo: ' . $mail->ErrorInfo); }
            return false;
        }
    }

    public function enviarRecuperacion(string $paraEmail, string $link): bool {
        try {
            $mail = $this->mailer();
            $mail->addAddress($paraEmail);
            $mail->isHTML(true);
            $mail->Subject = 'Restablecer contraseña';
            $safeLink = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
            $mail->Body =
                "<h2>Restablece tu contraseña</h2>" .
                "<p>Haz clic en el enlace para crear una nueva contraseña (expira en 1 hora):</p>" .
                "<p><a href=\"{$safeLink}\" target=\"_blank\">{$safeLink}</a></p>";
            $mail->AltBody = "Restablece tu contraseña (expira en 1 hora):\n{$link}";

            return $mail->send();
        } catch (Exception $e) {
            error_log('Error enviando recuperación: ' . $e->getMessage());
            if (isset($mail)) { error_log('PHPMailer ErrorInfo: ' . $mail->ErrorInfo); }
            return false;
        }
    }
}
