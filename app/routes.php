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
Route::put('account/{account}/alter-subscription', ['as'=>'account.alter-subscription', 'uses' => 'AccountController@alterSubscription', 'before'=>'auth.admin']);
Route::put('account/{account}/admin-update', ['as'=>'account.admin-update', 'uses' => 'AccountController@adminUpdate', 'before'=>'auth.admin']);
Route::put('account/{account}/rejoin', ['as'=>'account.rejoin', 'uses' => 'AccountController@rejoin', 'before'=>'auth']);
Route::get('member-import', function() {
    $import = new AccountImportController();
    $import->fetch();
});
Route::get('account/confirm-email/{id}/{hash}', ['as'=>'account.confirm-email', 'uses'=>'AccountController@confirmEmail']);


# Subscription/Payments

Route::get('account/{account}/subscription/store', ['as' => 'account.subscription.store', 'uses' => 'SubscriptionController@store']);
Route::resource('account.subscription', 'SubscriptionController', ['except' => ['store', 'update', 'edit', 'show', 'index']]);
Route::post('gocardless/webhook', ['uses' => 'GoCardlessWebhookController@receive']);

Route::resource('account.payment', 'PaymentController', ['only' => ['store', 'edit', 'update', 'destroy', 'index']]);
Route::post('account/{account}/payment/create', ['as'=>'account.payment.create', 'uses' => 'PaymentController@create']);
Route::get('account/{account}/payment/confirm-payment', ['as' => 'account.payment.confirm-payment', 'uses' => 'PaymentController@confirmPayment']);


# Inductions
Route::resource('account.induction', 'InductionController', ['before'=>'auth.admin', 'only' => ['index', 'update', 'destroy']]);
Route::resource('induction', 'InductionController', ['before'=>'auth.admin', 'only' => ['index', 'update', 'destroy']]);


# Statements
Route::resource('statement-import', 'StatementImportController', ['except' => ['index', 'show', 'edit', 'update', 'destroy'], 'before'=>'auth.admin']);


#KeyFobs
Route::resource('keyfob', 'KeyFobController', ['only' => ['index', 'store', 'update', 'destroy'], 'before'=>'auth.admin']);


#PayPal IPN
Route::post('paypal-ipn', 'PaypalIPNController@receiveNotification');


# Access Control
Route::post('access-control/main-door', ['uses' => 'AccessControlController@mainDoor']);
Route::post('access-control/status', ['uses' => 'AccessControlController@status']);



Route::get('test', function() {
   $process = new \BB\Process\CheckMemberships();
    $process->run();
});

//This route is only to be called once!
//Route::get('send-welcome', 'AccountController@sendWelcomeEmails');