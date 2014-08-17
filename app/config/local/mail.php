<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Mail Driver
	|--------------------------------------------------------------------------
	|
	| Laravel supports both SMTP and PHP's "mail" function as drivers for the
	| sending of e-mail. You may specify which one you're using throughout
	| your application here. By default, Laravel is setup for SMTP mail.
	|
	| Supported: "smtp", "mail", "sendmail", "mailgun", "mandrill", "log"
	|
	*/

	'driver' => 'smtp',

	/*
	|--------------------------------------------------------------------------
	| SMTP Host Address
	|--------------------------------------------------------------------------
	|
	| Here you may provide the host address of the SMTP server used by your
	| applications. A default option is provided that is compatible with
	| the Mailgun mail service which will provide reliable deliveries.
	|
	*/

	'host' => 'smtp.mailgun.org',

	/*
	|--------------------------------------------------------------------------
	| SMTP Host Port
	|--------------------------------------------------------------------------
	|
	| This is the SMTP port used by your application to deliver e-mails to
	| users of the application. Like the host we have set this value to
	| stay compatible with the Mailgun e-mail application by default.
	|
	*/

	'port' => 587,

	/*
	|--------------------------------------------------------------------------
	| Global "From" Address
	|--------------------------------------------------------------------------
	|
	| You may wish for all e-mails sent by your application to be sent from
	| the same address. Here, you may specify a name and address that is
	| used globally for all e-mails that are sent by your application.
	|
	*/

	'from' => array('address' => 'bbms@sandbox3012.mailgun.org', 'name' => 'Build Brighton Member System'),



	/*
	|--------------------------------------------------------------------------
	| Mail "Pretend"
	|--------------------------------------------------------------------------
	|
	| When this option is enabled, e-mail will not actually be sent over the
	| web and will instead be written to your application's logs files so
	| you may inspect the message. This is great for local development.
	|
	*/

	'pretend' => true,

);
