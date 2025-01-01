<?php
// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpScripts/vendor/autoload.php';

// Load configuration
$config = parse_ini_file('../phpScripts/config.ini', true);

// File paths
$workgroupFile = 'workgroup.json';
$applicationFile = 'application.json';

// Load JSON data
$workgroupData = json_decode(file_get_contents($workgroupFile), true);
$applicationData = json_decode(file_get_contents($applicationFile), true);

// Get POST data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$company = $_POST['company'] ?? '';
$companyName = $_POST['contractorName'] ?? '';
$workgroup = $_POST['workgroup'] ?? '';

// Validation
if (empty($name) || empty($email) || empty($company) || empty($workgroup) || ($company === 'Contractor' && empty($companyName))) {
    die("<div class='error'>Error: All fields are required. Please ensure no fields are left empty.</div>");
}

// Find selected workgroup
$selectedWorkgroup = array_values(array_filter($workgroupData['records'], function ($record) use ($workgroup) {
    return $record['group'] === $workgroup;
}));

if (empty($selectedWorkgroup)) {
    die("<div class='error'>Error: Invalid workgroup selected.</div>");
}

// Prepare record
$selectedWorkgroupDetails = $selectedWorkgroup[0];
$uniqueID = uniqid();
$newRecord = [
    "ID" => $uniqueID,
    "Date" => date('d-M-Y'),
    "Name" => $name,
    "email" => $email,
    "Company" => $company,
    "CompanyName" => $company === 'Contractor' ? $companyName : '',
    "group" => $selectedWorkgroupDetails['group'],
    "endorserName" => $selectedWorkgroupDetails['name'],
    "EndorserEmail" => $selectedWorkgroupDetails['email'],
    "AuthCodes" => "",
    "ModuleIDs" => ""
];

// Update application data
$applicationData['records'][] = $newRecord;

if (file_put_contents($applicationFile, json_encode($applicationData, JSON_PRETTY_PRINT))) {
    // Email preparation
    $endorserEmail = $selectedWorkgroupDetails['email'];
    $endorserName = $selectedWorkgroupDetails['name'];
    $subject = "New Application Received for Endorsement";
    $message = "
    <div style='font-family: Arial, sans-serif; line-height: 1.6;'>
        <p>Hello <strong>{$endorserName}</strong>,</p>
        <p>An application has been received from:</p>
        <ul>
            <li><strong>Name:</strong> {$name}</li>
            <li><strong>Email:</strong> {$email}</li>
            <li><strong>Company:</strong> {$company}</li>
            <li><strong>Company Name:</strong> " . ($company === 'Contractor' ? $companyName : "N/A") . "</li>
            <li><strong>Workgroup:</strong> {$selectedWorkgroupDetails['group']}</li>
        </ul>
        <p>Please review the application by clicking the link below:</p>
        <p><a href='{$config['server']['url']}/jasonDB/selectAuth.php?ID={$uniqueID}'>Review Application</a></p>
        <p>Thank you.</p>
    </div>";

    // Send email
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $config['mail']['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['mail']['username'];
        $mail->Password = $config['mail']['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config['mail']['port'];

        $mail->setFrom($config['mail']['from_email'], $config['mail']['from_name']);
        $mail->addAddress($endorserEmail, $endorserName);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        echo "
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
          <h2 class='card-title'>Enrolment Submitted Successfully!</h2>
		  <img src=\"images/paperplane.png\" style=\"width: 80px\"><br>
          Notification email has been sent to the endorser.
          <div class='card-details'>
              <h3>Details:</h3>
            <p><strong>Subject:</strong> {$subject}</p>
            <p><strong>Name:</strong> {$endorserName}</p>
            <p><strong>Endorser Email:</strong> {$endorserEmail}</p><br>
            Please wait 48 hours for the endorsement details are emailed to you.
          </div>
      </div>
";
    } catch (Exception $e) {
        echo "<div class='error'>Error: Unable to send email. Mailer Error: {$mail->ErrorInfo}</div>";
    }
} else {
    echo "<div class='error'>Error: Unable to save application.</div>";
}
?>