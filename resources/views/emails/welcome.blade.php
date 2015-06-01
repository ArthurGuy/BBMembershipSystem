<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>
<h1>Welcome to Build Brighton</h1>

<p>
    Hi {{ $user['given_name'] }},<br />
    Thanks for signing up to be part of Build Brighton, Brighton's amazing maker space.
</p>
<h3>Next Steps</h3>
<p>
    The first thing to do is to arrange your induction.
    This will be a full overview of Build Brighton from where everything is and what to do if something goes
    wrong through to where the bins get emptied.<br />
    The process for arranging this is still being sorted out so for now please email us at
    <a href="mailto:info@buildbrighton.com">info@buildbrighton.com</a> and let us know if you're going to be coming
    to the space on Thursday (our open evening) and we will make sure someone will be around to show you the ropes.<br />
    <br />
    We would also like you to join the mailing list, this is where most of the communication between members occurs and is where
    things get decided and you find out about what's going on.<br />
    <a href="https://groups.google.com/forum/#!forum/brighton-hackspace-members">Mailing list</a> - please use the same
    email address as you did when signing up.<br />
    <br />
    We also have an active community on <a href="https://slack.com/">Slack</a>,
    for an invite please email the <a href="mailto:trustees@buildbrighton.com">trustees</a>
</p>
<p>
    We have a code of conduct which we expect all members to abide by and a set of rules which govern how things are run,
    please take a moment and read these.<br />
    <a href="https://bbms.buildbrighton.com/resources/policy/rules">Rules</a>
    and the <a href="https://bbms.buildbrighton.com/resources/policy/code-of-conduct">Code of Conduct</a>.<br />
    There are also a number of policies which manage and guide other areas of Build Brighton,
    these can be found on the <a href="https://bbms.buildbrighton.com/resources">resources</a> page.<br />
    If you ever have any concerns about any aspect of Build Brighton please email the <a href="mailto:trustees@buildbrighton.com">trustees</a>.
</p>
<p>
    Finally, if you could follow the link below to confirm your email address that would help us ensure we have a accurate details.<br />
    {!! URL::route('account.confirm-email', [$user['id'], $user['hash']]) !!}.<br/>
</p>
<p>
    Thank you,
    The Build Brighton Trustees
</p>

</body>
</html>
