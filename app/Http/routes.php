<?php

##########################
# Home
##########################

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index'));


##########################
# Authentication
##########################

Route::get('login', ['as' => 'login', 'uses' => 'SessionController@create']);
Route::get('logout', ['as' => 'logout', 'uses' => 'SessionController@destroy']);
Route::resource('session', 'SessionController', ['only' => ['create', 'store', 'destroy']]);
Route::post('session/pusher', ['uses' => 'SessionController@pusherAuth', 'middleware' => 'role:member', 'as' => 'session.pusher']);
Route::get('password/forgotten', ['as' => 'password-reminder.create', 'uses' => 'ReminderController@create']);
Route::post('password/forgotten', ['as' => 'password-reminder.store', 'uses' => 'ReminderController@store']);
Route::get('password/reset/{id}', ['uses' => 'ReminderController@getReset']);
Route::post('password/reset', ['as'=>'password.reset.complete', 'uses' => 'ReminderController@postReset']);



##########################
# Account
##########################

Route::get('account/trusted_missing_photos', ['uses'=>'AccountController@trustedMissingPhotos', 'as'=>'account.trusted_missing_photos', 'middleware'=>'role:admin']);
Route::resource('account', 'AccountController');

//Editing the profile
Route::get('account/{account}/profile/edit', ['uses'=>'ProfileController@edit', 'as'=>'account.profile.edit', 'middleware'=>'role:member']);
Route::put('account/{account}/profile', ['uses'=>'ProfileController@update', 'as'=>'account.profile.update', 'middleware'=>'role:member']);

//Short register url
Route::get('register', ['as' => 'register', 'uses' => 'AccountController@create']);

//Special account editing routes
Route::put('account/{account}/alter-subscription', ['as'=>'account.alter-subscription', 'uses' => 'AccountController@alterSubscription', 'middleware'=>'role:admin']);
Route::put('account/{account}/admin-update', ['as'=>'account.admin-update', 'uses' => 'AccountController@adminUpdate', 'middleware'=>'role:admin']);
Route::put('account/{account}/rejoin', ['as'=>'account.rejoin', 'uses' => 'AccountController@rejoin', 'middleware'=>'role:member']);
Route::get('account/confirm-email/{id}/{hash}', ['as'=>'account.confirm-email', 'uses'=>'AccountController@confirmEmail']);

//Balance
Route::get('account/{account}/balance', ['uses'=>'BalanceController@index', 'as'=>'account.balance.index', 'middleware'=>'role:member']);
Route::post('account/{account}/balance/withdrawal', ['uses'=>'BalanceController@withdrawal', 'as'=>'account.balance.withdrawal', 'middleware'=>'role:member']);
//Route::post('account/{account}/balance/transfer', ['uses' => 'BalanceController@recordTransfer', 'as'=>'account.balance.transfer.create']);

//Inductions
Route::get('account/{account}/induction', ['uses'=>'MemberInductionController@show', 'as'=>'account.induction.show', 'middleware'=>'role:member']);
Route::put('account/{account}/induction', ['uses'=>'MemberInductionController@update', 'as'=>'account.induction.update', 'middleware'=>'role:member']);
Route::get('member_inductions', ['uses'=>'MemberInductionController@index', 'as'=>'account.induction.index', 'middleware'=>'role:comms']);
Route::put('member_inductions/{account}', ['uses'=>'MemberInductionController@approve', 'as'=>'account.induction.approve', 'middleware'=>'role:comms']);


##########################
# Public Member List
##########################

Route::resource('members', 'MembersController', ['only'=>['index', 'show']]);



##########################
# Subscriptions / Payments
##########################

Route::get('account/{account}/subscription/store', ['as' => 'account.subscription.store', 'uses' => 'SubscriptionController@store']);
Route::resource('account.subscription', 'SubscriptionController', ['except' => ['store', 'update', 'edit', 'show', 'index']]);
Route::post('gocardless/webhook', ['uses' => 'GoCardlessWebhookController@receive']);

Route::post('account/{account}/payment', ['uses' => 'PaymentController@store', 'as' => 'account.payment.store', 'middleware' => 'role:admin']);

Route::group(array('middleware' => 'role:finance'), function() {
    Route::resource('payments', 'PaymentController', ['only' => ['index', 'destroy', 'update']]);
    Route::get('payments/overview', ['uses'=>'PaymentOverviewController@index', 'as'=>'payments.overview']);
    Route::get('payments/sub-charges', ['as' => 'payments.sub-charges', 'uses' => 'SubscriptionController@listCharges']);
});

Route::post('account/{account}/payment/create', ['as'=>'account.payment.create', 'uses' => 'PaymentController@create']);
Route::get('account/{account}/payment/confirm-payment', ['as' => 'account.payment.confirm-payment', 'uses' => 'PaymentController@confirmPayment']);
Route::post('account/{account}/update-sub-payment', ['as'=>'account.update-sub-payment', 'uses'=>'AccountController@updateSubscriptionAmount']);
Route::post('account/{account}/update-sub-method', ['as'=>'account.update-sub-method', 'uses'=>'SubscriptionController@updatePaymentMethod']);

# Payment provider specific urls
Route::post('account/{account}/payment/stripe', ['as'=>'account.payment.stripe.store', 'uses' => 'StripePaymentController@store']);
Route::post('account/{account}/payment/gocardless', ['as'=>'account.payment.gocardless.create', 'uses' => 'GoCardlessPaymentController@create']);
Route::post('account/{account}/payment/balance', ['as'=>'account.payment.balance.create', 'uses' => 'BalancePaymentController@store']);
//Gocardless return url
Route::get('account/{account}/payment/gocardless/manual-return', ['as'=>'account.payment.gocardless.manual-return', 'uses' => 'GoCardlessPaymentController@handleManualReturn']);


//Cash
Route::group(array('middleware' => 'role:admin'), function() {
    Route::post('account/{account}/payment/cash/create', ['as'=>'account.payment.cash.create', 'uses' => 'CashPaymentController@store']);
    Route::delete('account/{account}/payment/cash', ['as'=>'account.payment.cash.destroy', 'uses' => 'CashPaymentController@destroy']);
});

# Statements
Route::group(array('middleware' => 'role:finance'), function() {
    Route::resource('statement-import', 'StatementImportController', ['except' => ['index', 'show', 'edit', 'update', 'destroy']]);
});

//DD Migration to variable payments
Route::post('account/payment/migrate-direct-debit', ['as'=>'account.payment.gocardless-migrate', 'uses' => 'PaymentController@migrateDD', 'middleware'=>'role:member']);



##########################
# Inductions
##########################

Route::group(array('middleware' => 'role:admin'), function() {
    Route::post('equipment_training/update', ['uses'=>'InductionController@update', 'as'=>'equipment_training.update']);
    Route::resource('account.induction', 'InductionController', ['only' => ['update', 'destroy']]);
});



##########################
# Equipment
##########################

Route::group(array('middleware' => 'role:member'), function() {
    Route::resource('equipment', 'EquipmentController');
    Route::post('equipment/{id}/photo', ['uses'=>'EquipmentController@addPhoto', 'as'=>'equipment.photo.store']);
    Route::delete('equipment/{id}/photo/{key}', ['uses'=>'EquipmentController@destroyPhoto', 'as'=>'equipment.photo.destroy']);
});

# Equipment Log
Route::post('equipment/log/{logId}', ['uses'=>'EquipmentLogController@update', 'middleware'=>'role:member', 'as'=>'equipment_log.update']);



##########################
# Notifications
##########################

Route::resource('notifications', 'NotificationController', ['only' => ['index', 'update'], 'middleware' => 'role:member']);



##########################
# Key fobs
##########################

Route::group(array('middleware' => 'role:admin'), function() {
    Route::resource('keyfob', 'KeyFobController', ['only' => ['index', 'store', 'update', 'destroy']]);
});

# PayPal IPN
Route::post('paypal-ipn', 'PaypalIPNController@receiveNotification');



##########################
# Access Control & Devices
##########################

//Main Door
Route::post('access-control/main-door', ['uses' => 'AccessControlController@mainDoor']);

//Status endpoint - testing - not in production
Route::post('access-control/status', ['uses' => 'AccessControlController@status']);
Route::get('access-control/status', ['uses' => 'AccessControlController@status']);

//Device control
Route::post('access-control/device', ['uses' => 'DeviceAccessControlController@device']);

//New ACS System
Route::post('acs', ['uses' => 'ACSController@store']);
//Route::get('acs', ['uses' => 'ACSController@get']);

//spark core - printer charges
Route::post('acs/spark', ['uses' => 'ACSSparkController@handle']);

Route::post('camera/event/store', ['uses' => 'CCTVController@store']);

Route::group(array('middleware' => 'role:admin'), function() {
    Route::resource('detected_devices', 'DetectedDevicesController');
});

Route::group(array('middleware' => 'role:acs'), function() {
    Route::resource('devices', 'DeviceController');
});

//New ACES Endpoint
Route::get('acs/test', ['uses' => 'ACS\TestController@index', 'middleware' => 'acs']);
Route::get('acs/status/{tagId}', ['uses' => 'ACS\StatusController@show', 'middleware' => 'acs']);
Route::post('acs/node/boot', ['uses' => 'ACS\NodeController@boot', 'middleware' => 'acs']);
Route::post('acs/node/heartbeat', ['uses' => 'ACS\NodeController@heartbeat', 'middleware' => 'acs']);

Route::post('acs/activity', ['uses' => 'ACS\ActivityController@store', 'middleware' => 'acs']);
Route::put('acs/activity/{sessionId}', ['uses' => 'ACS\ActivityController@update', 'middleware' => 'acs']);
Route::delete('acs/activity/{sessionId}', ['uses' => 'ACS\ActivityController@destroy', 'middleware' => 'acs']);

##########################
# Activity Page
##########################

Route::get('activity', ['uses' => 'ActivityController@index', 'as'=>'activity.index', 'middleware'=>'role:member']);
Route::get('activity/realtime', ['uses' => 'ActivityController@realtime', 'as'=>'activity.realtime', 'middleware'=>'role:member']);



##########################
# Storage Boxes
##########################

Route::get('storage_boxes', ['uses'=>'StorageBoxController@index', 'as'=>'storage_boxes.index', 'middleware'=>'role:member']);
Route::put('storage_boxes/{id}', ['uses'=>'StorageBoxController@update', 'as'=>'storage_boxes.update', 'middleware'=>'role:member']);



##########################
# Stats
##########################

Route::get('stats', ['uses'=>'StatsController@index', 'middleware'=>'role:member', 'as'=>'stats.index']);
Route::get('stats/gocardless', ['uses'=>'StatsController@ddSwitch', 'middleware'=>'role:member', 'as'=>'stats.gocardless']);



##########################
# Notification Emails
##########################

Route::get('notification_email/create', ['as' => 'notificationemail.create', 'uses' => 'NotificationEmailController@create', 'middleware'=>'role:member']);
Route::post('notification_email', ['as' => 'notificationemail.store', 'uses' => 'NotificationEmailController@store', 'middleware'=>'role:member']);



##########################
# Proposals
##########################

Route::get('proposals', ['uses'=>'ProposalController@index', 'as'=>'proposals.index', 'middleware'=>'role:member']);
Route::get('proposals/create', ['uses'=>'ProposalController@create', 'as'=>'proposals.create', 'middleware'=>'role:admin']);
Route::post('proposals', ['uses'=>'ProposalController@store', 'as'=>'proposals.store', 'middleware'=>'role:admin']);
Route::get('proposals/{id}', ['uses'=>'ProposalController@show', 'as'=>'proposals.show', 'middleware'=>'role:member']);
Route::post('proposals/{id}', ['uses'=>'ProposalController@vote', 'as'=>'proposals.vote', 'middleware'=>'role:member']);
Route::get('proposals/{id}/edit', ['uses'=>'ProposalController@edit', 'as'=>'proposals.edit', 'middleware'=>'role:admin']);
Route::post('proposals/{id}/update', ['uses'=>'ProposalController@update', 'as'=>'proposals.update', 'middleware'=>'role:admin']);



##########################
# Feedback
##########################

Route::post('feedback', ['uses'=>'FeedbackController@store', 'as'=>'feedback.store', 'middleware'=>'role:member']);



##########################
# Roles
##########################

Route::group(array('middleware' => 'role:admin'), function() {
    Route::resource('roles', 'RolesController', []);
    Route::resource('roles.users', 'RoleUsersController', ['only' => ['destroy', 'store']]);
});
Route::group(array('middleware' => 'role:member'), function() {
    Route::resource('groups', 'GroupsController', ['only' => ['index', 'show']]);
});



##########################
# Resources
##########################

Route::get('resources', ['uses'=>'ResourcesController@index', 'middleware'=>'role:member', 'as'=>'resources.index']);
Route::get('resources/policy/{title}', ['uses'=>'ResourcesController@viewPolicy', 'as'=>'resources.policy.view']);



##########################
# Expenses
##########################

Route::group(array('middleware' => 'role:member'), function() {
    Route::resource('expenses', 'ExpensesController');
});



##########################
# Logviewer
##########################

Route::get('logs', ['uses' => '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index', 'middleware'=>'role:admin'])->name('logs');






Route::any('api-docs.json', function() {
    $filePath = Config::get('swagger.doc-dir') . "/api-docs.json";

    if (!File::Exists($filePath)) {
        App::abort(404, "Cannot find {$filePath}");
    }

    $content = File::get($filePath);
    return Response::make($content, 200, array(
        'Content-Type' => 'application/json'
    ));
});

Route::get('api-docs', function() {
    if (Config::get('swagger.generateAlways')) {
        $appDir = base_path()."/".Config::get('swagger.app-dir');
        $docDir = Config::get('swagger.doc-dir');


        if (!File::exists($docDir) || is_writable($docDir)) {
            // delete all existing documentation
            if (File::exists($docDir)) {
                File::deleteDirectory($docDir);
            }

            File::makeDirectory($docDir);

            $excludeDirs = Config::get('swagger.excludes');

            $swagger =  \Swagger\scan($appDir, [
                'exclude' => $excludeDirs
            ]);

            $filename = $docDir . '/api-docs.json';
            file_put_contents($filename, $swagger);
        }
    }

    if (Config::get('swagger.behind-reverse-proxy')) {
        $proxy = Request::server('REMOTE_ADDR');
        Request::setTrustedProxies(array($proxy));
    }

    //need the / at the end to avoid CORS errors on Homestead systems.
    $response = response()->view('swagger.index', array(
            'secure'         => Request::secure(),
            'urlToDocs'      => url('api-docs.json'),
            'requestHeaders' => Config::get('swagger.requestHeaders'),
            'clientId'       => Input::get("client_id"),
            'clientSecret'   => Input::get("client_secret"),
            'realm'          => Input::get("realm"),
            'appName'        => Input::get("appName"),
        )
    );

    //need the / at the end to avoid CORS errors on Homestead systems.
    /*$response = Response::make(
        View::make('swaggervel::index', array(
                'secure'         => Request::secure(),
                'urlToDocs'      => url('api-docs.json'),
                'requestHeaders' => Config::get('swaggervel.requestHeaders') )
        ),
        200
    );*/

    return $response;
});
