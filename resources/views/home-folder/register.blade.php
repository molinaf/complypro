<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
	<link rel="stylesheet" href="{{ asset('homeres/css/logstyles.css') }}">
</head>
<body>
    <div class="login-container">
        <h1>Registration</h1>
        <p>Register now to get more features and access from our platform.</p>
        <form>
            <div class="form-group">
                <label for="name">NAME</label>
                <input type="text" id="name" placeholder="Your name">
            </div>
            <div class="form-group">
                <label for="email">EMAIL</label>
                <input type="email" id="email" placeholder="Your email">
            </div>
            <div class="form-group">
                <label for="password">PASSWORD</label>
                <input type="password" id="password" placeholder="********">
            </div>
            <div class="form-group">
                <label for="confirm-password">CONFIRM PASSWORD</label>
                <input type="password" id="confirm-password" placeholder="********">
            </div>
            <br>
            <button type="submit">Create account</button>
        </form>
    </div>
</body>
</html>