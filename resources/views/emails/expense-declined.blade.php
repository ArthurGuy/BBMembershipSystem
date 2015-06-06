<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>
<p>
    Hi {{ $user->given_name }},<br />
    Your expense submission has been declined.<br />
    If you require further information please email the trustees, <a href="trustees@buildbrighton.com">trustees@buildbrighton.com</a><br />
    <br />
    Category: {{ $expense->category }}<br />
    Description: {{ $expense->description }}<br />
    Amount: {{ number_format($expense->amount / 100, 2) }}<br />
    Date: {{ $expense->expense_date }}<br />
</p>
</body>
</html>
