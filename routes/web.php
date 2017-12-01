<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/', 'PayController@index');
Route::get('/redirect_show', 'PayController@redirectShow')->name('pay_redirect_url');
Route::get('/pay_amount', 'PayController@payAmount');
Route::any('/refund', 'PayController@refundShow');
Route::any('/notify_show', 'PayController@notifyShow')->name('pay_notify_url');
Route::get('/pay_amount_wep', 'PayController@mobliePay');
Route::get('/orderCheck', 'PayController@orderCheck');
Route::get('/wechat_scanning', 'PayController@weChatPay');
Route::any('/notify_url', 'PayController@weChatInfo');