<?php
// Include the config file to connect to the database
require_once 'config.php';

// Check if the user is logged in and is an admin
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Administrator') {
    header("Location: login.php");
    exit;
}

// Get the table and ID from the URL query string
$table = isset($_GET['table']) ? $_GET['table'] : '';
$id_column = isset($_GET['id_column']) ? $_GET['id_column'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Fetch the data if an ID is provided
$data = [];
if ($id) {
    $query = "SELECT * FROM $table WHERE $id_column = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Dynamically bind the result columns to variables
        $meta = $stmt->result_metadata();
        $fields = [];
        $field_names = [];
        while ($field = $meta->fetch_field()) {
            $fields[$field->name] = null;
            $field_names[] = &$fields[$field->name];
        }
        call_user_func_array([$stmt, 'bind_result'], $field_names);

        // Fetch the single record
        if ($stmt->fetch()) {
            $data = $fields;
        } else {
            echo "Record not found!";
            $stmt->close();
            exit;
        }
        $stmt->close();  // Close the statement
    } else {
        echo "Error preparing query.";
        exit;
    }
}

// Fetch categories for the dropdown
$categories = [];
if ($table == 'Authorisation') {
    $category_query = "SELECT * FROM category";
    if ($category_result = $conn->query($category_query)) {
        while ($category = $category_result->fetch_assoc()) {
            $categories[] = $category;
        }
        $category_result->free(); // Clear the result set
    }
}

// Handle form submission to add or update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST['data'];
    if ($id) {
        // Update existing record
        $set = implode(", ", array_map(function ($key, $value) use ($conn) {
            return "$key = '" . $conn->real_escape_string($value) . "'";
        }, array_keys($data), array_values($data)));
        $query = "UPDATE $table SET $set WHERE $id_column = $id";
    } else {
        // Add new record
        $columns = implode(", ", array_keys($data));
        $values = implode(", ", array_map(function ($value) use ($conn) {
            return "'" . $conn->real_escape_string($value) . "'";
        }, array_values($data)));
        $query = "INSERT INTO $table ($columns) VALUES ($values)";
    }

    if ($conn->query($query)) {
        header("Location: admin_manage_auth_req.php");
        exit;
    } else {
        echo "Error executing query: " . $conn->error; // Output the exact error for debugging
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Table</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 30px;
            width: 500px;
            border: 2px solid;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 40px;
        }
        .form-group {
            margin-bottom: 40px;
        }
        .alert {
            margin-top: 20px;
        }
        .dashboard-btn {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4"><?php echo $id ? 'Edit' : 'Add'; ?> <?php echo ucfirst($table); ?></h2>
    
    <div class="dashboard-btn">
        <center><a href="admin_manage_auth_req.php" class="btn btn-primary">Back to Manage Tables</a></center>
    </div>
    
    <!-- Edit/Add Form -->
    <form method="POST" action="edit_table.php?table=<?php echo $table; ?>&id_column=<?php echo $id_column; ?>&id=<?php echo $id; ?>">
        <?php foreach ($data as $key => $value): ?>
        <div class="form-group">
            <label for="<?php echo $key; ?>"><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</label>
            <input type="text" class="form-control" id="<?php echo $key; ?>" name="data[<?php echo $key; ?>]" value="<?php echo htmlspecialchars($value); ?>" required>
        </div>
        <?php endforeach; ?>
        <?php if ($table == 'Authorisation'): ?>
        <div class="form-group">
            <label for="category_id">Category:</label>
            <select class="form-control" id="category_id" name="data[category_id]" required>
                <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['category_id']; ?>" <?php echo ($category['category_id'] == $data['category_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['category_name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
        <?php if (!$id): ?>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="data[name]" required>
        </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary"><?php echo $id ? 'Update' : 'Add'; ?></button>
        <a href="admin_manage_auth_req.php" class="btn btn-secondary ml-3">Cancel</a>
    </form>
</div>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
