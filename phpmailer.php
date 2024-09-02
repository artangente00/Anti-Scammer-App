<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require_once '/Applications/XAMPP/htdocs/App/phpMailer/src/PHPMailer.php';
require_once '/Applications/XAMPP/htdocs/App/phpMailer/src/SMTP.php';
require_once '/Applications/XAMPP/htdocs/App/phpMailer/src/Exception.php';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'faustinetangente00@gmail.com';
    $mail->Password = 'ilovewebdesign123';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  
    $mail->Port = 587;

    // Set email content
    $mail->setFrom('faustinetangente00@gmail.com', 'Faustine');
    $mail->addAddress('artangente00@gmail.com');
    $mail->Subject = 'Test Email';
    $mail->Body = 'This is a test email sent using PHPMailer!';

    // Send email
    $mail->send();
    echo 'Email sent successfully!';
} catch (Exception $e) {
    echo 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
}
?>
