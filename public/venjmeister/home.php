<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Power Services - Work Practices and Training - Authorisation Course Listing</title>
<link rel="stylesheet" href="css\style.css">
<script src="js/scormAPI.js"></script>
<div style="text-align: center;">
<a href="home.php"><img src="images\logo.png" alt="Logo Image" style="width: 194px; display: inline-block;"></a>
</div>
</head>
<body>
<?php
// Load configuration
$config = parse_ini_file('phpScripts/config.ini', true); 
	if (isset($_SESSION['name'])) {
		echo "<center><h4>Name: " . $_SESSION['name'] . " | email: " . $_SESSION['email'] . "<br>You are currently enrolled</h1></center>";
	} else {
		echo "<center><h4>No user is logged in.</h4></center>";
	}
  echo '<iframe src= ' . $config['server']['courseUrl'] .' width="100%" height="750" title="Embedded Page">';
?>
    Your browser does not support iframes.
  
    </iframe>

</body>
</html>
