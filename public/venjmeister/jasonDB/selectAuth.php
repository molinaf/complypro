<?php
// Load application.json
$applicationFile = 'application.json';
if (!file_exists($applicationFile)) {
    die("Error: application.json not found.");
}

$applicationData = json_decode(file_get_contents($applicationFile), true);
if ($applicationData === null) {
    die("Error: Failed to decode application.json.");
}

// Get the Application ID from GET parameter
$applicationID = $_GET['ID'] ?? null;
if (!$applicationID) {
    die("Error: Application ID is missing.");
}

// Find the corresponding application record
$applicationRecord = null;
foreach ($applicationData['records'] as $record) {
    if ($record['ID'] === $applicationID) {
        $applicationRecord = $record;
        break;
    }
}

if ($applicationRecord === null) {
    die("Error: Application not found in application.json.");
}

// Check if the application has already been processed
if (!empty($applicationRecord['authCodes'])) {
    // Display notification and application details
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolment Processed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }

        h1 {
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .info {
            margin-bottom: 20px;
        }

        .info strong {
            color: #007BFF;
        }

        .details {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 12px;
            align-items: start;
        }

        .details div {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
        }

        .details .label {
            font-weight: bold;
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Enrolment Processed</h1>
        <p class="info">The application with ID <strong><?php echo htmlspecialchars($applicationID); ?></strong> has already been processed. Below are the details:</p>

        <div class="details">
            <div class="label">Date Applied</div>
            <div><?php echo htmlspecialchars($applicationRecord['Date']); ?></div>

            <div class="label">Name</div>
            <div><?php echo htmlspecialchars($applicationRecord['Name']); ?></div>

            <div class="label">Email</div>
            <div><?php echo htmlspecialchars($applicationRecord['email']); ?></div>

            <div class="label">Company</div>
            <div><?php echo htmlspecialchars($applicationRecord['Company']); ?></div>

            <div class="label">Company Name</div>
            <div><?php echo htmlspecialchars($applicationRecord['CompanyName']); ?></div>

            <div class="label">Group</div>
            <div><?php echo htmlspecialchars($applicationRecord['group']); ?></div>

            <div class="label">Endorser Name</div>
            <div><?php echo htmlspecialchars($applicationRecord['endorserName']); ?></div>

            <div class="label">Endorser Email</div>
            <div><?php echo htmlspecialchars($applicationRecord['EndorserEmail']); ?></div>

            <div class="label">Date Endorsed</div>
            <div><?php echo htmlspecialchars($applicationRecord['endorseDate']); ?></div>

            <div class="label">Authorisation Codes</div>
            <div><?php echo htmlspecialchars(implode(', ', $applicationRecord['authCodes'])); ?></div>

            <div class="label">Module IDs</div>
            <div><?php echo htmlspecialchars(implode(', ', $applicationRecord['moduleIDs'])); ?></div>
        </div>
    </div>
</body>
</html>
    <?php
    exit;
}

// If not processed, show the form
$jsonFile = 'authcode.json';
if (!file_exists($jsonFile)) {
    die("Error: authcode.json not found.");
}

$jsonData = json_decode(file_get_contents($jsonFile), true);
if ($jsonData === null) {
    die("Error: Failed to decode authcode.json.");
}

// Group records by type
$groupedRecords = [];
foreach ($jsonData['records'] as $record) {
    $groupedRecords[$record['type']][] = $record;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorisation Enrolment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .info {
            background-color: #f2f2f2;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #007BFF;
        }

        .info strong {
            color: #007BFF;
        }

        .group {
            margin-bottom: 20px;
        }

        .group h3 {
            margin-bottom: 10px;
            font-size: 18px;
            color: #444;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 8px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .checkbox-item input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
        }

        .checkbox-item label {
            color: #555;
        }

        .submit-btn {
            display: block;
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            font-size: 16px;
            text-align: center;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Authorisation Enrolment</h1>
        <div class="info">
            <p>Application from:</p>
            <p><strong><?php echo htmlspecialchars($applicationRecord['Name']); ?></strong></p>
            <p>Company: <strong><?php echo htmlspecialchars($applicationRecord['Company'] . ': ' . $applicationRecord['CompanyName']); ?></strong></p>
            <p>Date: <strong><?php echo htmlspecialchars($applicationRecord['Date']); ?></strong></p>
            <p>Endorser Name: <strong><?php echo htmlspecialchars($applicationRecord['EndorserName']); ?></strong></p>
        </div>

        <form action="endorser.php" method="post">
            <input type="hidden" name="ApplicationID" value="<?php echo htmlspecialchars($applicationID); ?>">

            <?php foreach ($groupedRecords as $type => $records): ?>
                <div class="group">
                    <h3><?php echo htmlspecialchars($type); ?></h3>
                    <?php foreach ($records as $record): ?>
                        <div class="checkbox-item">
                            <input 
                                type="checkbox" 
                                id="<?php echo htmlspecialchars($record['code']); ?>" 
                                name="selectedCodes[]" 
                                value="<?php echo htmlspecialchars($record['code'] . "|" . $record['moduleID']); ?>" 
                            >
                            <label for="<?php echo htmlspecialchars($record['code']); ?>">
                                <?php echo htmlspecialchars($record['code'] . " - " . $record['title']); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
</body>
</html>