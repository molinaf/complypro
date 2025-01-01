<?php
// Load configuration
$config = parse_ini_file('phpScripts/config.ini', true);
$url = $config['server']['url'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Power Services - Work Practices and Training - Authorisation Course Listing</title>
<link rel="stylesheet" href="css\style.css">
<div style="text-align: center;">
<a href="home.php"><img src="images\logo.png" alt="Logo Image" style="width: 194px; display: inline-block;"></a>
</div><br>
</head>
<body>

  <div class="login-container">
    <h2>Power Services Authorisation Courses</h2>
    <form action="<?php echo $url ?>phpScripts/emailToken.php" method="post">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>

      <button type="submit">Login</button>
    </form>
  </div>
<br>
    <p align="center" class="info-text" style="margin-top: 20px;">
An email with a one-time-use link will be sent to you <br>
for access to the PWC Power Services Authorisation Courses.
<br><br>
Please coordinate with your supervisor or contract manager <br>
to determine which units are required for your authorisation.
    </p>
</body>
</html>
