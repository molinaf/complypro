<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolment Details</title>
	<link rel="stylesheet" href="css\style.css">
</head>
<body>
    <?php
    // Load configuration
    $config = parse_ini_file('../phpScripts/config.ini', true);
    
    // File paths
    $files = [
        'application' => "application.json",
        'authcode' => "authcode.json",
        'module' => "module.json"
    ];

    // Load and parse JSON files
    function loadJson($filePath, $key = null) {
        $data = json_decode(file_get_contents($filePath), true);
        if ($data === null || ($key && !isset($data[$key]))) {
            die("<p>Error: Failed to parse {$filePath} or invalid structure.</p>");
        }
        return $key ? $data[$key] : $data;
    }

    $applications = loadJson($files['application'], 'records');
    $authcodeRecords = loadJson($files['authcode'], 'records');
    $modules = loadJson($files['module'], 'records');

    // Validate and get ID from query string
    $requestedID = $_GET['ID'] ?? null;
    if (!$requestedID) {
        die("<p>Error: No ID parameter provided.</p>");
    }

    // Find application record by ID
    $application = array_filter($applications, fn($record) => $record['ID'] === $requestedID);
    if (!$application) {
        die("<p>Error: Application record not found.</p>");
    }
    $application = reset($application); // Get the first matching record

    // Helper to find title by code or module ID
    function findTitle(array $records, string $key, string $value, string $titleField) {
        foreach ($records as $record) {
            if (($record[$key] ?? null) === $value) {
                return $record[$titleField] ?? "Unknown Title";
            }
        }
        return "Unknown Title";
    }
    ?>
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
    <div class='card-details'>
	<h2>Enrolment Details</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($application['Name']) ?></p>
    <p><strong>Company:</strong> <?= htmlspecialchars($application['Company']) ?></p>
    <p><strong>Company Name:</strong> <?= htmlspecialchars($application['CompanyName']) ?></p>
    <p><strong>Endorser Name:</strong> <?= htmlspecialchars($application['endorserName']) ?></p>
    <p><strong>Endorse Date:</strong> <?= htmlspecialchars($application['endorseDate']) ?></p>

    <h2>Authorisation Categories</h2>
    <ul>
        <?php foreach ($application['authCodes'] as $authCode): 
            $title = findTitle($authcodeRecords, 'code', $authCode, 'title'); ?>
            <li align='left'><?= htmlspecialchars($authCode) ?>: <?= htmlspecialchars($title) ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Training Modules to Complete</h2>
    <?php
    $modulesToComplete = array_diff($application['moduleIDs'], $application['completedModuleIDs']);
    if (empty($modulesToComplete)): ?>
        <h2>Congratulations, you have completed the required modules</h2>
        <p>Please wait for a few days for this application to be approved and entered into the Authorisation database.<br />
        Once the approval have been given, you will receive your Certificate of Completion via your registered email.</p>
    <?php else: ?>
        <ul><?php foreach ($modulesToComplete as $moduleId): 
                $title = findTitle($modules, 'moduleId', $moduleId, 'Title');
                $baseDir = __DIR__ . "/../PWC/{$moduleId}/";

                if (is_dir($baseDir)) {
                    // Scan for all subdirectories inside the PWC/[ID] directory
                    $subdirectories = glob($baseDir . '*/', GLOB_ONLYDIR);
                
                    // If there are subdirectories, use the first one
                    if (count($subdirectories) > 0) {
                        // Get the first subdirectory
                        $firstSubdir = $subdirectories[0];
                
                        // Check for index_lms.html or story.html in the first subdirectory
                        if (file_exists($firstSubdir . 'index_lms.html')) {
                            $link = str_replace(__DIR__, '', $firstSubdir . 'index_lms.html');
                        } elseif (file_exists($firstSubdir . 'index.html')) {
                            $link = str_replace(__DIR__, '', $firstSubdir . 'index.html');
                        } elseif (file_exists($firstSubdir . 'story.html')) {
                            $link = str_replace(__DIR__, '', $firstSubdir . 'story.html');
                        } else {
                            echo "No valid HTML file (index.html or story.html) found in the first subdirectory.";
                            exit;
                        }
                
                        // Generate the link
                    } else {
                        echo "No subdirectories found under PWC/$id.";
                    }
                } else {
                    echo "Error: Directory PWC/$id not found.";
                }
                ?>
                <li align='left'><strong><a href='scormContainer.php?ID=<?= $requestedID."&mID=".$moduleId ?>'><?= $title?></a></strong></li>
            <?php endforeach; ?>
		</ul>
    <?php endif; ?>
	</div>
	</div>
</body>
</html>
