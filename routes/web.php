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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/', [
    'uses' => 'ReserveController@create',
    'as' => 'create'
])->middleware('auth');

Route::get('/user_page', [
    'uses' => 'ReserveController@user',
    'as' => 'user_page'
])->middleware('auth');

Route::post('/delete', [
    'uses' => 'ReserveController@delete',
    'as' => 'delete'
])->middleware('auth');

Route::post('/update', [
    'uses' => 'ReserveController@update',
    'as' => 'update'
])->middleware('auth');

Route::post('/search', [
    'uses' => 'ReserveController@search',
    'as' => 'search'
])->middleware('auth');

Route::get('/edit/{id}/{start?}', [
    'uses' => 'ReserveController@edit',
    'as' => 'edit'
])->middleware('auth');

Route::get('/{start?}',[
    'uses' => 'ReserveController@index',
    'as' => 'index'
])->middleware('auth');;
