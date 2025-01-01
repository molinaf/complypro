<?php
// Fetch GET parameters
$ID = $_GET['ID'] ?? null;
$mID = $_GET['mID'] ?? null;


// Load configuration
$config = parse_ini_file('config.ini', true);

function generateToken($name, $email, $secretKey) {
    $data = json_encode(['ID' => $name, 'mID' => $email]);
    $iv = random_bytes(16);
    $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $secretKey, 0, $iv);
    return base64_encode($iv . $encryptedData);
}

$encodedID = generateToken($ID, $mID, $config['security']['secret_key']);

// Validate inputs
if (!$ID || !$mID) {
    die("Error: Missing ID or mID parameter.");
}

// Load JSON files
$applicationJson = json_decode(file_get_contents("application.json"), true);
$moduleJson = json_decode(file_get_contents("module.json"), true);

// Ensure the JSON structure is correct
if (!isset($applicationJson['records'], $moduleJson['records'])) {
    die("Error: Invalid JSON structure.");
}

// Find the application and module records
$application = array_filter($applicationJson['records'], fn($record) => $record['ID'] == $ID);
$module = array_filter($moduleJson['records'], fn($record) => $record['moduleId'] == $mID);

// Validate that we found the records
$application = reset($application);
$module = reset($module);
if (!$application || !$module) {
    die("Error: Invalid ID or mID parameter.");
}

// Get the name and title
$applicationName = htmlspecialchars($application['Name']);
$moduleTitle = htmlspecialchars($module['Title']);

// Find the first subdirectory in ../PWC/[ID]
$baseDir = "../PWC/$mID/";
$subdirs = glob($baseDir . '*', GLOB_ONLYDIR);
if (empty($subdirs)) {
    die("Error: No subdirectories found in $baseDir.");
}
$firstSubdir = basename($subdirs[0]);

// Determine the iframe source
$iframeSrc = "";
$possibleFiles = ["index_lms.html", "story.html", "index.html"];
foreach ($possibleFiles as $file) {
    if (file_exists("$baseDir/$firstSubdir/$file")) {
        $iframeSrc = "$baseDir/$firstSubdir/$file";
        break;
    }
}
if (!$iframeSrc) {
    die("Error: No valid HTML file found in $baseDir/$firstSubdir.");
}

// Pass data to the HTML template
include 'scormContainer.html';
