<?php
// Database connection settings
$host = "localhost"; // Database host, e.g., localhost
$username = "oddzo549"; // Database username
$password = "hU~=2-slu9?!"; // Database password (leave empty for localhost if not set)
$dbname = "oddzo549_complypro"; // Name of your database

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Uncomment the following line if you want to set the character set
// $conn->set_charset("utf8");
?>
