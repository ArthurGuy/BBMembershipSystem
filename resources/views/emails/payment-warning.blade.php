<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>
<p>
    Hi {{ $user['given_name'] }},<br />
    We haven't been notified about your latest subscription payment or you have cancelled your direct debit or PayPal
    subscription.<br />
    Your account has been flagged with a payment warning, if you're in the process of setting up a new subscription
    this will be cleared as soon as that's done.<br />
    <br />
    What to do:<br />
    If you're changing payment method please disregard this email and complete the process if you haven't done it already.<br />
    If you have paid please email us asap with the details and we will see what went wrong.<br />
    If you have decided to leave it would be great if you could click the leaving button in your account or email us and let us know.
</p>
<p>
    Within the next 24 hours your status will be changed to leaving or left depending on your last subscription payment.
    This may mean you lose access to the space so please get in contact soon.
</p>
<p>
    <a href="{{ URL::route('home') }}">Build Brighton Member System</a><br/>
</p>
<p>
    Thank you,<br />
    The Build Brighton Trustees
</p>

</body>
</html>
