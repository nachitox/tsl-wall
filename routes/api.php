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
		'middleware' => [
			'api.headers',
		]
	],
	function () {
		Route::get('/messages', 'MessageController@index')->name('messages.index');
		Route::options('/keep-alive', function() { return; });
		Route::post('/login', 'UserController@login')->name('users.login');
		Route::post('/keep-alive', 'UserController@keepAlive')->name('users.refresh');
		Route::post('/register', 'UserController@register')->name('users.register');
	}
);
Route::group(
	[
		'middleware' => [
			'api.headers',
			'auth.jwt',
		]
	],
	function () {
		Route::delete('/messages/{id}', 'MessageController@delete')->name('messages.delete');
		Route::options('/messages/{id}', function() { return; });
		Route::post('/messages', 'MessageController@create')->name('messages.create');
	}
);
