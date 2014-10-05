<?php

# Home

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


# Members

Route::resource('members', 'MembersController', ['only'=>['index','show']]);


# Subscription/Payments

Route::get('account/{account}/subscription/store', ['as' => 'account.subscription.store', 'uses' => 'SubscriptionController@store']);
Route::resource('account.subscription', 'SubscriptionController', ['except' => ['store', 'update', 'edit', 'show', 'index']]);
Route::post('gocardless/webhook', ['uses' => 'GoCardlessWebhookController@receive']);

Route::resource('account.payment', 'PaymentController', ['only' => ['store', 'edit', 'update', 'destroy', 'index']]);
Route::post('account/{account}/payment/create', ['as'=>'account.payment.create', 'uses' => 'PaymentController@create']);
Route::get('account/{account}/payment/confirm-payment', ['as' => 'account.payment.confirm-payment', 'uses' => 'PaymentController@confirmPayment']);
Route::post('account/{account}/update-sub-payment', ['as'=>'account.update-sub-payment', 'uses'=>'AccountController@updateSubscriptionAmount']);


# Inductions
Route::post('equipment_training/update', ['uses'=>'InductionController@update', 'before'=>'role:admin', 'as'=>'equipment_training.update']);
Route::resource('account.induction', 'InductionController', ['before'=>'role:admin', 'only' => ['update', 'destroy']]);
//Route::resource('induction', 'InductionController', ['before'=>'role:admin', 'only' => ['index', 'update', 'destroy']]);


# Equipment
Route::get('equipment', ['uses'=>'EquipmentController@index', 'before'=>'role:member', 'as'=>'equipment.index']);


# Statements
Route::resource('statement-import', 'StatementImportController', ['except' => ['index', 'show', 'edit', 'update', 'destroy'], 'before'=>'role:admin']);


# KeyFobs
Route::resource('keyfob', 'KeyFobController', ['only' => ['index', 'store', 'update', 'destroy'], 'before'=>'role:admin']);


# PayPal IPN
Route::post('paypal-ipn', 'PaypalIPNController@receiveNotification');


# Access Control
Route::post('access-control/main-door', ['uses' => 'AccessControlController@mainDoor']);
Route::post('access-control/status', ['uses' => 'AccessControlController@status']);
Route::post('access-control/legacy', ['uses' => 'AccessControlController@legacy']);
Route::get('access-control/main-door/list', ['uses' => 'AccessControl\MainDoorController@all']);
Route::post('access-control/device', ['uses' => 'AccessControlController@device']);

# Activity Page
Route::get('activity', ['uses' => 'ActivityController@index', 'as'=>'activity.index', 'before'=>'role:member']);
Route::get('activity/realtime', ['uses' => 'ActivityController@realtime', 'as'=>'activity.realtime', 'before'=>'role:member']);


# Storage Boxes
Route::get('storage_boxes', ['uses'=>'StorageBoxController@index', 'as'=>'storage_boxes.index', 'before'=>'role:member']);


# Stats
Route::get('stats', ['uses'=>'StatsController@index', 'before'=>'role:member', 'as'=>'stats.index']);

Route::get('test', function() {
    //$process = new \BB\Process\CheckMemberships();
    //$process->run();
});
