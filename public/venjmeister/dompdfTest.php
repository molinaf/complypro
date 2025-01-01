<?php
// Include the dompdf autoloader (if installed via Composer)
require 'phpScripts/vendor/autoload.php';

// Import the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;

// Initialize dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);  // Enable PHP if you need to use PHP in your HTML (e.g. for variables or calculations)
$dompdf = new Dompdf($options);

$imageData = base64_encode(file_get_contents('images/logo.png'));
$src = 'data:image/png;base64,' . $imageData;
// HTML content for the PDF
$html = '
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            text-align: center;
            vertical-align: text-top;
            margin: 0;
            border: 5px solid #4a90e2;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .header h1 {
            font-size: 1.8em;
            color: #4a90e2;
        }
        .header p {
            font-size: 1.2em;
        }
        .dynamic-field {
            font-weight: bold;
            color: #333;
        }
        .date, .percentage {
            font-size: 1em;
        }
        .content {
            margin: 2px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            padding: 5px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    <div class="header"><br><br>
<img src="' . $src . '" alt="Logo Image" style="width: 194px; display: inline-block;">
        <h1>Certificate of Completion</h1>
    </div>
    <div class="content">
            <p>This certifies that</p>
            <p class="dynamic-field">Venjie Diola</p>
            <p>has successfully completed the module</p>
            <p class="dynamic-field">Module Title</p>
            <p class="date">Date: <span class="dynamic-field">14 November 2024</span></p>
            <p class="percentage">Score: <span class="dynamic-field">100%</span></p>
    </div>
    <div class="footer">
        Power Services | Work Practices and Training
    </div>
</body>
</html>
';

// Load the HTML content into dompdf
$dompdf->loadHtml($html);

// Set the paper size (optional)
$dompdf->setPaper('A5', 'landscape');

// Render the PDF (first pass - in background)
$dompdf->render();

// Output the PDF (this will send the PDF to the browser)
$dompdf->stream('sample_report.pdf', array('Attachment' => 0)); // 'Attachment' => 0 forces download, 1 forces inline view
?>
