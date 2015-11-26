<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>
<p>
    {{ $user->name }} has submitted a new expense with the following details.<br />
    <br />
    Category: {{ $expense->category }}<br />
    Description: {{ $expense->description }}<br />
    Amount: {{ number_format($expense->amount / 100, 2) }}<br />
    Date: {{ $expense->expense_date }}<br />
</p>
<p>
    This can be approved or declined from the expenses section of the BBMS, if further information is required
    please contact the member directly.
    <br />
    <br />
    <br />
    <small>This is an automated email sent by the Build Brighton Member System</small>
</p>
</body>
</html>
