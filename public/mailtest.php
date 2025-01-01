<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/home/oddzo549/complypro.com.au/public/phpmailer/src/PHPMailer.php';
require '/home/oddzo549/complypro.com.au/public/phpmailer/src/Exception.php';
require '/home/oddzo549/complypro.com.au/public/phpmailer/src/SMTP.php'; // Add this line


$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'e2elearning.com.au'; // Replace with your MAIL_HOST
    $mail->SMTPAuth = true;
    $mail->Username = 'info@e2elearning.com.au'; // Replace with your MAIL_USERNAME
    $mail->Password = 'hU~=2-slu9?!'; // Replace with your MAIL_PASSWORD
    $mail->SMTPSecure = 'tls'; // Replace with your MAIL_ENCRYPTION
    $mail->Port = 587; // Replace with your MAIL_PORT

    $mail->setFrom('info@e2elearning.com.au', 'Test');
    $mail->addAddress('your-email@example.com'); // Replace with your email

    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body = 'This is a test email.';

    $mail->send();
    echo 'Email sent successfully.';
} catch (Exception $e) {
    echo "Email could not be sent. Error: {$mail->ErrorInfo}";
}
