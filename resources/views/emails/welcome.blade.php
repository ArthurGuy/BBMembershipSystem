<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
</head>
<body>
<h1>Welcome to Build Brighton</h1>

<p>
    Hi {{ $user['given_name'] }},<br />
    Thanks for joining the Build Brighton maker space. 
</p>
<h3>Next Steps</h3>
<p>
    <b>1. Join Slack and Arrange an Induction</b><br /><br />
    The first thing is to join Slack - a collaborative network for group and individual messaging.<br /><br />
    Please use the New Members channel on Slack to arrange your induction. They are normally done every few weeks on a Thursday at 8pm (during our open evening) but can be done on other days if needed.<br /><br />
    For an invite to <a href="https://discord.gg">Discord</a> please install the client on desktop or mobile, and click <a href="https://discord.gg/tzgfE2Q5qh">this link</a><br /><br />
    Once you’re signed in, please update your nickname to your 'real name' by right clicking yourself in sidebar and selecting "edit server profile". You can join any other channels that take your interest such as 3D printing, electronics and photography.
</p>
<p>
    <b>2. Sign up to the Google groups/mailing list</b><br /><br />
    You can sign up to our 2 Google groups <a href="https://groups.google.com/forum/#!forum/brighton-hackspace-members">here</a> - please use the email address you used when joining Build Brighton. We have a public group that anyone can see and post to, plus a members only group.<br >
    The mailing lists are useful when your posting a longer form question.
</p>
<p>
    <b>3. Read the Code of Conduct and Rules</b><br />
    We have a <a href="https://bbms.buildbrighton.com/resources/policy/code-of-conduct">Code of Conduct</a> which we expect all members to follow and a set of <a href="https://bbms.buildbrighton.com/resources/policy/rules">Rules</a> which govern how things are run. Please make sure you read and accept these.<br />
    The main points are: Be safe, keep things tidy and make a contribution. We’re a members club and the place gets better when members make it better. So if you spot something that could be improved we encourage a pro-active attitude, in collaboration with other members. All roles are done by volunteers, including the trustees.<br />
    If you break something or find something is not working, please let the members know (e.g. via Slack).<br /><br />
    Our policies are accessible from the <a href="https://bbms.buildbrighton.com/resources">resources</a> page of the Build Brighton Members System (BBMS).
</p>
<p>
    <b>4. Storage</b><br /><br />
    There is limited storage space for members, but larger items can be kept in the space for a short time if agreed beforehand. To reserve your storage box go to the Members Storage page in BBMS. Each member can claim up to 19L of member storage, this can be one 19L box or a combination of 4L and 9L boxes. Each individual box requires a £5 payment.
</p>
<p>
    <b>5. Any questions and concerns</b><br /><br />
    If you have any questions please use Slack, the members google group or ask another member who may be able to help, or point you in the direction of someone who can. If you need to contact the trustees please email us at <a href="mailto:trustees@buildbrighton.com">trustees@buildbrighton.com</a>. 
</p>
<p>
    <b>6. Confirm your email address</b><br /><br />
    Finally, please click the link below to confirm your email address and ensure we have your accurate details.<br /><br />
    <a href="{!! URL::route('account.confirm-email', [$user['id'], $user['hash']]) !!}">{!! URL::route('account.confirm-email', [$user['id'], $user['hash']]) !!}</a>
</p>    
<p>
    Thank you and we hope you enjoy being a member of Build Brighton. <br /><br />
    Best wishes, <br />
    The Build Brighton Trustees
</p>
</body>
</html>
