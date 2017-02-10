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
        // Authentication:
        Route::get('auth', 'Auth\LoginController@getAuth')->name('admin.auth.getAuth');
        Route::post('login', 'Auth\LoginController@login')->name('admin.auth.postLogin');
        Route::get('logout', 'Auth\LoginController@logout')->name('admin.auth.logout');

        Route::group(['prefix' => 'password'], function () {
            Route::get('email', 'HomeController@index')->name('admin.auth.password.getResetForm');
            Route::post('email', 'Auth\PasswordController@postEmail')->name('admin.auth.password.email');
            Route::post('reset', 'Auth\PasswordController@reset')->name('admin.auth.password.reset');
        });

        // Admin page:
        Route::get('/', 'HomeController@index')->name('admin.home.index');
        Route::get('/login', 'HomeController@index')->name('admin.auth.getLogin');
        Route::get('/dashboard', 'HomeController@index')->name('dashboard');
    });
});
