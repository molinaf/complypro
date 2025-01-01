<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorisation Notification</title>
</head>
<body>
    <h1>Hello, {{ $user->name }}</h1>
    <p>You have been assigned the following authorisations:</p>
    <ul>
        @foreach($authorisations as $auth)
            <li>{{ $auth->name }}</li>
        @endforeach
    </ul>
    <p>Please complete the requisites for these authorisations as soon as possible.</p>
</body>
</html>
