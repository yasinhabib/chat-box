<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@login');

    Route::get('consume', 'ChatController@consume');
    
    Route::group(['middleware' => ['jwt.verify']], function () {
        Route::get('get-current-user', 'UserController@getCurrentUser');
        Route::get('get-all-chats/{target_user_id}', 'ChatController@getAllChat');
        Route::get('get-all-users', 'UserController@getAllUser');
        Route::post('publish', 'ChatController@publish');
    });
});
