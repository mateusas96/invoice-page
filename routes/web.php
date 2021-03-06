<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest');


Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);


Route::group(['middleware' => 'auth', 'middleware' => 'validateBackHistory'], function(){
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('{path}', "HomeController@index")->where('path', '([A-z\-\/]+)?');
});



