<?php

use Illuminate\Http\Request;
use Laravel\Passport\Passport;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/registration','AuthController@register');

Passport::routes(function($router){
    Route::post('/token', [
        'uses' => 'AccessTokenController@issueToken',
        'as' => 'passport.token',
        'middleware' => 'throttle'
    ]);
},['prefix'=>'user']);

Route::get('/longpolling','LongPollingController@longpolling');

Route::get('/chat.create','ChatController@create');
Route::get('/chat.acceptChatRequest','ChatController@acceptChatRequest');

//Route::post('/user/token','Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
//Route::post('/user/token','AuthController@login');
Route::get('message.send',"MessageController@send");
