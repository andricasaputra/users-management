<?php

Route::get('/', 'HomeController@index');

Route::namespace('Auth')->group(function(){
	Route::get('/login', 'LoginController@showLoginForm')->name('login.form');
	Route::post('/login', 'LoginController@login')->name('login');
	Route::get('/logout', 'LoginController@logout')->name('logout');
	Route::post('/sso/{secret_key?}', 'LoginController@autoLogin')->name('sso.login');
});

Route::middleware('auth')->group(function(){

	Route::get('home', 'HomeController@home')->name('home');
	Route::resource('users', 'UsersController')->except('destroy');
	Route::get('users/destroy/{user?}', 'UsersController@delete')->name('users.destroy');
	Route::get('users/fetch/{user}', 'UsersController@fetch')->name('users.fetch');
	Route::get('import', 'UsersController@createBulk')->name('users.create.bulk');
	Route::post('import', 'UsersController@import')->name('import');
	Route::get('users/roles/{user}', 'UsersController@roles')->name('users.roles');
	Route::post('users/roles/{user}', 'UsersController@attachRoles')->name('users.attach.roles');

	Route::prefix('pegawai')->group(function(){
		Route::get('/', 'PegawaiController@index')->name('pegawai.index');
		Route::get('show/{nip?}', 'PegawaiController@detail')->name('pegawai.detail');
		Route::post('fetch', 'PegawaiController@fetchAllSimAsnData')->name('pegawai.fetch');
		Route::get('update/{pegawai}', 'PegawaiController@fetchSimAsnData')->name('pegawai.update');
	});

	Route::resource('wilkers', 'WilkerController')->except(['destroy']);
	Route::post('wilkers_delete/{wilker?}', 'WilkerController@destroy')->name('wilker.destroy');

	Route::namespace('Admin')->group(function(){
		Route::resource('roles', 'RoleController');
		Route::resource('permissions', 'PermissionController');
	});

	Route::get('setting', 'SettingController@index')->name('setting.index');
});
