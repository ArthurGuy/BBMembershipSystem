<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>
<p>
    Hi {{ $user['given_name'] }},<br />
    Your Build Brighton membership has been suspended because of a payment problem.<br />
    <br />
    Your latest subscription payment has failed to process and we need you to login and retry your payment or make a manual payment.
</p>
<p>
    While your membership is suspended you wont have access to Build Brighton and if you have one your key fob wont work.
</p>
<p>
    Please login as soon as you can and make your subscription payment.<br />
    <a href="{{ URL::route('home') }}">Build Brighton Member System</a><br/>
    If you have any questions please email the <a href="mailto:trustees@buildbrighton.com">trustees</a>
</p>
<p>
    If you are leaving then we would be really grateful if you could click the leaving button in your account or let us know.
</p>
<p>
    Thank you,<br />
    The Build Brighton Trustees
</p>

</body>
</html>
