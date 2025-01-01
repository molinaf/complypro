<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
	<link rel="stylesheet" href="{{ asset('homeres/css/logstyles.css') }}">
</head>
<body>
    <div class="login-container">
        <h1>Log In</h1>
        <p>Blurb...</p>
        <form>
            <div class="form-group">
                <label for="email">EMAIL</label>
                <input type="email" id="email" placeholder="Your email">
            </div>
            <div class="form-group">
                <label for="password">PASSWORD</label>
                <input type="password" id="password" placeholder="********">
            </div>
            <div class="form-footer">
                <a href="#">Forgot Password?</a>
            </div><br>
			 <div class="form-register">
                <center>Have an account? <a href="#">Register now!</a></center>
            </div>
            <br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>