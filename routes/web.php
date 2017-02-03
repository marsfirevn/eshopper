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
    Route::get('/', function () {
        return view('welcome');
    });
});

Route::group(['domain' => 'admin.' . env('APP_DOMAIN')], function () {
    Route::get('/', function () {
        return view('welcome');
    });
});
