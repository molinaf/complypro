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
    <title>Dashboard Role Selection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 350px;
            text-align: center;
        }

        .logo {
            width: 100px;
            height: 100px;
            background: url('images/logo-white.png') no-repeat center center;
            background-size: contain;
            margin: 0 auto 20px;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        p {
            color: #666;
            margin-bottom: 20px;
        }

        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #004080;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #003366;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="logo"></div>
        <h1>Welcome to the Portal</h1>
        <p>Please select your role to proceed to the dashboard:</p>
        <form method="post" action="">
            <select id="role-select" name="role" required>
                <option value="" disabled selected>Select your role</option>
                <option value="Administrator">Administrator</option>
                <option value="Coordinator">Coordinator</option>
                <option value="User">User</option>
            </select>
            <button type="submit">Proceed</button>
        </form>
    </div>

</body>
</html>
