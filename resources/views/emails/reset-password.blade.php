<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <p>Hello {{ $first_name }},</p>
    <p>You've asked to reset to HRmap password. Please click the link below to enter the new one.</p>
    <a href="http://localhost:3000/reset-password/{{ $token }}">reset password</a>
</body>
</html>
