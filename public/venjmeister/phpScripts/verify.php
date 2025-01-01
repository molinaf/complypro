<?php

// Load configuration
$config = parse_ini_file('config.ini', true);

function decryptToken($token, $secretKey) {
    $data = base64_decode($token);
    $iv = substr($data, 0, 16);
    $encryptedData = substr($data, 16);
    $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $secretKey, 0, $iv);
    return json_decode($decryptedData, true);
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $userData = decryptToken($token, $config['security']['secret_key']);
    if ($userData) {
        $name = $userData['name'];
        $email = $userData['email'];
        $expire = $userData['expire'];
        // Create a new session
        session_start();
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        if (file_exists('tokens/' . bin2hex(substr($token,0,16)))) {
            // Token is valid, proceed with the action
            // Invalidate the token by deleting the file
            unlink('tokens/' . bin2hex(substr($token,0,16)));
            header("Location: " . $config['server']['url'] . "home.php");
        } else {
            // Token is invalid or already used
			session_unset(); // Clear session variables
			session_destroy(); // End the session
            header("Location: " . $config['server']['url'] . "phpScripts/usedToken.php");
        }
    } else {
        echo '<h1>Invalid token</h1>';
    }
}
?>
