<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'gustavoalexiscuevas@gmail.com';
    $mail->Password   = 'bhgn jeju ajnu vhtm'; // 👈 pon la generada en Google
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('gustavoalexiscuevas@gmail.com', 'Hieribal');
    $mail->addAddress('otrocorreo@gmail.com', 'Prueba');

    $mail->isHTML(true);
    $mail->Subject = 'Prueba de envío';
    $mail->Body    = 'Si ves esto, ¡tu Gmail está funcionando!';

    $mail->send();
    echo '✅ Correo enviado correctamente';
} catch (Exception $e) {
    echo "❌ Error al enviar: {$mail->ErrorInfo}";
}
