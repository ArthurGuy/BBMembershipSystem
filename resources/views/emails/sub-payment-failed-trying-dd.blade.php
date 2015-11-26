<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>
<p>
    Hi {{ $user->given_name }},<br />
    Your latest subscription payment has failed<br />
    <br />
    We tried to take money from your balance but were unable to do so.<br />
    We will attempt to take payment from your backup source, you will be notified if this fails.
    <br />
    <br />
    <br />
    <small>This is an automated email sent by the Build Brighton Member System</small>
</p>
</body>
</html>
