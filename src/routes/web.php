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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/index', 'HomeController@list')->name('list');

Route::get('/home/feed/{id}', 'HomeController@show')->name('showFeed');
Route::get('/home/add', 'HomeController@add')->name('addFeed');
Route::post('/home/add', 'HomeController@store')->name('storeFeed');
