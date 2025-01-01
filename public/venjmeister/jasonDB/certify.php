<?php
/*==========================================================================
This PHP script:
Reads Data: Loads applications, authentication codes, and selected IDs from JSON files.
Filters Applications: Processes only those with IDs in selected_ids.json.
Creates Certificates: Generates personalized HTML certificates with applicant details, company info, endorsers, approvers, and completed auth codes.
Saves Output: Stores each certificate in the certificates folder as an HTML file named after the applicant's ID.
Ensures Directory: Automatically creates the certificates folder if it doesn’t exist.
It automates certificate generation for selected applicants.
============================================================================*/

require '../phpScripts/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;


// Load the application, auth code, and selected IDs data
$applications = json_decode(file_get_contents('application.json'), true);
$authCodesData = json_decode(file_get_contents('authcode.json'), true);
$selectedIDs = json_decode(file_get_contents('selected_ids.json'), true); // Load selected IDs

// Check if selected_ids.json exists
if (!file_exists('selected_ids.json')) {
    echo "Error: selected_ids.json file not found. Exiting...\n";
    exit;
}

// Create a lookup array for auth codes and their titles
$authCodeLookup = [];
foreach ($authCodesData['records'] as $record) {
    $authCodeLookup[$record['code']] = $record['title'];
}

// Ensure certificates directory exists
if (!is_dir('certificates')) {
    mkdir('certificates', 0777, true);
}

// Process applications with IDs in selected_ids.json
foreach ($applications['records'] as $application) {
    // Check if the application's ID is in the selected IDs
    if (!in_array($application['ID'], $selectedIDs)) {
        continue;
    }

    // Fetch applicant details
    $applicantName = isset($application['Name']) ? $application['Name'] : 'Unknown';
    $applicantEmail = isset($application['email']) ? $application['email'] : 'Unknown';
    $company = (isset($application['Company']) ? $application['Company'] : 'Unknown') . ": " . (isset($application['CompanyName']) ? $application['CompanyName'] : 'Unknown');
    $endorserName = isset($application['endorserName']) ? $application['endorserName'] : 'Unknown';
    $endorserEmail = isset($application['EndorserEmail']) ? $application['EndorserEmail'] : 'Unknown';
    $approverName = isset($application['ApprovedBy']) ? $application['ApprovedBy'] : 'Unknown';
    $approverEmail = isset($application['ApproverEmail']) ? $application['ApproverEmail'] : 'Unknown';
    $approvedDate = isset($application['ApproveDate']) ? $application['ApproveDate'] : 'Unknown';
    $applicantID = isset($application['ID']) ? $application['ID'] : uniqid();

    // Fetch auth codes
    $authCodes = isset($application['authCodes']) ? $application['authCodes'] : [];
    $authCodeDetails = [];
    foreach ($authCodes as $authCode) {
        $authTitle = isset($authCodeLookup[$authCode]) ? $authCodeLookup[$authCode] : "Unknown Title";
        $authCodeDetails[] = "$authCode: $authTitle";
    }

    // Generate certificate HTML
    $certificateHTML = "
    <html>
    <head>
        <title>Certificate of Completion</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; margin: 0; padding: 20px; }
            .certificate { border: 5px solid #000; padding: 20px; margin: 20px auto; width: 500px; }
            .header { font-size: 24px; font-weight: bold; margin-bottom: 20px; }
            .details { text-align: left; margin: 20px auto; width: 80%; }
            .authcodes { margin-top: 20px; text-align: left; }
        </style>
    </head>
    <body>
        <div class='certificate'>
            <div class='header'>Certificate of Completion</div>
            <div class='details'>
                <p>Applicant: <strong>$applicantName</strong></p>
                <p>Email: <strong>$applicantEmail</strong></p>
                <p>Company: <strong>$company</strong></p>
                <p>Endorsed by: <strong>$endorserName</strong> </p>
                <p>Approved by: <strong>$approverName</strong> </p>
                <p>Approved Date: <strong>$approvedDate</strong> </p>
            </div>
            <div class='authcodes'>
                <h3>Completed Auth Codes:</h3>
                <ul>";
    foreach ($authCodeDetails as $authCodeDetail) {
        $certificateHTML .= "<li>$authCodeDetail</li>";
    }
    $certificateHTML .= "
                </ul>
            </div>
        </div>
    </body>
    </html>";

// Load HTML content
$html = $certificateHTML;

// Create an instance of Dompdf
$dompdf = new Dompdf();
$dompdf->set_option('isHtml5ParserEnabled', true);
$dompdf->set_option('isPhpEnabled', true);


$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'landscape');
//=========================================
// Define the file path and name

$dompdf->render();

$filePath = 'certificates/';
$fileName = $applicantID . '.pdf';

// Ensure the certificates directory exists
if (!is_dir($filePath)) {
    mkdir($filePath, 0777, true);
}

// Save the PDF to the file
$pdfContent = $dompdf->output();
if (empty($pdfContent)) {
    echo "Error: No content generated in the PDF.\n";
} else {
    echo "PDF content generated.\n";
    file_put_contents($filePath . $fileName, $pdfContent);
}
//file_put_contents($filePath . $fileName, $dompdf->output());


//=========================================
    // Save certificate to a file
    echo "Certificate created for applicant: $applicantName ($fileName)\n";
}

exec('php emailProcessedApplication.php > /dev/null 2>&1 &');
?>
