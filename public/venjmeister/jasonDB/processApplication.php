<?php
/*==============================================================================
JSON Loading and Matching: Handles dynamic data loading and matching logic.
Approval Filtering: Filters applications for pending approval.
HTML Table Rendering: Displays the applications with details.
Data Updates: Saves approved IDs and updates the application file.
==============================================================================*/


// Load configuration
/*$config = parse_ini_file('config.ini');
$iv = $config['iv'];
$secretKey = $config['secretKey'];

// Decrypt and extract user data
$encryptedData = $_GET['checkID'] ?? $_POST['checkID'] ?? null;
if (!$encryptedData) {
    die("No checkID provided.");
}
$userData = json_decode(openssl_decrypt($encryptedData, 'aes-256-cbc', $secretKey, 0, $iv), true);
if (!$userData) {
    die("Invalid or corrupted data.");
}
$ID = $userData['ID'];
$name = $userData['approver'];
*/


$ID = "2";// For testing only
// Load JSON files
$applications = json_decode(file_get_contents('application.json'), true);
$authCodes = json_decode(file_get_contents('authcode.json'), true);
// Load workgroup.json
$workgroupFilePath = 'workgroup.json';
$workgroupData = json_decode(file_get_contents($workgroupFilePath), true);
$workgroupRecords = $workgroupData['records'] ?? [];
$approverData = json_decode(file_get_contents('approver.json'), true);

// Initialize a variable to store the matched record
$approverRecord = null;
// Search for the record with the matching ID
foreach ($approverData['records'] as $record) {
    if ($record['code'] === $ID) {
        $approverRecord = $record;
        break;
    }
}
if (!$approverRecord) {
    print_r($approverRecord);
    Exit ("No Approver record found with ID: $ID");
}
// Define approver details
$approvedBy = $approverRecord['Name']; // Replace with your supplied value
$ApproverEmail = $approverRecord['email']; // Replace with your supplied value


// Filter completed applications
$completedApplications = array_filter($applications['records'], function ($record) use ($workgroupRecords, $ID) {
    if (!isset($record['completedModuleIDs'])) {
        return false;
    }

    // Sort both arrays to ensure order doesn't matter
    $moduleIDs = $record['moduleIDs'];
    $completedModuleIDs = $record['completedModuleIDs'];
    sort($moduleIDs);
    sort($completedModuleIDs);

    // Ensure moduleIDs and completedModuleIDs match
    if ($moduleIDs !== $completedModuleIDs) {
        return false;
    }
    if (isset($record['ApprovedBy']) && !empty($record['ApprovedBy'])) {
        return false;
    }
    
    // Concatenate application group and endorserName
    $applicationGroupEndorser = $record['group'] . $record['endorserName'];

    // Search for a matching record in workgroup
    foreach ($workgroupRecords as $workgroupRecord) {
        $workgroupGroupName = $workgroupRecord['group'] . $workgroupRecord['name'];

        // Check if concatenation matches and approverID equals $ID
        if ($applicationGroupEndorser === $workgroupGroupName && $workgroupRecord['approverID'] === $ID) {
            return true;
        }
    }

    return false;
});

// Start HTML output
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Training and Authorisation (OTAS) - Enrolment Approval</title>
<style>
	body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #FFFFFF;
	}
	label {
	display: block;
    margin-bottom: 3px;
	}
	.tabs {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 10px;
    margin-bottom: 10px;	
    }
    .tab-button {
    background-color: #e6e6e6;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px 15px;
    text-decoration: none;
    color: #333;
    font-weight: bold;
    text-transform: capitalize;
    transition: background-color 0.3s, color 0.3s;
    }
    .tab-button:hover {
    background-color: #0056b3;
    color: white;	
    }
	table {
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: white;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
        background-color: #e6e6e6;		
    }

    th {
        background-color: #DDDDDD;
    }
</style>
</head>
<body>
	<h1>Online Training and Authorisation (OTAS) - Enrolment Approval</h1>
    <h2>Welcome, <?php echo htmlspecialchars($approvedBy); ?><br></h2>
    <h3>Please review the applications below for approval.</h3>

    <form method="POST" action="" id="myForm" style="display: form;">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Endorsed By</th>
                    <th>Date</th>
                    <th>Authorisation Categories</th>
                    <th>Completed Date</th>
                    <th>Approved</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($completedApplications as $application): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($application['Name'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($application['Company'] ?? '') ?> <br> <?php  echo htmlspecialchars($application['CompanyName'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($application['endorserName'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($application['endorseDate'] ?? ''); ?></td>
                        <td>
                            <?php
                            if (!empty($application['authCodes'])) {
                                foreach ($application['authCodes'] as $code) {
                                    $title = '';
                                    foreach ($authCodes['records'] as $record) {
                                        if ($record['code'] === $code) {
                                            $title = $record['title'];
                                            break;
                                        }
                                    }
                                    echo htmlspecialchars("$code - $title") . "<br>";
                                }
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($application['completedDate'] ?? date('Y-m-d')); ?></td>
                        <td class="center"><input type="checkbox" name="approved[]" value="<?php echo htmlspecialchars($application['ID']); ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <input type="submit" name="submit" value="Submit">
    </form>

<?php
    
if (empty($application['authCodes'])) {
    echo "<script>document.getElementById(\"myForm\").style.display = \"none\";</script>";
    exit ("<h1><p>All Applications have been approved and processed.</p></h1>");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approved'])) {
    $selectedIDs = $_POST['approved'];
    
    file_put_contents('selected_ids.json', json_encode($selectedIDs, JSON_PRETTY_PRINT));

    // Load application.json
    $applications = json_decode(file_get_contents('application.json'), true);
    
    // Define approver details
    $approveDate = date('Y-m-d');

    // Update records in application.json
    foreach ($applications['records'] as &$record) {
        if (in_array($record['ID'], $selectedIDs)) {
            $record['ApprovedBy'] = $approvedBy;
            $record['ApproverEmail'] = $ApproverEmail;
            $record['ApproveDate'] = $approveDate;
        }
    }
    // Save the updated application.json
    file_put_contents('application.json', json_encode($applications, JSON_PRETTY_PRINT));

    // Execute the process.php script in the background

    exec('php certify.php > /dev/null 2>&1 &');
    
    //header("Location: processApplication.php");
    echo '<meta http-equiv="refresh" content="0;url=processApplication.php">';
}
?>
</body>
</html>
