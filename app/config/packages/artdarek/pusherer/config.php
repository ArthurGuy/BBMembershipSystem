<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| Pusher Config
	|--------------------------------------------------------------------------
	|
	| Pusher is a simple hosted API for quickly, easily and securely adding
	| realtime bi-directional functionality via WebSockets to web and mobile 
	| apps, or any other Internet connected device.
	|
	*/

	/**
	 * App id
	 */
	'app_id' => $_SERVER['PUSHER_APP_ID'],

	/**
	 * App key
	 */
	'key' => $_SERVER['PUSHER_APP_KEY'],

	/**
	 * App Secret
	 */
	'secret' => $_SERVER['PUSHER_APP_SECRET']

);