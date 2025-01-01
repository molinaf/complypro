<?php
/*=============================================================================
This script:
Reads applicant data from JSON.
Sends emails to applicants who match selectedIDs, attaching their certificates if available.
Logs successes and failures during the email process.
Updates the application data to remove records for which emails were successfully sent.
==============================================================================*/

require '../phpScripts/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load configuration
$config = parse_ini_file('../phpScripts/config.ini', true);
print_r($config['mail']);
// Load JSON files
$idsFile = file_get_contents('selected_ids.json');
$applicationsFile = file_get_contents('application.json');

// Decode JSON
$ids = json_decode($idsFile, true);
$applicationsData = json_decode($applicationsFile, true);

// Validate structure
if (!isset($applicationsData['records'])) {
    die("Error: 'records' key not found in application.json\n");
}

// Extract the "records" array
$applications = $applicationsData['records'];

// Filter records based on IDs
$retrievedRecords = [];
foreach ($applications as $application) {
    if (isset($application['ID']) && in_array($application['ID'], $ids, true)) { // Match "ID" field
        $retrievedRecords[] = $application;
    }
}
print_r($retrievedRecords);

// Set up PHPMailer
function sendEmail($record, $config) {
    //return true;
    $mail = new PHPMailer(true);

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
        
        $mail->addAddress($record['email'], $record['Name']); // Adjust "email" and "Name" based on JSON structure
        
        
    // Email subject and body
    $mail->isHTML(true);
    $mail->Subject = 'Your Authorisation Has Been Approved';

    $emailContent = "<h1>hello world</h1>";
    $mail->Body = $emailContent;

        // Add attachment (e.g., certificates/<ID>.pdf)
        $certificatePath = __DIR__ ."/certificates/{$record['ID']}.pdf"; // Use "ID" __DIR__ . 
        echo "-----".$certificatePath."-----";
        if (file_exists($certificatePath)) {
            $mail->addAttachment($certificatePath);
        } else {
            echo "Warning: Certificate not found for ID {$record['ID']}.\n";
        }

        $mail->send();
        echo "Email sent to {$record['Name']} ({$record['email']})\n";
        return true;
    } catch (Exception $e) {
        echo "Failed to send email to {$record['email']}: {$mail->ErrorInfo}\n";
        return false;
    }
}

// Process and send emails
$successfulEmails = [];
foreach ($retrievedRecords as $record) {
    if (sendEmail($record, $config)) {
        $successfulEmails[] = $record['ID']; // Track successfully sent emails using "ID"
    }
}

// Remove successfully processed records from applications.json
$applications = array_filter($applications, function ($application) use ($successfulEmails) {
    return !in_array($application['ID'], $successfulEmails);
});

// Save the updated applications.json
$applicationsData['records'] = array_values($applications); // Replace the "records" key
//file_put_contents('application.json', json_encode($applicationsData, JSON_PRETTY_PRINT));

echo "Updated applications.json file saved.\n";
?>
