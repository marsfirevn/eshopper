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

Route::group(['domain' => env('APP_DOMAIN')], function () {
    Route::group(['namespace' => 'Web'], function () {
    });
});

Route::group(['domain' => 'admin.' . env('APP_DOMAIN')], function () {
    Route::group(['namespace' => 'Admin'], function () {
        // Authentication:
        Route::get('auth', 'Auth\LoginController@getAuth')->name('api.admin.auth.getAuth');

        Route::post('/login', 'Auth\LoginController@login')
            ->name('api.admin.auth.postLogin')
            ->middleware('guest:admin');

        Route::group(['prefix' => 'password'], function () {
            Route::post('email', 'Auth\PasswordController@postEmail')->name('api.admin.auth.password.postEmail');
            Route::put('reset/{token}', 'Auth\PasswordController@resetPassword')
                ->name('api.admin.auth.password.resetPassword');
        });

        Route::put('/profile', 'ProfileController@update')->name('api.admin.profile.update');
        Route::get('/admins', 'AdminController@index')->name('api.admin.admin.list')->middleware('auth:admin');
    });
});
