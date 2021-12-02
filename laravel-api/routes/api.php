<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'Api', 'as' => 'api.'], function () {

    Route::post('login', 'LoginController@login')->name('login');

    Route::post('register', 'RegisterController@register')->name('register');

    Route::group(['middleware' => ['auth:api']], function () {

        Route::get('user', 'AuthenticationController@user')->name('user');

        Route::post('logout', 'LoginController@logout')->name('logout');

        Route::post('uploadimage', 'UserController@uploadImage')->name('uploadimage');

        Route::get('getimages/{credentialType?}/{userId?}', 'UserController@getImages')->name('getimages');
        
        Route::get('getallimages', 'UserController@getAllImages')->name('getallimages');

        Route::post('deleteimg', 'UserController@deleteImg')->name('deleteimg');
    });

});
