<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>
<p>
    Hi {{ $user['given_name'] }},<br />
    {{ $messageBody }}
</p>
<p>
    Thank you,<br />
    The Build Brighton Trustees<br />
    <br />
    <a href="{{ URL::route('home') }}">Build Brighton Member System</a>
</p>

</body>
</html>
