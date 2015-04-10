<?php

# Home

use GifCreator\GifCreator;

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index'));


# Authentication

Route::get('login', ['as' => 'login', 'uses' => 'SessionController@create']);
Route::get('logout', ['as' => 'logout', 'uses' => 'SessionController@destroy']);
Route::resource('session', 'SessionController', ['only' => ['create', 'store', 'destroy']]);
Route::get('password/forgotten', ['as' => 'password-reminder.create', 'uses' => 'ReminderController@create']);
Route::post('password/forgotten', ['as' => 'password-reminder.store', 'uses' => 'ReminderController@store']);
Route::get('password/reset/{id}', ['uses' => 'ReminderController@getReset']);
Route::post('password/reset', ['as'=>'password.reset.complete', 'uses' => 'ReminderController@postReset']);


# Account

Route::get('account/trusted_missing_photos', ['uses'=>'AccountController@trustedMissingPhotos', 'as'=>'account.trusted_missing_photos', 'before'=>'role:admin']);
Route::resource('account', 'AccountController');
//Editing the profile
Route::get('account/{account}/profile/edit', ['uses'=>'ProfileController@edit', 'as'=>'account.profile.edit', 'before'=>'role:member']);
Route::put('account/{account}/profile', ['uses'=>'ProfileController@update', 'as'=>'account.profile.update', 'before'=>'role:member']);
//Short register url
Route::get('register', ['as' => 'register', 'uses' => 'AccountController@create']);
//Special account editing routes
Route::put('account/{account}/alter-subscription', ['as'=>'account.alter-subscription', 'uses' => 'AccountController@alterSubscription', 'before'=>'role:admin']);
Route::put('account/{account}/admin-update', ['as'=>'account.admin-update', 'uses' => 'AccountController@adminUpdate', 'before'=>'role:admin']);
Route::put('account/{account}/rejoin', ['as'=>'account.rejoin', 'uses' => 'AccountController@rejoin', 'before'=>'role:member']);
Route::get('account/confirm-email/{id}/{hash}', ['as'=>'account.confirm-email', 'uses'=>'AccountController@confirmEmail']);
//BB Credit
Route::get('account/{account}/balance', ['uses'=>'BBCreditController@index', 'as'=>'account.balance.index', 'before'=>'role:member']);



# Members

Route::resource('members', 'MembersController', ['only'=>['index','show']]);


# Subscription/Payments

Route::get('account/{account}/subscription/store', ['as' => 'account.subscription.store', 'uses' => 'SubscriptionController@store']);
Route::resource('account.subscription', 'SubscriptionController', ['except' => ['store', 'update', 'edit', 'show', 'index']]);
Route::post('gocardless/webhook', ['uses' => 'GoCardlessWebhookController@receive']);

Route::resource('account.payment', 'PaymentController', ['only' => ['store']]);
Route::group(array('before' => 'role:admin'), function() {
    Route::resource('payments', 'PaymentController', ['only' => ['index', 'destroy', 'update']]);
    Route::get('payments/overview', ['uses'=>'PaymentOverviewController@index', 'as'=>'payments.overview']);
    Route::get('payments/sub-charges', ['as' => 'payments.sub-charges', 'uses' => 'SubscriptionController@listCharges']);
});
Route::post('account/{account}/payment/create', ['as'=>'account.payment.create', 'uses' => 'PaymentController@create']);
Route::get('account/{account}/payment/confirm-payment', ['as' => 'account.payment.confirm-payment', 'uses' => 'PaymentController@confirmPayment']);
Route::post('account/{account}/update-sub-payment', ['as'=>'account.update-sub-payment', 'uses'=>'AccountController@updateSubscriptionAmount']);

# Payment provider specific urls
Route::post('account/{account}/payment/stripe/store', ['as'=>'account.payment.stripe.store', 'uses' => 'StripePaymentController@store']);
Route::post('account/{account}/payment/gocardless/create', ['as'=>'account.payment.gocardless.create', 'uses' => 'GoCardlessPaymentController@create']);
Route::get('account/{account}/payment/gocardless/store', ['as'=>'account.payment.gocardless.store', 'uses' => 'GoCardlessPaymentController@store']);

//balance payments
Route::post('account/{account}/payment/balance/create', ['as'=>'account.payment.balance.create', 'uses' => 'BalancePaymentController@store']);
//Cash
Route::post('account/{account}/payment/cash/create', ['as'=>'account.payment.cash.create', 'uses' => 'CashPaymentController@store']);


# Inductions
Route::group(array('before' => 'role:admin'), function() {
    Route::post('equipment_training/update', ['uses'=>'InductionController@update', 'as'=>'equipment_training.update']);
    Route::resource('account.induction', 'InductionController', ['only' => ['update', 'destroy']]);
});


# Equipment
Route::get('equipment', ['uses'=>'EquipmentController@index', 'before'=>'role:member', 'as'=>'equipment.index']);
Route::get('equipment/{equipment}', ['uses'=>'EquipmentController@show', 'before'=>'role:member', 'as'=>'equipment.show']);


# Equipment Log
Route::post('equipment/log/{logId}', ['uses'=>'EquipmentLogController@update', 'before'=>'role:member', 'as'=>'equipment_log.update']);


# Statements
Route::resource('statement-import', 'StatementImportController', ['except' => ['index', 'show', 'edit', 'update', 'destroy'], 'before'=>'role:admin']);


# KeyFobs
Route::group(array('before' => 'role:admin'), function() {
    Route::resource('keyfob', 'KeyFobController', ['only' => ['index', 'store', 'update', 'destroy']]);
});

# PayPal IPN
Route::post('paypal-ipn', 'PaypalIPNController@receiveNotification');


# Access Control

//Main Door
Route::post('access-control/main-door', ['uses' => 'AccessControlController@mainDoor']);

//Status endpoint - testing - not in production
Route::post('access-control/status', ['uses' => 'AccessControlController@status']);
Route::get('access-control/status', ['uses' => 'AccessControlController@status']);

//Device control
Route::post('access-control/device', ['uses' => 'DeviceAccessControlController@device']);

//Spark Core Testing
Route::post('access-control/spark-status', ['uses' => 'AccessControlController@sparkStatus']);

//New ACS System
Route::post('acs', ['uses' => 'ACSController@update']);
Route::get('acs', ['uses' => 'ACSController@get']);



# Activity Page
Route::get('activity', ['uses' => 'ActivityController@index', 'as'=>'activity.index', 'before'=>'role:member']);
Route::get('activity/realtime', ['uses' => 'ActivityController@realtime', 'as'=>'activity.realtime', 'before'=>'role:member']);


# Storage Boxes
Route::get('storage_boxes', ['uses'=>'StorageBoxController@index', 'as'=>'storage_boxes.index', 'before'=>'role:member']);
Route::put('storage_boxes/{id}', ['uses'=>'StorageBoxController@update', 'as'=>'storage_boxes.update', 'before'=>'role:member']);


# Stats
Route::get('stats', ['uses'=>'StatsController@index', 'before'=>'role:member', 'as'=>'stats.index']);


#Notification Emails
Route::get('notification_email/create', ['as' => 'notificationemail.create', 'uses' => 'NotificationEmailController@create', 'before'=>'role:member']);
Route::post('notification_email', ['as' => 'notificationemail.store', 'uses' => 'NotificationEmailController@store', 'before'=>'role:member']);


#Proposals
Route::get('proposals', ['uses'=>'ProposalController@index', 'as'=>'proposals.index', 'before'=>'role:member']);
Route::get('proposals/create', ['uses'=>'ProposalController@create', 'as'=>'proposals.create', 'before'=>'role:admin']);
Route::post('proposals', ['uses'=>'ProposalController@store', 'as'=>'proposals.store', 'before'=>'role:admin']);
Route::get('proposals/{id}', ['uses'=>'ProposalController@show', 'as'=>'proposals.show', 'before'=>'role:member']);
Route::post('proposals/{id}', ['uses'=>'ProposalController@vote', 'as'=>'proposals.vote', 'before'=>'role:member']);
Route::get('proposals/{id}/edit', ['uses'=>'ProposalController@edit', 'as'=>'proposals.edit', 'before'=>'role:admin']);
Route::post('proposals/{id}/update', ['uses'=>'ProposalController@update', 'as'=>'proposals.update', 'before'=>'role:admin']);


# Feedback
Route::post('feedback', ['uses'=>'FeedbackController@store', 'as'=>'feedback.store', 'before'=>'roll:member']);


# Roles
Route::group(array('before' => 'role:admin'), function() {
    Route::resource('roles', 'RolesController', []);
    Route::resource('roles.users', 'RoleUsersController', ['only' => ['destroy', 'store']]);
});

# Resources
Route::get('resources', ['uses'=>'ResourcesController@index', 'before'=>'role:member', 'as'=>'resources.index']);
Route::get('resources/policy/{title}', ['uses'=>'ResourcesController@viewPolicy', 'before'=>'', 'as'=>'resources.policy.view']);


Route::any('camera/event/store', function() {

    $s3 = AWS::get('s3');
    $s3Bucket = 'buildbrighton-bbms';

    if (Request::hasFile('image')) {
        $file = Request::file('image');
        $event = Request::get('textevent');
        $time = Request::get('time');

        try {
            $newFilename = \App::environment() . '/camera-photos/' . $event . '/' . $time . '.jpg';
            $s3->putObject(array(
                'Bucket'        => $s3Bucket,
                'Key'           => $newFilename,
                'Body'          => file_get_contents($file),
                'ACL'           => 'public-read',
                'ContentType'   => 'image/jpg',
                'ServerSideEncryption' => 'AES256',
            ));
        } catch(\Exception $e) {
            \Log::exception($e);
        }
        //Log::debug('Image saved :https://s3-eu-west-1.amazonaws.com/buildbrighton-bbms/'.$newFilename);
    }
    if (Request::get('eventend') == 'true') {

        $event = Request::get('textevent');

        $iterator = $s3->getIterator(
            'ListObjects',
            array(
                'Bucket' => $s3Bucket,
                'Prefix' => \App::environment().'/camera-photos/'.$event,
                //'Prefix' => 'production/camera-photos/20150410222028',
            )
        );

        $images         = [];
        $imageDurations = [];
        foreach ($iterator as $object) {
            $images[]         = 'https://s3-eu-west-1.amazonaws.com/buildbrighton-bbms/' . $object['Key'];
            $imageDurations[] = 50;
        }

        $gc = new GifCreator();
        $gc->create($images, $imageDurations, 0);
        $gifBinary = $gc->getGif();

        $newFilename = \App::environment() . '/camera-photos/' . $event . '.gif';
        $s3->putObject(
            array(
                'Bucket'               => $s3Bucket,
                'Key'                  => $newFilename,
                'Body'                 => $gifBinary,
                'ACL'                  => 'public-read',
                'ContentType'          => 'image/gif',
                'ServerSideEncryption' => 'AES256',
            )
        );
        Log::debug('Event Gif generated :https://s3-eu-west-1.amazonaws.com/buildbrighton-bbms/'.$newFilename);



    }


    //Log::debug('Camera Data: '.json_encode(Request::all()));
});