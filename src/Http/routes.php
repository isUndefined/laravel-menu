<?php

Route::group(['prefix' => 'backend', 'namespace' => 'Isundefined\Menu\Http\Controllers', 'middleware'=>'auth'], function () {
	
	Route::get('/menu', [
		'as' => 'backend.menu.manage',
		'uses' => 'MenuController@backendIndex',
	]);
	
	Route::get('/menu/create', [
		'as' => 'backend.menu.menu_create',
		'uses' => 'MenuController@backendNewMenu',
	]);
	
	Route::post('/menu/category/store', [
		'as' => 'backend.menu.menu_category_create',
		'uses' => 'MenuController@backendNewMenuStore',
	]);
	
	Route::get('/menu/{id}', [
		'as' => 'backend.menu.view',
		'uses' => 'MenuController@backendViewMenu',
	]);
	
	Route::post('/menu/ajax-update-position', [
		'as' => 'backend.menu.update_position',
		'middleware'=>'ajax',
		'uses' => 'MenuController@backendUpdatePosition',
	]);	
	
	Route::post('/menu/ajax-update-menu', [
		'as' => 'backend.menu.update_menu',
		'middleware'=>'ajax',
		'uses' => 'MenuController@backendUpdateMenu',
	]);
	
	Route::post('/menu/ajax-show-edit-menu', [
		'as' => 'backend.menu.show_update_menu',
		'middleware'=>'ajax',
		'uses' => 'MenuController@backendShowUpdateMenu',
	]);
	
	Route::get('/menu/delete/{id}', [
		'as' => 'backend.menu.delete_menu',
		'uses' => 'MenuController@backendDeleteMenu',
	]);	
	
	Route::get('/menus/delete/{id}', [
		'as' => 'backend.menu.delete_menu_all',
		'uses' => 'MenuController@backendDeleteMenusCategory',
	]);	
	
	Route::post('/menu/create', [
		'as' => 'backend.menu.post_create',
		'uses' => 'MenuController@backendCreateMenu',
	]);
	
	
});
	