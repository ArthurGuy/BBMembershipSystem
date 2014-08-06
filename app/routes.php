<?php

# Home

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index', 'before'=>'auth'));


# Authentication

Route::get('login', ['as' => 'login', 'uses' => 'SessionController@create']);
Route::get('logout', ['as' => 'logout', 'uses' => 'SessionController@destroy']);
Route::resource('session', 'SessionController', ['only' => ['create', 'store', 'destroy']]);


# Account


Route::resource('account', 'AccountController');
Route::get('register', ['as' => 'register', 'uses' => 'AccountController@create']);


# Subscription/Payments

Route::get('account/{account}/subscription/store', ['as' => 'account.subscription.store', 'uses' => 'SubscriptionController@store']);
Route::resource('account.subscription', 'SubscriptionController', ['except' => ['store', 'update', 'edit', 'show', 'index']]);
Route::post('gocardless/webhook', ['uses' => 'GoCardlessWebhookController@receive']);


# Inductions
Route::resource('account.induction', 'InductionController', ['before'=>'auth']);
Route::get('account/{account}/induction/{id}/confirm-payment', ['as' => 'account.induction.confirm-payment', 'uses' => 'InductionController@confirmPayment']);
