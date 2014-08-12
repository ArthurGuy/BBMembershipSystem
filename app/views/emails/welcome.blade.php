<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>
<h1>Welcome to Build Brighton</h1>

<p>
    Thanks for signing up to be part of Build Brighton.
</p>
<p>
    <h3>Next Steps</h3>
    The first thing to do is to arrange your induction.
    This will be a full overview of Build Brighton from where everything is and what to do if something goes
    wrong through to where the bins get emptied.<br />
    The process for arranging this is still being sorted out so for now please email us at
    <a href="mailto:info@buildbrighton.com">info@buildbrighton.com</a> and let us know if your going to be coming
    to the space on Thursday and we will make sure someone will be around to show you the ropes.<br />
    <br />
    You should also join the mailing list, this is where most of the communication between members occurs and is where
    things get decided and you will find out about whats going on.<br />
    <a href="https://groups.google.com/forum/#!forum/brighton-hackspace-members">Mailing list</a> - please use the same
    email address as you did signing up.
</p>
<p>
    Finally, if you could follow the link below to confirm your email address that would help us ensure we have a accurate details.<br />
    {{ URL::route('account.confirm-email', [$user->id, $user->hash]) }}.<br/>
</p>
<p>
    Thank you,
    The Build Brighton Trustees
</p>

</body>
</html>
