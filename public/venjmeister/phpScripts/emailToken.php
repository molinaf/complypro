<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
} else {
    exit('Invalid entry to this call');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Load configuration
$config = parse_ini_file('config.ini', true);

function generateToken($name, $email, $secretKey) {
    $date = new DateTime();
    $expire = ((int)$date->format('YmdHi')) + 200; // expires in 2 hours

    $data = json_encode(['name' => $name, 'email' => $email, 'expire' => $expire]);
    $iv = random_bytes(16);
    $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $secretKey, 0, $iv);
    return base64_encode($iv . $encryptedData);
}

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = $config['mail']['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['mail']['username'];
    $mail->Password = $config['mail']['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $config['mail']['port'];

    // Recipients
    $mail->setFrom($config['mail']['from_email'], $config['mail']['from_name']);
    $mail->addAddress($email, $name);

    // Content
    $token = generateToken($name, $email, $config['security']['secret_key']);
    $link = $config['server']['url'] . "phpScripts/verify.php?token=" . urlencode($token); // Change localhost to the new server...

    $mail->isHTML(true);
    $mail->Subject = 'Power Services Authorisation Courses';
    $mail->Body = "Click <a href='$link'>here</a> to access Authorisation Course Listing";
    $mail->send();

    // Store the token in a file  bin2hex(substring($token,0,16))
    file_put_contents('tokens/' . bin2hex(substr($token,0,16)), 'valid');
	echo "<center style=\"font-family: Arial, sans-serif;\"><img src=\"../images/logo.png\" style=\"width: 194px; display: inline-block;\"><br><br>An email has been sent to confirm your enrolment. Please check your inbox and follow the link to access the online courses.<br><img src=\"../images/paperplane.png\" style=\"width: 80px\"></center>";
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
