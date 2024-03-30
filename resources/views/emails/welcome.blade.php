<!DOCTYPE html>
<html lang="en-GB">
<head>
  <meta charset="utf-8">
  <title></title>
</head>
<body>
  <h1>Welcome to Build Brighton</h1>
  <p>Hi {{ $user['given_name'] }},<br>
  <br>
  Thanks for joining the Build Brighton maker space</p>
  <h2>Essential!</h2>
  <h3>Confirm your email address</h3><br>
  Please click the link below to confirm your email address to ensure we have
  your accurate details.<br>
  <br>
  <a href=
  "{!!%20URL::route('account.confirm-email',%20[$user['id'],%20$user['hash']])%20!!}">
  {!! URL::route('account.confirm-email', [$user['id'], $user['hash']]) !!}</a>
  <h3>Your membership is not activated until this step is complete!</h3>
  <h3>Next Steps</h3>
  <p><b>1. Join Discord say hello and arrange an induction</b><br>
  <br>
  The first thing is to join Discord - a collaborative network for group and
  individual messaging. This has information on the current state of things and
  we use it to chat between members.<br>
  <br>
  Optionally install the <a href="https://discord.gg">Discord</a> app, then
  click <a href="https://discord.gg/tzgfE2Q5qh">this link</a> to be invited to
  our server.<br>
  <br>
  Once you are signed in, please update your nickname to your 'real name' by
  right clicking yourself in sidebar and selecting "edit server profile". This
  is so that the trustees can identify you as a paid-up member on Discord. You
  will then need to wait for a trustee to update your profile. (The default
  profile is restricted as anyone with a link can join our Discord server.)
  Until your profile is updated, you can only post in the #welcome channel. If
  the update does not happen, see (7) below.<br>
  <br>
  Once your profile is updated, we will let you know and you can join other
  channels of interest, and you can request equipment inductions in the
  #inductions channel.</p>
  <p><b>2. Read the Rules and Code of Conduct</b><br>
  We have <a href="https://bbms.buildbrighton.com/resources/policy/rules">some
  rules</a> which we expect all members to follow. These govern how things are
  run. Please make sure you read these.<br>
  It is a condition of membership that you adhere to the rules and call out
  members who are breaking them.<br>
  The main points are: Be safe, keep things tidy and contribute to maintaining
  a positive and happy environment for everyone.</p>
  <p>Please read and familiarise yourself with our <a href=
  "https://bbms.buildbrighton.com/resources/policy/code-of-conduct">code of
  conduct</a>. Behave well towards other members and visitors. Do not do things
  or post messages that will offend and do not harass anyone in our space.</p>
  <p>We are a members club and the place gets better when members make it
  better. So if you spot something that could be improved we encourage you to
  be proactive and collaborate with other members. All the administration of
  the space from the cleaning to maintaining the infrastructure to being a
  trustee is done by volunteers.<br>
  If you break something or find something is not working, please let the
  members know (e.g. via Discord).<br>
  <br>
  Our Rules and policies are accessible (once you have logged in - see 8 below)
  from the <a href="https://bbms.buildbrighton.com/resources">resources</a>
  page of the Management System.</p>
  <p><b>3. Storage</b><br>
  <br>
  You can, on payment of a £15 non-refundable fee, obtain one 19 litre storage
  box for your use. To do this, go to the <a href=
  "https://bbms.buildbrighton.com/storage_boxes">Members Storage page</a> in
  our management system (see 8 below).<br>
  There are also storage lockers with a monthly rental depending on size - for
  availability see the relevant channel on our Discord server.<br>
  There is limited storage space for members in our three rooms. Large items
  can be left in the space for a week or two (depending on the their size,
  larger things should be left for shorter times) if agreed beforehand. Items
  left in the space must have an do not hack sign with your name and a removal
  date.</p>
  <p><b>4. Any questions and concerns</b><br>
  <br>
  If you have any questions please use discord where a member who may be able
  to help, or point you in the direction of someone who can. If you need to
  contact the trustees please see 7 below.</p>
  <p><b>5.Visiting the Space</b><br></p>
  <p>The space is often unoccupied. If you plan to visit please use discord to
  engage with members and to see who will be in the space.<br>
  The activity channel on Discord shows when members enter the space but they
  can depart at any time.<br>
  Do not just turn up at the space and expect someone to let you in! Until you
  become a keyholder, arrange to meet someone there before setting out.</p>
  <p><b>6. Refunds and cancellation</b><br>
  <br>
  We will not refund membership fees even if you are not using the space. To
  cancel your subscription, go to your member page, and then scroll down to the
  bottom after "Submit a new expense", then press the link 'Cancel your direct
  debit and leave'. Check the payment has been cancelled. If you subscribed
  using Paypal then cancel via Paypal.</p>
  <p><b>7. Contacting the trustees</b><br>
  <br>
  If you need to contact the trustees please email us at <a href=
  "mailto:trustees@buildbrighton.com">trustees@buildbrighton.com</a>.<br>
  If you do need to contact us, bear in mind that we are not magicians, we may
  have jobs and families which are our main priority.<br>
  We cannot necessarily fix things or rectify problems easily and we may not do
  things exactly the same way given similar circumstances, though we try to be
  consistent and fair as far as possible.</p>
  <p><b>8. Our Management System</b><br>
  <br>
  As a member, please explore our <a href=
  "https://bbms.buildbrighton.com/">management system</a>. Log in using the
  email address that you entered on the joining form. This allows you to do
  things such as check the status of our equipment, submit expenses and manage
  payments for use of tools or storage.</p>
  <p><b>9. Finally, Thank you and we hope you enjoy being a member of Build
  Brighton.</b><br>
  <br></p>
  <p>Best wishes,<br>
  <br>
  The Build Brighton Trustees</p>
  <hr>
  <p>updated March 2024</p>
</body>
</html>
