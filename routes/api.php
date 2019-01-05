<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
	[
		'middleware'	=> 'auth:api',
		'prefix'		=> 'messages'
	],
	function () {
		// all routes to protected resources are registered here  
	    Route::post('/', 'MessageController@create')->name('messages.create');
		Route::delete('/{id}', 'MessageController@delete')->name('messages.delete');
	}
);
Route::group(['prefix' => 'messages'],
	function () {
		// all routes to protected resources are registered here  
	    Route::get('/', 'MessageController@index')->name('messages.index');
	}
);

Route::post('/login', 'UserController@login')->name('users.login');
Route::post('/register', 'UserController@register')->name('users.register');
