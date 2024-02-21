<?php

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

Route::group(['prefix' => 'user', 'namespace' => 'Api'], function () {
    //user
    Route::post('store', 'UsersController@store');
    Route::post('register', 'UsersController@register');
    Route::post('login', 'UsersController@login');

    //test
    Route::get('test', 'UsersController@test');
});

Route::group(['prefix' => 'user', 'middleware' => 'auth:api', 'namespace' => 'Api'], function () {
    //user
    Route::get('', 'UsersController@index');
    Route::post('update-profile', 'UsersController@updateProfile');
    Route::patch('registered/{id}', 'UsersController@registered');
    Route::put('update', 'UsersController@update');

    //contact
    Route::get('contact', 'ContactsController@index');
    Route::post('contact/store', 'ContactsController@store');
    Route::post('contact/update', 'ContactsController@update');

    //permission
    Route::get('permission', 'PermissionsController@index');
    Route::post('permission-store', 'PermissionsController@store');
    Route::post('permission-update', 'PermissionsController@update');

    //role
    Route::get('role', 'RolesController@index');
    Route::post('role-store', 'RolesController@store');
    Route::post('role-update', 'RolesController@update');
});
