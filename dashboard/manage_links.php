<?php
// Include the config file to connect to the database
require_once 'config.php';

error_log(print_r($_POST, true));
// Get data from AJAX POST request
$authorisationIds = $_POST['authorisation_id'];
$itemId = $_POST['item_id'][0]; // Dynamic for module, f2f, induction, license
$tableName = $_POST['table_name']; // Example: 'authorisation_module', 'authorisation_f2f'
$action = $_POST['action']; // 'insert' or 'delete'

// Determine the subTableName for later use
$parts = explode("_", $tableName); // Split the string by "_"
$subTableName = $parts[1]; // Get the second element (index 1)

// Dynamically determine the item ID and corresponding link table
if ($tableName !== 'authorisation_authorisation') {
    $itemColumn = $tableName === 'authorisation_module' ? 'module_id' :
                  ($tableName === 'authorisation_f2f' ? 'f2f_id' :
                  ($tableName === 'authorisation_induction' ? 'induction_id' : 'license_id'));
} else {
    $itemColumn = "prerequisite_authorisation_id";
    $tableName = "authorisation_prerequisite";
}

// Execute action based on 'insert' or 'delete'
if ($action === 'create') {
    // Insert the new link into the database
    //$authorisationId = $authorisationIds[0];
    foreach ($authorisationIds as $authorisationId) {
        $query = "INSERT INTO $tableName (authorisation_id, $itemColumn) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $authorisationId, $itemId);
        
        error_log("authorisation_id:$authorisationId   $itemColumn:$itemId");
    
        if ($stmt->execute()) {
            error_log( "Insert successful.");
        } else {
            error_log( "Error inserting data: " . $stmt->error);
        }
    }
} elseif ($action === 'delete') {
    // Delete the link from the database
    $query = "DELETE FROM $tableName WHERE authorisation_id = ? AND $itemColumn = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $authorisationIds, $itemId);

} else {
    // Handle invalid action
    error_log( "Invalid action provided.");
}


if ($stmt->execute()) {
    // Commit the transaction to ensure the delete operation is finalized
    $conn->commit();

    // Fetch the updated links dynamically based on the table
    $links_result = $conn->query("
        SELECT *
        FROM $tableName" );

    $links = [];
    if ($links_result) {
        while ($row = $links_result->fetch_row()) {
            $links[] = $row;
        }
    }

    // Generate the HTML to update the links table
    $html = '';
    foreach ($links as $link) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($link[0]) . '</td>';
        $html .= '<td>' . htmlspecialchars($link[1]) . '</td>';
        $html .= '<td><button class="btn btn-danger btn-sm delete-link" data-link-id="' . $link["authorisation_id"] . ',' . $link["{$tableName}_id"] . ',authorisation_' . $tableName . '">Delete</button></td>';
        $html .= '</tr>';
    }
    // Return the generated HTML
    echo $html;
} else {
    // If the insert failed, return an error
    error_log( "Error: " . $stmt->error);
}

// Helper function to get the related item table name
function getItemTable($tableName) {
    switch ($tableName) {
        case 'authorisation_module':
            return 'module';
        case 'authorisation_f2f':
            return 'f2f';
        case 'authorisation_induction':
            return 'induction';
        case 'authorisation_license':
            return 'license';
        default:
            return '';
    }
}

// Helper function to get the item column name based on the table
function getItemColumn($tableName) {
    switch ($tableName) {
        case 'authorisation_module':
            return 'module_id';
        case 'authorisation_f2f':
            return 'f2f_id';
        case 'authorisation_induction':
            return 'induction_id';
        case 'authorisation_license':
            return 'license_id';
        default:
            return '';
    }
}
?>