<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('home');
});

Route::any('/search/{q?}/{p?}', array('as' => 'search', 'uses' => 'BusquedaController@buscar'));

Route::get('/getmp3/{id}', array('as' => 'getmp3', 'uses' => 'MusicaController@get_mp3'));
Route::get('/getdownload/{id?}/{nombre?}', array('as' => 'getdownload', 'uses' => 'MusicaController@obtener_descarga'));