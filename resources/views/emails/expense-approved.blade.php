<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>
<p>
    Hi {{ $user->given_name }},<br />
    Your expense submission has been approved and the amount added to your balance.<br />
    <br />
    Category: {{ $expense->category }}<br />
    Description: {{ $expense->description }}<br />
    Amount: {{ number_format($expense->amount / 100, 2) }}<br />
    Date: {{ $expense->expense_date }}<br />

    <br />
    <br />
    <br />
    <small>This is an automated email sent by the Build Brighton Member System</small>
</p>
</body>
</html>
