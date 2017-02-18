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
        // Admin page:
        Route::get('/login', 'HomeController@index')->name('admin.auth.getLogin')->middleware('guest:admin');

        Route::get('/', 'HomeController@index')->name('admin.home.index');
        Route::get('/dashboard', 'HomeController@index')->name('dashboard');
        Route::get('/orders', 'HomeController@index')->name('admin.order.list');
        Route::get('/products', 'HomeController@index')->name('admin.product.list');
        Route::get('/categories', 'CategoryController@index')->name('admin.category.list');
        Route::get('/admins', 'CategoryController@index')->name('admin.admin.list');
        Route::get('/profile', 'HomeController@index')->name('admin.profile.showForm');
        Route::get('/customers', 'HomeController@index')->name('admin.customer.list');
        Route::get('reset/{token}', 'HomeController@index')->name('admin.auth.password.changePasswordForm');
        Route::get('password/email', 'HomeController@index')->name('admin.auth.password.getResetForm');
        Route::get('logout', 'Auth\LoginController@logout')->name('admin.auth.logout');
    });
});
