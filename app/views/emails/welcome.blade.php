<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>
<h1>Welcome to Build Brighton</h1>

<p>
    some intro text here
</p>
<p>
    <h3>Next Steps</h3>
    The first thing to do is to arrange your induction.
    This will be a full overview of Build Brighton from where everything is and what to do if something goes
    wrong through to where the bins get emptied.<br />
    The process for arranging this is still being sorted out so for now please reply to this email and let us know if
    your going to be coming to the space on Thursday and we will make sure someone will be around to show you the ropes.
</p>
<p>
    Finally, if you could follow the link below to confirm your email address that would help us ensure we have an accurate email address.<br />
    {{ URL::route('account.confirm-email', [$user->id, 'dd']) }}.<br/>
</p>
<p>
    Thank you,
    The Build Brighton Trustees
</p>

</body>
</html>
