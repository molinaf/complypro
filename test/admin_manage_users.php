<?php
// Assuming you have a database connection setup (e.g., $conn)
require_once('config.php');

session_start();
print_r($_SESSION);

// Determine the sort order
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'first_name';

// Toggle the sort order
$new_order = ($order === 'ASC') ? 'DESC' : 'ASC';

// Fetch users from the database with sorting
$sql = "SELECT * FROM users ORDER BY $sort $order";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        
        .container {
            margin: 20px auto;
            width: 60%;
            border: 2px solid;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.5);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            padding: 8px 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .add-btn, .dashboard-btn {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Admin - Manage Users</h1>

    <div class="dashboard-btn">
        <a href="dashboard.php?"<?= $_SESSION["role"] ?> class="btn">Back to Dashboard</a>
    </div>

    <div class="add-btn">
        <a href="add_user.php" class="btn">Add New User</a>
    </div>

    <table>
        <thead>
            <tr>
                <th onclick="sortTable('user_id')">User ID</th>
                <th onclick="sortTable('first_name')">Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['user_id'] . "</td>
                            <td>" . $row['first_name'] . " " . $row['last_name'] . "</td>
                            <td>" . $row['email'] . "</td>
                            <td>" . $row['role'] . "</td>
                            <td>" . ($row['status'] == 1 ? 'Active' : 'Inactive') . "</td>
                            <td>
                                <a href='edit_user.php?id=" . $row['user_id'] . "' class='btn'>Edit</a>
                                <a href='delete_user.php?id=" . $row['user_id'] . "' class='btn' style='background-color: #dc3545;'>Delete</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function sortTable(column) {
        const urlParams = new URLSearchParams(window.location.search);
        let order = urlParams.get('order') === 'ASC' ? 'DESC' : 'ASC';
        window.location.href = `?sort=${column}&order=${order}`;
    }
</script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
