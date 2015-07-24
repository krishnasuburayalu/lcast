<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'home', 'uses' => 'WelcomeController@index']);

//LCAST URL Params
Route::get('profile/bulk', 'ProfileController@add_bulk');
Route::get('profile/add/{id}', 'ProfileController@add');
Route::get('profile/update/{id}', 'ProfileController@update');
Route::get('profile/show/{id}', 'ProfileController@show');
Route::get('profile/delete/{id}', 'ProfileController@delete');
Route::get('profile/search', 'ProfileController@search');
Route::get('profile/facets', 'ProfileController@facets');

//Route::get('profile/{id}/album/new', 'ProfileController@add_new_album');
//Route::get('profile/{id}/album/{album_id}', 'ProfileController@show_album');



