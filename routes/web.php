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

Route::get('/', "MyController@index")->name("top-page");

Route::get('/showResult', "MyController@showResult")->name("showResult");

Route::post('/execute', 'MyController@execute')->name("execute");