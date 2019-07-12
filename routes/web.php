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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['namespace'=>'Auth'],function(){
    Route::get('weChat','LoginController@init_weChat');
    Route::any('connect_weChat_servsr','LoginController@connect_weChat_servsr');
    Route::any('weChat_menu','LoginController@get_menu');
    Route::any('group_sending','LoginController@group_sending');
    Route::any('create_menu','LoginController@create_menu');
});
