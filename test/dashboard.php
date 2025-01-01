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
    echo "<a href='$link' style='display:inline-block; margin:5px; padding:10px 15px; color:#fff; background-color:$color; text-decoration:none; border-radius:5px; min-width:230px; 
          width:auto; text-align:center;'>$label</a>";
}
 
// Render dashboard based on the role
function renderDashboard($role) {
    switch ($role) {
        case 'Administrator':
            echo "<h1>Administrator Dashboard</h1>";
            echo "<div class='card'>
                    <h2>System Overview</h2>
                    <p>Manage the overall system performance, users, and logs.</p>";
            renderButton("View System Stats", "system_stats.php", "navy");
            renderButton("Manage Users", "admin_manage_users.php", "blue");
            renderButton("Manage Company", "admin_manage_company.php", "cornflowerblue");
            renderButton("Audit Logs", "audit_logs.php", "darkcyan");
            echo "</div>";
            
            echo "<div class='card'>
                    <h2>Manage Authorisations</h2>
                    <p>Oversee authorisations and company-wide compliance.</p>";
            renderButton("Authorisation Settings", "admin_manage_auth_req.php", "coral");
            renderButton("Authorisation Workflow", "admin_manage_auth_links.php", "darksalmon");
            renderButton("View All Applications", "all_applications.php", "salmon");
            echo "</div>";
            break;

        case 'Coordinator':
            echo "<h1>Coordinator Dashboard</h1>";
            echo "<div class='card'>
                    <h2>Create and Manage Applications</h2>
                    <p>Manage user authorisations and create applications.</p>";
            renderButton("Create New Application", "create_application.php", "darkslategray");
            renderButton("Pending Applications", "pending_applications.php", "forestgreen");
            echo "</div>";

            echo "<div class='card'>
                    <h2>Training and Scheduling</h2>
                    <p>Organise and monitor training schedules.</p>";
            renderButton("Schedule Training", "schedule_training.php", "lightseagreen");
            renderButton("Training Calendar", "training_calendar.php", "mediumseagreen");
            echo "</div>";

            echo "<div class='card'>
                    <h2>Reports</h2>
                    <p>Generate compliance and user reports.</p>";
            renderButton("Generate Reports", "generate_reports.php", "seagreen");
            echo "</div>";
            break;

        case 'User':
            echo "<h1>User Dashboard</h1>";
            echo "<div class='card'>
                    <h2>My Applications</h2>
                    <p>Track and manage your authorisation applications.</p>";
            renderButton("View Applications", "my_applications.php", "saddlebrown");
            renderButton("Start/Complete Requirements", "complete_requirements.php", "sienna");
            echo "</div>";

            echo "<div class='card'>
                    <h2>My Training</h2>
                    <p>Book or check the status of your training sessions.</p>";
            renderButton("Book Training", "book_training.php", "peru");
            renderButton("View Training History", "training_history.php", "chocolate");
            echo "</div>";

            echo "<div class='card'>
                    <h2>Certificates</h2>
                    <p>Download certificates for your completed authorisations.</p>";
            renderButton("Download Certificates", "download_certificates.php", "rosybrown");
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
    <title>Corporate Learning Portal</title>
    <style>
        
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .card { border: 1px solid #ddd; border-radius: 5px; padding: 20px; margin: 10px 0; background: #f9f9f9; }
        h1 { color: #333; }
        h2 { color: #555; }
        a { text-decoration: none; font-weight: bold; }
        a:hover { opacity: 0.8; }

        header {
            background: #01274e;
            padding: 1rem 0;
            color: white;
            text-align: center;
        }

        .dashboard-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            width: 150px;
            height: 60px;
            background: url('images/logo.png') no-repeat center center;
            background-size: contain;
        }

        .nav-links {
            list-style: none;
            display: flex;
        }

        .nav-links li {
            margin-left: 1.5rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
        }

        .container {
            display: flex;
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            flex: 2;
            padding: 20px;
        }

        footer {
            background: #01274e; /* Footer background color */
            color: white;
            text-align: center;
            padding: 1rem;
            width: 100vw; /* Full width of the viewport */
            position: relative; /* Ensure no overlap */
            left: 0; /* Align with the left edge */
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <a href="home.php" class="logo"></a>
            <nav>
                <ul class="nav-links">
                    <li><a href="#help">Help & Support</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="#courses">Courses</a></li>
                    <li><a href="#mytraining">My Training</a></li>
                    <li><a href="#resources">Resources</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <!-- Main Content Area -->
        <div class="main-content">
            
    <?php
    if (isset($_SESSION['role'])) {
        renderDashboard($_SESSION['role']);
    } else {
        echo "<h1>Please log in to access the dashboard.</h1>";
    }
    ?>

    </div>
    </div>
    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 ComplyPro. All Rights Reserved.</p>
    </footer>
</body>
</html>