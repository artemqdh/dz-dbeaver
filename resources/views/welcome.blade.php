<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome to Our Site!</h1>

    @if(session('success'))
        <div style="color: green; padding: 10px; margin-bottom: 15px; border: 1px solid green; background-color: lightgreen;">
            {{ session('success') }}
        </div>
    @endif

    @if ($user)
        <h2>Hello, {{ $user->name }}!</h2>

        <h3>Your Profile Image:</h3>
        @if ($user->profileImage)
            <img src="{{ Storage::url($user->profileImage->path) }}" width="300">
        @else
            <p>You haven't uploaded a profile image yet.</p>
        @endif
    @else
        <p>Please log in or register to see personalized content.</p>
    @endif
    </body>
</html>