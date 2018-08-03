<?php

use Illuminate\Http\Request;

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
Route::prefix('yrjj')->group(function () {
//    Route::post('wx_users', 'WxUserController@index');
//    Route::post('wx_users/{id}', 'WxUserController@show');
//    Route::post('wx_users', 'WxUserController@store');
//    Route::put('wx_users/{id}', 'WxUserController@update');
//    Route::delete('wx_users/{id}', 'WxUserController@delete');
    Route::post('login_session', 'CommonController@loginSession');
    Route::post('per_image', 'CommonController@perImage');
    Route::post('product_image', 'CommonController@productImage');
    Route::post('quote', 'CommonController@quote');
    Route::post('update_userinfo', 'WxUserController@updateUserInfo');
    Route::post('wx_steps', 'PunchController@wxSteps');
    Route::post('punch', 'PunchController@punch');
    Route::post('is_punched', 'PunchController@isPunched');
    Route::post('history_steps', 'PunchController@historySteps');
    Route::post('history_punch', 'PunchController@historyPunch');
    Route::post('powers', 'PunchController@powers');
    Route::get('power_record', 'CommonController@powerRecord');
    Route::post('rank', 'RankController@rank');
    Route::post('openid_gid', 'RankController@openGid');

});
Route::prefix('limit')->group(function () {
//    Route::post('wx_users', 'WxUserController@index');
//    Route::post('wx_users/{id}', 'WxUserController@show');
//    Route::post('wx_users', 'WxUserController@store');
//    Route::put('wx_users/{id}', 'WxUserController@update');
//    Route::delete('wx_users/{id}', 'WxUserController@delete');
    Route::post('login_session', 'CommonController@limitSessionKey');
    Route::post('put_limit', 'LimitController@putLimit');
    Route::post('get_limit', 'LimitController@getLimit');
});