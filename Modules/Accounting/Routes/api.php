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

Route::group(['prefix' => 'accounting', 'middleware' => 'auth:api', 'namespace' => 'Api'], function () {
    //account
    Route::get('', 'AccountController@index');
    Route::post('store', 'AccountController@store');
    Route::put('update', 'AccountController@update');

    //ledger
    Route::get('ledger', 'LedgerController@index');
    Route::post('ledger-store', 'LedgerController@store');
    Route::put('ledger-update', 'LedgerController@update');

    //account-module
    Route::get('account-module', 'AccountModuleController@index');
    Route::post('account-module-store', 'AccountModuleController@store');
    Route::put('account-module-update', 'AccountModuleController@update');

    //test
    Route::get('test', 'AccountController@index');
});