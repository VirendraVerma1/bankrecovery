
<?php

Route::get('data','DataController@data')->name('data'); 
Route::get('data_delete/{id}','DataController@data_delete')->name('data_delete'); 
Route::get('data_delete_by_file/{id}','DataController@data_delete_by_file')->name('data_delete_file'); 
Route::get('/file-import','DataController@importView')->name('import-view'); 
Route::post('/import','DataController@import')->name('import'); 
Route::get('/export-users','DataController@exportUsers')->name('export-users');