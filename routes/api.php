<?php

use Illuminate\Support\Facades\Route;

Route::post('register', 'UserController@create');

Route::group([
    'middleware' => 'api',
], function () {
    Route::resource('transactions', 'TransactionController');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});
