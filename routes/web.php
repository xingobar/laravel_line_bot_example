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
    Route::group(['prefix' => '/richmenu'], function() {
        Route::post('/', 'LineRichMenuController@create');
        Route::get('/', 'LineRichMenuController@get');
        Route::post('/upload', 'LineRichMenuController@uploadImage');
        Route::post('/cancel', 'LineRichMenuController@cancelDefault');
        Route::put('/default', 'LineRichMenuController@setDefault');
        Route::delete('/', 'LineRichMenuController@delete');
    });
});
