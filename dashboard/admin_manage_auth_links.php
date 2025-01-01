<?php
// Include the config file to connect to the database
require_once 'config.php';

// Check if the user is logged in and is an admin
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Administrator') {
    header("Location: login.php");
    exit;
}

// Fetch the table name dynamically
$table_name = isset($_GET['table']) ? $_GET['table'] : 'module';  // Default to 'module' if no table is specified
if ($table_name == "prerequisite") {
	$table_name="authorisation";
}
// Define the related table and authorisation table
$table_id = $table_name . '_id';
$table_name_capitalized = ucfirst($table_name);

// Fetch Authorisations with Category Name (JOIN with category table)
$authorisations_result = $conn->query("SELECT a.*, c.name as category_name FROM authorisation a 
                                        LEFT JOIN category c ON a.category_id = c.category_id
                                        ORDER BY c.name, a.authorisation_id");
$authorisations = [];
if ($authorisations_result) {
    while ($row = $authorisations_result->fetch_assoc()) {
        $authorisations[] = $row;
    }
}

// Fetch Items (Module, F2F, Induction, License) dynamically
$items_result = $conn->query("SELECT * FROM $table_name ORDER BY {$table_name}_id");
$items = [];
if ($items_result) {
    while ($row = $items_result->fetch_assoc()) {
        $items[] = $row;
    }
}

// Fetch Links (joined data for better readability)
$links_result = $conn->query("SELECT * FROM authorisation_$table_name ORDER BY {$table_name}_id");
$links = [];
if ($links_result) {
    while ($row = $links_result->fetch_assoc()) {
        $links[] = $row;
    }
}

// Group Authorisations by Category
$groupedAuthorisations = [];
foreach ($authorisations as $authorisation) {
    $groupedAuthorisations[$authorisation['category_name']][] = $authorisation;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1600px, initial-scale=1.0">
    <title>Manage Authorisation-<?= ucfirst($table_name) ?> Links</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 30px;
            margin-left: 5%;
        }
        .table-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 50px;
            width: 100%;
        }
        .table-container .table {
            width: 94%; /* Set table width to 27% */
            margin-right: 3%; /* Add a little gap between tables */
        }
        .table-container .table:last-child {
            margin-right: 0; /* Remove margin from the last table */
        }
        th, td {
            text-align: center;
        }
        .selected {
            background-color: #ffcc00 !important;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table-responsive {
            margin-bottom: 20px;
        }
        .dashboard-btn {
            text-align: center;
            margin-bottom: 20px;
        }
        .category-label {
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Authorisation-<?= ucfirst($table_name) ?> Links</h2>
    
    <div class="dashboard-btn">
        <button class="btn btn-primary" id="link-btn">Create Link</button>
    </div>

    <div class="table-container">
        <!-- Authorisation Table Grouped by Category Name -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Authorisation</th>
                        <th>ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($groupedAuthorisations as $categoryName => $authorisations): ?>
                        <tr class="table-info">
                            <td colspan="2" class="category-label" data-category="<?= htmlspecialchars($categoryName) ?>"><strong><?= htmlspecialchars($categoryName) ?></strong></td>
                        </tr>
                        <?php foreach ($authorisations as $authorisation): ?>
                            <tr class="authorisation-row" data-id="<?= $authorisation['authorisation_id'] ?>" data-category="<?= htmlspecialchars($authorisation['category_name']) ?>">
                                <td><?= htmlspecialchars($authorisation['name']) ?></td>
                                <td><?= htmlspecialchars($authorisation['authorisation_id']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Dynamic Table (Module, F2F, Induction, License) -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th><?= ucfirst($table_name) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr class="<?= $table_name ?>-row" data-id="<?= $item["{$table_name}_id"] ?>">
                            <td><?= $item["{$table_name}_id"] ?></td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Link Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>AuthoID</th>
                        <th><?= ucfirst($table_name) ?>ID</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="links-table-body">
                    <?php foreach ($links as $link): ?>
                        <tr>
                            <td><?= $link['authorisation_id'] ?></td>
                            <td><?= $link["{$table_name}_id"] ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-link" data-link-id="<?= $link["authorisation_id"].",".$link["{$table_name}_id"].",authorisation_$table_name" ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Delete Confirmation -->
<div class="modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Delete Link</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this link?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Toggle row selection on click (authorisation and dynamic tables)
    $(".authorisation-row, .<?= $table_name ?>-row").click(function() {
        $(this).siblings().removeClass("selected");
        $(this).toggleClass("selected");
    });

    // Toggle selection for all authorisations in a category when category label is clicked
    $(".category-label").click(function() {
        var category = $(this).data("category");
        var authorisations = $("tr[data-category='" + category + "']");

        // Check if the category is already selected (toggle logic)
        if (authorisations.hasClass("selected")) {
            authorisations.removeClass("selected");
        } else {
            authorisations.addClass("selected");
        }
    });

    // Create Link Button
    $("#link-btn").click(function() {
        var selectedAuthorisations = $(".authorisation-row.selected");
        var selectedItems = $(".<?= $table_name ?>-row.selected");

        if (selectedAuthorisations.length && selectedItems.length) {
            var authorisationIds = selectedAuthorisations.map(function() {
                return $(this).data("id");
            }).get();

            var itemIds = selectedItems.map(function() {
                return $(this).data("id");
            }).get();

            // Send the data as array if multiple items are selected
            $.ajax({
                url: 'manage_links.php',
                type: 'POST',
                data: {
                    authorisation_id: authorisationIds,
                    item_id: itemIds,
                    table_name: ('authorisation_' + '<?= $table_name ?>'),
                    action: 'create'
                },
                success: function(response) {
                    window.location.assign('admin_manage_auth_links.php?table=<?= $table_name ?>');
                    $("#links-table-body").html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Error creating link(s):", error);
                }
            });
        } else {
            alert("Please select both an authorisation and an item to create a link.");
        }
    });

    // Show delete modal for link
    $(".delete-link").click(function() {
        var linkId = $(this).data("link-id");
        $("#deleteModal").data("link-id", linkId).modal("show");
    });

    // Confirm delete link
    $("#confirm-delete").click(function() {
        var linkId = $("#deleteModal").data("link-id");
        var linkParts = linkId.split(",");
        var authorisationId = linkParts[0];
        var itemId = linkParts[1];
        var tableName = linkParts[2];

        $.ajax({
            url: 'manage_links.php',
            type: 'POST',
            data: {
                authorisation_id: authorisationId,
                item_id: itemId,
                table_name: tableName,
                action: 'delete'
            },
            success: function(response) {
                window.location.assign('admin_manage_auth_links.php?table=<?= $table_name ?>');
                $("#links-table-body").html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error deleting link:", error);
            }
        });

        $("#deleteModal").modal("hide");
    });
});
</script>

</body>
</html>
