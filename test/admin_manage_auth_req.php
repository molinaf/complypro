<?php
// Include the config file to connect to the database
require_once 'config.php';

// Check if the user is logged in and is an admin
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Administrator') {
    header("Location: login.php");
    exit;
}

// Function to fetch data from a table
function fetchData($conn, $table) {
    $query = "SELECT * FROM $table";
    $result = $conn->query($query);
    return $result;
}

// Function to add data to a table
function addData($conn, $table, $data) {
    $columns = implode(", ", array_keys($data));
    $values = implode(", ", array_map(function($value) use ($conn) {
        return "'" . $conn->real_escape_string($value) . "'";
    }, array_values($data)));
    $query = "INSERT INTO $table ($columns) VALUES ($values)";
    return $conn->query($query);
}

// Function to update data in a table
function updateData($conn, $table, $data, $id_column, $id) {
    $set = implode(", ", array_map(function($key, $value) use ($conn) {
        return "$key = '" . $conn->real_escape_string($value) . "'";
    }, array_keys($data), array_values($data)));
    $query = "UPDATE $table SET $set WHERE $id_column = $id";
    return $conn->query($query);
}

// Handle form submission for adding or updating data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $table = $_POST['table'];
    $id_column = $_POST['id_column'];
    $id = $_POST['id'];
    $data = $_POST['data'];

    if ($id) {
        updateData($conn, $table, $data, $id_column, $id);
    } else {
        addData($conn, $table, $data);
    }
    header("Location: manage_tables.php");
    exit;
}

// Fetch data for each table
$category = fetchData($conn, 'category');
$authorisations = fetchData($conn, 'authorisation');
$module = fetchData($conn, 'module');
$f2f = fetchData($conn, 'f2f');
$license = fetchData($conn, 'license');
$inductions = fetchData($conn, 'induction');

// Fetch categories for grouping authorisations
$categories = fetchData($conn, 'category');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tables</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 30px;
        }
        .table-container {
            margin-bottom: 50px;
    border: 2px solid #007bff; /* Blue border */
    border-radius: 5px; /* Optional: rounds the corners */
    padding: 10px 20px; /* Adds padding inside the button */
    box-shadow: 10px 10px 10px rgba(0, 0, 0, 0.4); /* Adds a soft shadow */
    background-color: #f8f9fa; /* Light background color (optional) */
        }
        .dashboard-btn {
            text-align: center;
            margin-bottom: 20px;
        }
        th:first-child {
            width: 15%;
			text-align: center;
        }
        th:nth-child(2) {
            width: 70%;
        }
        th:last-child {
            width: 15%;
			text-align: center;
        }td:first-child {
			text-align: center;
        }
        td:last-child {
			text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4">Manage Tables</h2>
    
    <div class="dashboard-btn">
        <form action="dashboard.php" method="POST">
            <input type="hidden" name="role" value="Administrator">
            <button type="submit" class="btn btn-primary">Back to Dashboard</button>
        </form>
    </div>

    <!-- Category Table -->
    <div class="table-container">
        <h3>Category</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $category->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['category_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <a href="edit_table.php?table=category&id_column=category_id&id=<?php echo $row['category_id']; ?>" class="btn btn-secondary">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="edit_table.php?table=category" class="btn btn-primary">Add New category</a>
    </div>

    <!-- Authorisation Table -->
    <div class="table-container">
        <h3>Authorisations</h3>
        <?php while ($category = $categories->fetch_assoc()): ?>
        <h4><?php echo $category['name']; ?></h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $authorisation_query = "SELECT * FROM authorisation WHERE category_id = " . $category['category_id'];
                $authorisation_result = $conn->query($authorisation_query);
                while ($row = $authorisation_result->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo $row['authorisation_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <a href="edit_table.php?table=Authorisation&id_column=authorisation_id&id=<?php echo $row['authorisation_id']; ?>" class="btn btn-secondary">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php endwhile; ?>
        <a href="edit_table.php?table=Authorisation" class="btn btn-primary">Add New Authorisation</a>
    </div>

    <!-- Modules Table -->
    <div class="table-container">
        <h3>Online Training Modules</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $module->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['module_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <a href="edit_table.php?table=module&id_column=module_id&id=<?php echo $row['module_id']; ?>" class="btn btn-secondary">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="edit_table.php?table=module" class="btn btn-primary">Add New Module</a>
    </div>

    <!-- F2F Table -->
    <div class="table-container">
        <h3>Face-to-face Training</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $f2f->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['f2f_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <a href="edit_table.php?table=f2f&id_column=f2f_id&id=<?php echo $row['f2f_id']; ?>" class="btn btn-secondary">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="edit_table.php?table=f2f" class="btn btn-primary">Add New F2F</a>
    </div>

    <!-- License Table -->
    <div class="table-container">
        <h3>Licenses</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $license->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['license_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <a href="edit_table.php?table=license&id_column=license_id&id=<?php echo $row['license_id']; ?>" class="btn btn-secondary">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="edit_table.php?table=license" class="btn btn-primary">Add New License</a>
    </div>

    <!-- Induction Table -->
    <div class="table-container">
        <h3>Inductions</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $inductions->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['induction_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <a href="edit_table.php?table=Induction&id_column=induction_id&id=<?php echo $row['induction_id']; ?>" class="btn btn-secondary">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="edit_table.php?table=Induction" class="btn btn-primary">Add New Induction</a>
    </div>
</div>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
