<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>
<p>
    {{ $memberName }} has requested a withdrawal from their balance.<br />
    Please verify they are requesting a refund for expenses they have paid for rather than a  balance topup
    and don't forget to record the withdrawal in the BBMS.
</p>
<p>
    Amount: {{ $amount }}<br />
    Sort Code: {{ $sortCode }}<br />
    Account Number: {{ $accountNumber }}<br />
</p>

</body>
</html>
