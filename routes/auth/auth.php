<?php

Route::get('users','UserController@get_all_users')->name('users');
Route::get('user-edit/{id}','UserController@user_edit')->name('user_edit');
Route::post('user-update', 'UserController@user_update')->name('user_update');
Route::get('user-add', 'UserController@open_add_user_page')->name('user_add');
Route::post('add-user', 'UserController@user_add')->name('add_user');
Route::get('user-delete/{id}','UserController@user_delete')->name('user_delete');
Route::get('logout','UserController@logout')->name('logout');


Route::get('test','UserController@test_html')->name('test');
