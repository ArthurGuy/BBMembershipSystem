<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>
<p>
    Hi,<br />
    We have received a PayPal payment for &pound;{{ $payment->amount }} from your account {{ $email }},
    this is an unrecognised address so we have recorded it as a donation.<br />
    <br />
    If this was intentional, thank you for your support and contributions to keeping Build Brighton running.<br />
    <br />
    If this wasn't was you were expecting its most likely because you didn't enter your primary PayPal email address in to your account.
    Please update your account with the email address above and contact the trustees at
    <a href="mailto:trustees@buildbrighton.com">trustees@buildbrighton.com</a> quoting the payment id {{ $payment->id }}.

    Thank you,
    The Build Brighton Trustees
</p>
</body>
</html>
