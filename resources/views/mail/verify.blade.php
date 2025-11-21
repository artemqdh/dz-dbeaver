<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VerifyMail</title>
</head>
    <body>
        <h2>Hello, {{ $user->name }}!</h2>

        <p>Thank you for registering.</p>
        <p>Please verify your email by clicking the link below:</p>
        <p>
            <a href="{{ URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]) }}">
                Verify Email
            </a>
        </p>
        <p>This link expires in 60 minutes.</p>
    </body>
</html>