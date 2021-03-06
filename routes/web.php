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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/fixes/test1', 'FixesController@test1');

Route::get('/events.json', 'FrontendController@events');
Route::get('/date/{date}', 'FrontendController@getForDate');
Route::get('/play/{id}.mp3', 'FrontendController@play');
Route::post('/comment', 'FrontendController@addComment');