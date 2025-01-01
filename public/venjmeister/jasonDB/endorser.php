<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpScripts/vendor/autoload.php';


// Load configuration
$config = parse_ini_file('../phpScripts/config.ini', true);

// Load application.json
$applicationFile = 'application.json';
if (!file_exists($applicationFile)) {
    die("Error: application.json not found.");
}

$applicationData = json_decode(file_get_contents($applicationFile), true);
if ($applicationData === null) {
    die("Error: Failed to decode application.json.");
}

// Get POST data
$applicationID = $_POST['ApplicationID'] ?? null;
$selectedCodes = $_POST['selectedCodes'] ?? [];

if (!$applicationID) {
    die("Error: Application ID is missing.");
}

// Find the corresponding application record
$applicationRecord = null;
foreach ($applicationData['records'] as &$record) {
    if ($record['ID'] === $applicationID) {
        $applicationRecord = &$record;
        break;
    }
}

if ($applicationRecord === null) {
    die("Error: Application not found in application.json.");
}

// Extract authorization codes and module IDs
$authCodes = [];
$moduleIDs = [];
foreach ($selectedCodes as $selectedCode) {
    [$authCode, $moduleID] = explode('|', $selectedCode);
    $authCodes[] = $authCode;
    $moduleIDs[] = $moduleID;
}

// Ensure module IDs are unique
$moduleIDs = array_unique($moduleIDs);

// Update the application record
$applicationRecord['endorseDate']  = date('d-M-Y');
$applicationRecord['authCodes'] = $authCodes;
$applicationRecord['moduleIDs'] = $moduleIDs;
$applicationRecord['completedModuleIDs'] = [];

// Save the updated data back to application.json
if (file_put_contents($applicationFile, json_encode($applicationData, JSON_PRETTY_PRINT)) === false) {
    die("Error: Failed to save application.json.");
}

// Send email to the applicant
$mail = new PHPMailer(true);
$emailContent = "";
try {
        // SMTP server configuration        $mail->isSMTP();
        $mail->Host = $config['mail']['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['mail']['username'];
        $mail->Password = $config['mail']['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config['mail']['port'];
        $mail->Port = 587; // Typically 587 for TLS


        // Email sender and recipient
        $mail->setFrom($config['mail']['from_email'], $config['mail']['from_name']);
        $mail->addAddress($applicationRecord['email'], $applicationRecord['Name']);

    // Email subject and body
    $mail->isHTML(true);
    $mail->Subject = 'Your Application Has Been Endorsed';

    $emailContent = "
    <style>
      body {
          font-family: Arial, sans-serif;
          margin: 0;
          padding: 0;
          display: flex;
          justify-content: center;
          align-items: center;
          min-height: 100vh;
          background-color: #f9f9f9;
      }

      .card {
          background: #fff;
          border: 1px solid #ddd;
          border-radius: 10px;
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
          width: 100%;
          max-width: 400px;
          padding: 20px;
          margin: 20px;
          text-align: center;
      }

      .card-title {
          font-size: 1.5rem;
          color: #333;
          margin-bottom: 10px;
      }

      .card-details h3 {
          font-size: 1.2rem;
          margin-bottom: 10px;
          color: #007BFF;
      }

      .success-card {
          border-top: 5px solid #007bff;
      }

      .success-card p {
          font-size: 0.9rem;
          color: #666;
          margin-bottom: 15px;
          text-align: left;
      }
      </style>
	  <div class='card success-card'>
	  	<h2>Email Sent</h2>
		<img src=\"images/paperplane.png\" style=\"width: 80px\"><br>
        <p>Your application has been successfully endorsed. Below are the details:</p>		
        <div class='card-details'>
		<h3>Details:</h3>
            <p><strong>Name:</strong> {$applicationRecord['Name']}</p>
            <p><strong>Email:</strong> {$applicationRecord['email']}</p>
            <p><strong>Company:</strong> {$applicationRecord['Company']}</p>
            <p><strong>Group:</strong> {$applicationRecord['group']}</p>
            <p><strong>Authorisation Codes:</strong> " . implode(', ', $authCodes) . "</p>
            <p><strong>Module IDs:</strong> " . implode(', ', $moduleIDs) . "</p>
        </div>
        <p>You can now proceed to the module selection by clicking the link below:</p>
        <p><strong><center><a href='".$config['server']['url']."jasonDB/domodules.php?ID={$applicationID}'>[ Go to Module Selection ]</a><center></strong></p>
        <p>Best regards,</p>
        <p>The Team</p>
    ";

    $mail->Body = $emailContent;

    // Send email
    $mail->send();
    // echo "Application record updated, and email sent to the applicant.<br><br>";
    // echo "<h3>Email Sent:</h3>";
    echo $emailContent;
} catch (Exception $e) {
    echo "Application record updated, but email failed to send. Error: {$mail->ErrorInfo}";
}
?>
<!-- Venjmeister.com version -->