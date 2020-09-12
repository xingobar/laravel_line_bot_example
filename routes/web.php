<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => '/line'], function() {
    // 文字訊息
   Route::get('/chat', 'LineBotController@send');

   // 圖片訊息 (有連結）
    Route::get('/image', 'LineBotController@image');

    // 純圖片訊息
    Route::get('/image-message', 'LineBotController@imageMessage');

    // 訊息
    Route::post('/message', 'LineBotController@message');

    // rich menu
    Route::get('/richmenu', 'LineBotController@createRichMenu');
    Route::get('/richmenu_list', 'LineBotController@getRichMenuList');
    Route::get('/upload', 'LineBotController@uploadImage');
    Route::get('/cancel', 'LineBotController@cancel');
    Route::get('/default', 'LineBotController@setDefault');
});
