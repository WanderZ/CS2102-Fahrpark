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

Route::get('/', function () {
  $routes = \App\Models\Journey::retrieveJourneysForGuests();
  return view('welcome', ['routes' => $routes]);
});

// Authentication Routes
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('login', function () {
  return redirect('auth/login');
});
Route::post('login', function () {
  return redirect('auth/login');
});
Route::get('logout', function () {
  return redirect('auth/logout');
});

// Registration Routes
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::get('register', function () {
  return redirect('auth/register');
});
Route::post('register', function () {
  return redirect('auth/register');
});

// User Routes
Route::get('user/{username}', 'UserController@show');
Route::post('user/{username}/changePassword', 'UserController@changePassword');
Route::post('user/{username}/updateProfile', 'UserController@updateProfile');

// Only admin can access
Route::group(['middleware' => 'admin'], function () {
  Route::get('users', 'UserController@index');
  Route::get('users/create', 'UserController@create');
  Route::post('users/create', 'UserController@create');
  Route::post('user/{username}/deleteUser', 'UserController@deleteUser');
});

//Journey Routes
Route::get('journey/', 'JourneysController@index');
Route::get('journey/view/{id}', 'JourneysController@view');
Route::get('journey/search', 'JourneysController@journeyAdvSearch');
Route::post('journey/search','JourneysController@journeySimpleSearch');
Route::post('journey/advsearch', 'JourneysController@postAdvSearch');

//TODO: Run through gatekeeper for these function
Route::group(['middleware' => 'journey'], function () {
  Route::get('journey/create', 'JourneysController@create');
  Route::post('journey/create', 'JourneysController@postCreate');

  Route::get('journey/MyJourneys', 'JourneysController@myJourneys');
  Route::get('journey/edit/{id}', 'JourneysController@edit');
  Route::post('journey/edit/{id}', 'JourneysController@postEdit');

  Route::post('journey/delete', 'JourneysController@postDelete');
});

// Vehicle Routes
Route::get('vehicle/search','Vehicle\VehicleController@search')->name('vehicle.search');
Route::resource('vehicle', 'Vehicle\VehicleController');

// Booking Routes
Route::group(['middleware' => 'auth'], function () {
  Route::get('booking/', 'BookingsController@index');
  Route::post('journey/view/{id}', 'BookingsController@doCreate');
  Route::get('booking/edit/{booking_id}', 'BookingsController@edit');
  Route::post('booking/edit', 'BookingsController@doEdit');
  Route::get('booking/delete/{id}', 'BookingsController@requestDelete');

  Route::get('booking/offer', 'BookingsController@offer');
  Route::get('booking/offer_view/{id}', 'BookingsController@offerView');
  Route::get('booking/offer_view/accept/{journey_id},{booking_id}', 'BookingsController@acceptOffer');
  Route::get('booking/offer_view/reject/{journey_id},{booking_id}', 'BookingsController@rejectOffer');

  Route::get('booking/request', 'BookingsController@request');
});
Route::group(['middleware' => 'admin'], function () {
  Route::get('booking/admin', 'BookingsController@allOffer');
});
// Transactions
Route::get('transactions/', 'TransactionController@index');
Route::post('transactions/create', 'TransactionController@store');
Route::post('transactions/delete', 'TransactionController@destroy');
Route::post('transactions/edit', 'TransactionController@update');
