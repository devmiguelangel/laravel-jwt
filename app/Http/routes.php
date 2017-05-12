<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('index');
    });
});

Route::group([ 'prefix' => 'api' ], function () {
    Route::resource('authenticate', 'AuthenticateController', [ 'only' => [ 'index' ] ]);

    Route::post('authenticate', 'AuthenticateController@authenticate');
});


// Authentication routes
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', [
    'as'   => 'auth.login',
    'uses' => 'Auth\AuthController@postLogin',
]);
Route::get('auth/logout', [
    'as'   => 'auth.logout',
    'uses' => 'Auth\AuthController@getLogout',
]);