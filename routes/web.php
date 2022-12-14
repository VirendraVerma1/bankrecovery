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

Route::middleware(['auth'])->group(function(){
include('auth/auth.php');
include('auth/data.php');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/phpinfo', function() {
    phpinfo();
});


Route::get('crontest','HomeController@crontest')->name('crontest');
Route::get('updatetest','DataController@isshedulingon')->name('updatetest');
Route::get('filetest','DataController@filetest')->name('filetest');