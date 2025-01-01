<?php

// Load JSON file into an associative array
function loadJSON($filePath) {
    return json_decode(file_get_contents($filePath), true);
}

// Save associative array back to a JSON file
function saveJSON($filePath, $data) {
    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
}

// Check if a specific authCode is completed
function isAuthCodeCompleted($record, $authCode) {
    return in_array($authCode, $record['completedAuthCodes'] ?? []);
}

// Check if a specific moduleID is completed
function isModuleIDCompleted($record, $moduleID) {
    return in_array($moduleID, $record['completedModuleIDs'] ?? []);
}

// Mark an authCode as completed
function completeAuthCode(&$record, $authCode) {
    if (!isAuthCodeCompleted($record, $authCode)) {
        $record['completedAuthCodes'][] = $authCode;
    }
}

// Mark a moduleID as completed and complete related authCodes
function completeModuleID(&$record, $moduleID, $authCodesMap) {
    if (!isModuleIDCompleted($record, $moduleID)) {
        $record['completedModuleIDs'][] = $moduleID;

        // Automatically complete related authCodes
        foreach ($authCodesMap as $code => $relatedModuleID) {
            if ($relatedModuleID === $moduleID) {
                completeAuthCode($record, $code);
            }
        }
    }
}

// Check if all authCodes and moduleIDs are completed
function areAllCompleted($record) {
    $authCodes = $record['authCodes'] ?? [];
    $moduleIDs = array_values($record['moduleIDs'] ?? []);
    $completedAuthCodes = $record['completedAuthCodes'] ?? [];
    $completedModuleIDs = $record['completedModuleIDs'] ?? [];

    $allAuthCodesCompleted = empty(array_diff($authCodes, $completedAuthCodes));
    $allModuleIDsCompleted = empty(array_diff($moduleIDs, $completedModuleIDs));

    return $allAuthCodesCompleted && $allModuleIDsCompleted;
}

// List all application records with completion details
function listApplicationRecords($applicationFile) {
    $applicationData = loadJSON($applicationFile);
    $records = $applicationData['records'];
    $output = [];

    foreach ($records as $record) {
        $completedAuthCodes = $record['completedAuthCodes'] ?? [];
        $totalAuthCodes = $record['authCodes'] ?? [];
        $incompleteAuthCodes = array_diff($totalAuthCodes, $completedAuthCodes);

        $completedModuleIDs = $record['completedModuleIDs'] ?? [];
        $totalModuleIDs = array_values($record['moduleIDs'] ?? []);
        $incompleteModuleIDs = array_diff($totalModuleIDs, $completedModuleIDs);

        $output[] = [
            'ID' => $record['ID'],
            'Name' => $record['Name'],
            'Email' => $record['email'],
            'Company' => $record['Company'],
            'Completed AuthCodes' => implode(", ", $completedAuthCodes),
            'Incomplete AuthCodes' => implode(", ", $incompleteAuthCodes),
            'Incomplete ModuleIDs' => implode(", ", $incompleteModuleIDs),
        ];
    }

    return $output;
}

// Main script logic
$applicationFile = 'application.json';
$authCodeFile = 'authcode.json';

// Load data
$applications = loadJSON($applicationFile);
$authCodesData = loadJSON($authCodeFile);

// Map authCodes to moduleIDs
$authCodesMap = [];
foreach ($authCodesData['records'] as $authRecord) {
    $authCodesMap[$authRecord['code']] = $authRecord['moduleID'];
}

// Example: Update a specific application
foreach ($applications['records'] as &$record) {
    // Example: Mark module "07" as completed
    completeModuleID($record, "07", $authCodesMap);

    // Example: Check if all are completed
    if (areAllCompleted($record)) {
        echo "All completed for application ID: {$record['ID']}<br>";
    }
}

// Save updates back to JSON
saveJSON($applicationFile, $applications);

// Display the output as a table
$applications = listApplicationRecords($applicationFile);

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Application Records</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Application Records</h1>
    <table>
        <thead>
            <tr>
                <th>Application ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Company</th>
                <th>Completed AuthCodes</th>
                <th>Incomplete AuthCodes</th>
                <th>Incomplete ModuleIDs</th>
            </tr>
        </thead>
        <tbody>";

foreach ($applications as $application) {
    echo "<tr>
        <td>{$application['ID']}</td>
        <td>{$application['Name']}</td>
        <td>{$application['Email']}</td>
        <td>{$application['Company']}</td>
        <td>{$application['Completed AuthCodes']}</td>
        <td>{$application['Incomplete AuthCodes']}</td>
        <td>{$application['Incomplete ModuleIDs']}</td>
    </tr>";
}

echo "        </tbody>
    </table>
</body>
</html>";
