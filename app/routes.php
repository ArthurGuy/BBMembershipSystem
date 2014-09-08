<?php

# Home

Route::get('members', array('as' => 'members.index', 'uses' => 'MembersController@index'));
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
Route::get('register', ['as' => 'register', 'uses' => 'AccountController@create']);
Route::put('account/{account}/alter-subscription', ['as'=>'account.alter-subscription', 'uses' => 'AccountController@alterSubscription', 'before'=>'role:admin']);
Route::put('account/{account}/admin-update', ['as'=>'account.admin-update', 'uses' => 'AccountController@adminUpdate', 'before'=>'role:admin']);
Route::put('account/{account}/rejoin', ['as'=>'account.rejoin', 'uses' => 'AccountController@rejoin', 'before'=>'role:member']);
Route::get('account/confirm-email/{id}/{hash}', ['as'=>'account.confirm-email', 'uses'=>'AccountController@confirmEmail']);


# Subscription/Payments

Route::get('account/{account}/subscription/store', ['as' => 'account.subscription.store', 'uses' => 'SubscriptionController@store']);
Route::resource('account.subscription', 'SubscriptionController', ['except' => ['store', 'update', 'edit', 'show', 'index']]);
Route::post('gocardless/webhook', ['uses' => 'GoCardlessWebhookController@receive']);

Route::resource('account.payment', 'PaymentController', ['only' => ['store', 'edit', 'update', 'destroy', 'index']]);
Route::post('account/{account}/payment/create', ['as'=>'account.payment.create', 'uses' => 'PaymentController@create']);
Route::get('account/{account}/payment/confirm-payment', ['as' => 'account.payment.confirm-payment', 'uses' => 'PaymentController@confirmPayment']);


# Inductions
Route::get('equipment_training', ['uses'=>'InductionController@index', 'before'=>'role:member', 'as'=>'equipment_training.index']);
Route::post('equipment_training/update', ['uses'=>'InductionController@update', 'before'=>'role:admin', 'as'=>'equipment_training.update']);
Route::resource('account.induction', 'InductionController', ['before'=>'role:admin', 'only' => ['update', 'destroy']]);
//Route::resource('induction', 'InductionController', ['before'=>'role:admin', 'only' => ['index', 'update', 'destroy']]);


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


# Activity Page
Route::get('activity', ['uses' => 'ActivityController@index', 'as'=>'activity.index', 'before'=>'role:member']);


# Storage Boxes
Route::get('storage_boxes', ['uses'=>'StorageBoxController@index', 'as'=>'storage_boxes.index', 'before'=>'role:member']);


Route::get('test', function() {
    //$process = new \BB\Process\CheckMemberships();
    //$process->run();
});
