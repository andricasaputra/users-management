<?php

Route::post('/login/e-office', 'Auth\\LoginController@eOfficeLogin')->name('e-office.login');

Route::post('/user/login', 'Auth\\ApiLoginController@loginApiUsingUsername')->name('users.api.login');

Route::middleware('auth:api')->group(function(){

	Route::get('/users', 'UsersController@show')->name('users.api');
	Route::get('/user/auth', 'UsersController@forAuth');
	Route::post('/users/table', 'UsersController@showTable')->name('users.api.table');
	Route::get('/users/roles', 'UsersController@showRoles')->name('users.api.roles');

	Route::get('/pegawai', 'PegawaiController@show')->name('pegawai.api');
	Route::post('/pegawai/table', 'PegawaiController@showTable')->name('pegawai.api.table');

	Route::get('token/show', 'SettingController@showToken')->name('token.show');
	Route::post('token/generate', 'SettingController@generateToken')->name('token.generate');
});




