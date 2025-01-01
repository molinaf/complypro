<?php
// Fetch POST parameters
$encodedID = $_POST['encodedID'] ?? null;
// Validate inputs

// Load configuration
$config = parse_ini_file('config.ini', true);

function decryptToken($token, $secretKey) {
    $data = base64_decode($token);
    $iv = substr($data, 0, 16);
    $encryptedData = substr($data, 16);
    $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $secretKey, 0, $iv);
    return json_decode($decryptedData, true);
}

    $userData = decryptToken($encodedID, $config['security']['secret_key']);
    
    //print_r($userData);
    if ($userData) {
        $ID = $userData['ID'];
        $mID = $userData['mID'];
    } else {
        exit ('<h1>Invalid token</h1>');
    }

// Load the JSON file
$jsonPath = "application.json";
$applicationData = json_decode(file_get_contents($jsonPath), true);

if (!isset($applicationData['records'])) {
    die("Error: Invalid JSON structure.");
}

// Find the record with the matching ID
$recordFound = false;
foreach ($applicationData['records'] as &$record) {
    if ($record['ID'] == $ID) {
        $recordFound = true;

        // Ensure "completedModuleIDs" is set and is an array
        if (!isset($record['completedModuleIDs']) || !is_array($record['completedModuleIDs'])) {
            $record['completedModuleIDs'] = [];
        }

        // Append the mID if not already in the array
        if (!in_array($mID, $record['completedModuleIDs'])) {
            $record['completedModuleIDs'][] = $mID;
        }

        break;
    }
}

// Handle missing record
if (!$recordFound) {
    die("Error: Record with ID=$ID not found.");
}

// Save the updated JSON data back to the file
if (file_put_contents($jsonPath, json_encode($applicationData, JSON_PRETTY_PRINT)) === false) {
    die("Error: Unable to save updates to the JSON file.");
}
header("Location: domodules.php?ID=".$ID);
    exit;
?>
