<?php

# Home

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index', 'before'=>'auth'));


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


# Subscription/Payments

Route::get('account/{account}/subscription/store', ['as' => 'account.subscription.store', 'uses' => 'SubscriptionController@store']);
Route::resource('account.subscription', 'SubscriptionController', ['except' => ['store', 'update', 'edit', 'show', 'index']]);
Route::post('gocardless/webhook', ['uses' => 'GoCardlessWebhookController@receive']);

Route::resource('account.payment', 'PaymentController', ['only' => ['store', 'edit', 'update', 'destroy', 'index']]);
Route::post('account/{account}/payment/create', ['as'=>'account.payment.create', 'uses' => 'PaymentController@create']);
Route::get('account/{account}/payment/confirm-payment', ['as' => 'account.payment.confirm-payment', 'uses' => 'PaymentController@confirmPayment']);


# Inductions
Route::resource('account.induction', 'InductionController', ['before'=>'auth', 'only' => ['index', 'update', 'destroy']]);
Route::resource('induction', 'InductionController', ['before'=>'auth.admin', 'only' => ['index', 'update', 'destroy']]);