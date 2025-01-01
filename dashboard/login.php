<?php
session_start();

// Function to set role and redirect
if (isset($_POST['role'])) {
    $_SESSION['role'] = $_POST['role'];
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 50px; }
        h1 { color: #333; }
        form { display: inline-block; margin-top: 20px; }
        button {
            display: block;
            margin: 10px auto;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h1>Welcome to the System</h1>
    <p>Please select your role to proceed to the dashboard:</p>
    <form method="post" action="">
        <button type="submit" name="role" value="Administrator">Administrator</button>
        <button type="submit" name="role" value="Coordinator">Coordinator</button>
        <button type="submit" name="role" value="User">User</button>
    </form>
</body>
</html>
