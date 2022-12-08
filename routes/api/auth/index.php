<?php
/*new api*/

Route::post('make_and_update_connection_request', 'Api\AuthController@make_and_update_connection_request');

	Route::post('/get_connection_id','Api\AuthController@get_connection_id'); 
//Authentication
	Route::post('/request_otp','Api\AuthController@otp_request');
	Route::post('/verify_otp','Api\AuthController@verify_otp');
	Route::post('/app_shop_register','Api\AuthController@AppShopRegister');
	Route::post('/app_shop_login','Api\AuthController@AppShopLogin');
	Route::post('/logout','Api\AuthController@logout');
	Route::post('/forgot_pass','Api\AuthController@forgortPassword');
	Route::post('/forgot_pass_otp_verify','Api\AuthController@forgotpass_otp_verify');

	Route::post('/add_shop_category','Api\AuthController@shopCategoryAdd');
	Route::post('/shop_status_details','Api\AuthController@shopStatusDetail');
	Route::post('/shop_status_update','Api\AuthController@shopStatusUpdate');
	Route::post('/shop_visit_count','Api\AuthController@shopVisitCount');


	Route::post('/registerAccount','Api\AuthController@registerAccount');
	Route::post('/loginAccount','Api\AuthController@logInAccount');
?>