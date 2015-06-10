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

//Route::get('home', 'HomeController@index');
Route::get('welcome', 'ProducerController@index');
Route::get('addindex', 'ProducerController@addIndex');
Route::get('about', 'AboutController@index');

Route::get('contact', 
  ['as' => 'contact', 'uses' => 'AboutController@create']);
Route::post('contact', 
  ['as' => 'contact_store', 'uses' => 'AboutController@store']);

Route::group(['prefix' => 'admin', 'namespace' => 'admin', 'middleware' => 'admin'], 
	function()
	{
	    Route::resource('user', 'UserController');
	});

Route::resource('lists', 'ListsController');

Route::resource('lists.tasks', 'TasksController');

Route::post('lists/{lists}/tasks/{tasks}/complete', 
	array('as' => 'complete_task', 'uses' => 'TasksController@complete'));

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

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



