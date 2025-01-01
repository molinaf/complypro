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

// Get the company ID from the URL query string
if (isset($_GET['id'])) {
    $company_id = $_GET['id'];
    
    // Fetch the company data from the database
    $query = "SELECT * FROM company WHERE company_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $company_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $company = $result->fetch_assoc();
        } else {
            echo "Company not found!";
            exit;
        }
    } else {
        echo "Error preparing query.";
        exit;
    }
} else {
    echo "No company ID provided!";
    exit;
}

// Handle form submission to update company data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input data
    $name = $_POST['name'];
    $type = $_POST['type'];
    $safety_plan = $_POST['safety_plan'];
    $insurance_status = $_POST['insurance_status'];
    
    // Prepare the update query
    $update_query = "UPDATE company SET name = ?, type = ?, safety_plan = ?, insurance_status = ? WHERE company_id = ?";
    
    if ($stmt = $conn->prepare($update_query)) {
        $stmt->bind_param("ssssi", $name, $type, $safety_plan, $insurance_status, $company_id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Company updated successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error updating company. Please try again later.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Company</title>
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
    <h2 class="mb-4">Edit Company</h2>
    
    <div class="dashboard-btn">
        <form action="admin_manage_company.php" method="POST">
            <input type="hidden" name="role" value="Administrator">
            <button type="submit" class="btn btn-primary">Back to Manage Companies</button>
        </form>
    </div>
    
    <!-- Company Edit Form -->
    <form method="POST" action="edit_company.php?id=<?php echo $company_id; ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $company['name']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="type">Type:</label>
            <input type="text" class="form-control" id="type" name="type" value="<?php echo $company['type']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="safety_plan">Safety Plan:</label>
            <input type="text" class="form-control" id="safety_plan" name="safety_plan" value="<?php echo $company['safety_plan']; ?>" required>
        </div>

        <div class="form-group">
            <label for="insurance_status">Insurance Status:</label>
            <input type="text" class="form-control" id="insurance_status" name="insurance_status" value="<?php echo $company['insurance_status']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Company</button>
        <a href="admin_manage_company.php" class="btn btn-secondary ml-3">Cancel</a>
    </form>
</div>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
