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

Route::group(['domain' => env('APP_DOMAIN')], function () {
    Route::group(['namespace' => 'Web'], function () {
        Route::get('/', 'HomeController@index')->name('web.home.index');
    });
});

Route::group(['domain' => 'admin.' . env('APP_DOMAIN')], function () {
    Route::group(['namespace' => 'Admin'], function () {
        Route::get('/', 'HomeController@index')->name('admin.home.index');
    });
});
