<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>
<p>
    Hi {{ $user->given_name }},<br />
    Your expense submission has been declined.<br />
    This can happen if you didn't include the required information or didn't receive approval for large purchases.<br />
    If you require further information please email the trustees, <a href="trustees@buildbrighton.com">trustees@buildbrighton.com</a><br />
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
