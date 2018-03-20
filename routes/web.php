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
//
//Route::get('/', function () {
//	return 'Hello World';
//});

Route::any('/', 'Controller@split');
Route::any('/user', 'Controller@index');

Route::get('/rrr', function () {
    return view('welcome');
});

Route::resource('pattern','PatternController');
