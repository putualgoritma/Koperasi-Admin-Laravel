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

Route::group(['prefix' => 'capital', 'middleware' => 'auth:api', 'namespace' => 'Api'], function () {
    //capital
    Route::get('', 'CapitalController@index');
    Route::post('store', 'CapitalController@store');
    Route::put('update', 'CapitalController@update');

    //test
    Route::post('test', 'CapitalController@test');
});