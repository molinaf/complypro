<?php
ini_set('display_errors', 1); // Display errors on the browser
ini_set('display_startup_errors', 1); // Display errors during PHP startup
error_reporting(E_ALL); // Report all errors
date_default_timezone_set('Australia/Adelaide');

require 'phpScripts/vendor/autoload.php';

// Import the necessary classes
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Step 1: Generate PDF using dompdf
// Initialize dompdf with options
$options = new Options();
$options->set('isHtml5ParserEnabled', true);  // Enable HTML5 support
$options->set('isPhpEnabled', true);  // Allow PHP functions (like `image()` and `date()`)

// Create an instance of dompdf
$dompdf = new Dompdf($options);

// Load configuration
$config = parse_ini_file('phpScripts/config.ini', true);
$url = $config['server']['url'];
$name = "Felino Molina";
$email = 'felino.molina@gmail.com';
$percentage = 100;
$module = "Live Low Voltage";

$html = "<!DOCTYPE html>
<html lang=\"en\">
<head>
<meta charset=\"UTF-8\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
<title>Power Services - Work Practices and Training - Authorisation Course</title>
<link rel=\"stylesheet\" href=\"".$url."css/style.css\">
</head>
<body>
<div class=\"certificate\"><center>
    <h1>Power and Water Corporation<br>Certificate of Completion</h1>
    <p>This certifies that</p>
    <p><h2>".$name."</h2></p>
    <p>has successfully completed the module</p>
    <p><h2>".$module."</h2></p>
    <p>".date('d-M-Y')."</p>
</div>
</body>
</html>";


// Load HTML into dompdf
$dompdf->loadHtml($html);

// (Optional) Set paper size (A4 is default)
$dompdf->setPaper('A5', 'landscape');

// Render PDF (first pass to process HTML and CSS)
$dompdf->render();

// Step 2: Get the PDF content
$pdfContent = $dompdf->output();  // Get the PDF content as a string


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
	
    $mail->addStringAttachment($pdfContent, 'Certificate of Completion.pdf', 'base64', 'application/pdf');

    // Content
    //$token = generateToken($name, $email, $config['security']['secret_key']);
    //$link = $config['server']['url'] . "phpScripts/verify.php?token=" . urlencode($token); // Change localhost to the new server...

    $mail->isHTML(true);
    $mail->Subject = 'Power Services Authorisation Courses';
    $mail->Body = "Please see attached certificate of completion";
    $mail->send();

    // Store the token in a file  bin2hex(substring($token,0,16))
    //file_put_contents('tokens/' . bin2hex(substr($token,0,16)), 'valid');
	echo "<center style=\"font-family: Arial, sans-serif;\"><img src=\"images/logo.png\" style=\"width: 194px; display: inline-block;\"><br><br>An email has been sent to confirm your enrolment. Please check your inbox and follow the link to access the online courses.<br><img src=\"images/paperplane.png\" style=\"width: 80px\"></center>";
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
