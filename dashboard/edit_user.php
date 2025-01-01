<?php
// Include the config file to connect to the database
require_once 'config.php';

// Check if the user is logged in and is an admin
session_start();
print_r($_SESSION);
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Administrator') {
    header("Location: login.php");
    exit;
}

// Get the user ID from the URL query string
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // Fetch the user data from the database
    $query = "SELECT * FROM users WHERE user_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
        } else {
            echo "User not found!";
            exit;
        }
    } else {
        echo "Error preparing query.";
        exit;
    }
} else {
    echo "No user ID provided!";
    exit;
}

// Handle form submission to update user data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $company_id = $_POST['company_id'];
    
    // Prepare the update query
    $update_query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, role = ?, status = ?, company_id = ? WHERE user_id = ?";
    
    if ($stmt = $conn->prepare($update_query)) {
        $stmt->bind_param("ssssssii", $first_name, $last_name, $email, $phone, $role, $status, $company_id, $user_id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>User updated successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error updating user. Please try again later.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 30px;
            width: 500px;
            border: 2px solid;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.5);
        }
        .form-group {
            margin-bottom: 20px;
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
    <h2 class="mb-4">Edit User</h2>
    
    <div class="dashboard-btn">
        <form action="admin_manage_users.php" method="POST">
            <input type="hidden" name="role" value="Administrator">
            <button type="submit" class="btn btn-primary">Back to Manage Users</button>
        </form>
    </div>
    
    <!-- User Edit Form -->
    <form method="POST" action="edit_user.php?id=<?php echo $user_id; ?>">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
        </div>

        <div class="form-group">
            <label for="role">Role:</label>
            <select class="form-control" id="role" name="role">
                <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                <option value="coordinator" <?php echo ($user['role'] == 'coordinator') ? 'selected' : ''; ?>>Coordinator</option>
                <option value="manager" <?php echo ($user['role'] == 'manager') ? 'selected' : ''; ?>>Manager</option>
                <option value="administrator" <?php echo ($user['role'] == 'administrator') ? 'selected' : ''; ?>>Administrator</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status">
                <option value="active" <?php echo ($user['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo ($user['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>

        <div class="form-group">
            <label for="company_id">Company:</label>
            <select class="form-control" id="company_id" name="company_id">
                <!-- Assuming company data is already in a `companies` table -->
                <?php
                $company_query = "SELECT * FROM company ORDER BY name ASC";
                $company_result = $conn->query($company_query);
                while ($company = $company_result->fetch_assoc()) {
                    $selected = ($company['company_id'] == $user['company_id']) ? 'selected' : '';
                    echo "<option value='" . $company['company_id'] . "' $selected>" . $company['name'] . "</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="admin_manage_users.php" class="btn btn-secondary ml-3">Cancel</a>
    </form>
</div>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
