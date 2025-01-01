<?php
session_start(); 
print_r($_SESSION);
// Check if the form is submitted and 'role' exists in POST data 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) { 
	$_SESSION['role'] = $_POST['role']; 
	header("Location: dashboard.php"); exit; 
}

// Function to render buttons
function renderButton($label, $link, $color = "blue") {
    echo "<a href='$link' style='display:inline-block; margin:5px; padding:10px 15px; color:#fff; background-color:$color; text-decoration:none; border-radius:5px;'>$label</a>";
}

// Render dashboard based on the role
function renderDashboard($role) {
    switch ($role) {
        case 'Administrator':
            echo "<h1>Administrator Dashboard</h1>";
            echo "<div class='card'>
                    <h2>System Overview</h2>
                    <p>Manage the overall system performance, users, and logs.</p>";
            renderButton("View System Stats", "system_stats.php", "green");
            renderButton("Manage Users", "admin_manage_users.php", "blue");
            renderButton("Manage Company", "admin_manage_company.php", "blue");
            renderButton("Audit Logs", "audit_logs.php", "gray");
            echo "</div>";
            
            echo "<div class='card'>
                    <h2>Manage Authorisations</h2>
                    <p>Oversee authorisations and company-wide compliance.</p>";
            renderButton("Authorisation Settings", "admin_manage_auth_req.php", "blue");
            renderButton("Authorisation Workflow", "admin_manage_auth_links.php", "blue");
            renderButton("View All Applications", "all_applications.php", "purple");
            echo "</div>";
            break;

        case 'Coordinator':
            echo "<h1>Coordinator Dashboard</h1>";
            echo "<div class='card'>
                    <h2>Create and Manage Applications</h2>
                    <p>Manage user authorisations and create applications.</p>";
            renderButton("Create New Application", "create_application.php", "blue");
            renderButton("Pending Applications", "pending_applications.php", "orange");
            echo "</div>";

            echo "<div class='card'>
                    <h2>Training and Scheduling</h2>
                    <p>Organise and monitor training schedules.</p>";
            renderButton("Schedule Training", "schedule_training.php", "green");
            renderButton("Training Calendar", "training_calendar.php", "purple");
            echo "</div>";

            echo "<div class='card'>
                    <h2>Reports</h2>
                    <p>Generate compliance and user reports.</p>";
            renderButton("Generate Reports", "generate_reports.php", "gray");
            echo "</div>";
            break;

        case 'User':
            echo "<h1>User Dashboard</h1>";
            echo "<div class='card'>
                    <h2>My Applications</h2>
                    <p>Track and manage your authorisation applications.</p>";
            renderButton("View Applications", "my_applications.php", "blue");
            renderButton("Start/Complete Requirements", "complete_requirements.php", "green");
            echo "</div>";

            echo "<div class='card'>
                    <h2>My Training</h2>
                    <p>Book or check the status of your training sessions.</p>";
            renderButton("Book Training", "book_training.php", "purple");
            renderButton("View Training History", "training_history.php", "orange");
            echo "</div>";

            echo "<div class='card'>
                    <h2>Certificates</h2>
                    <p>Download certificates for your completed authorisations.</p>";
            renderButton("Download Certificates", "download_certificates.php", "gray");
            echo "</div>";
            break;

        default:
            echo "<h1>Welcome</h1>";
            echo "<p>Your role does not have access to any dashboard.</p>";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .card { border: 1px solid #ddd; border-radius: 5px; padding: 20px; margin: 10px 0; background: #f9f9f9; }
        h1 { color: #333; }
        h2 { color: #555; }
        a { text-decoration: none; font-weight: bold; }
        a:hover { opacity: 0.8; }
    </style>
</head>
<body>
    <?php
    if (isset($_SESSION['role'])) {
        renderDashboard($_SESSION['role']);
    } else {
        echo "<h1>Please log in to access the dashboard.</h1>";
    }
    ?>
</body>
</html>
