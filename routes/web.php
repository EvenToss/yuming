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

Route::get('/yuming', 'DomainController@index');
Route::get('/proxy', 'DomainController@IpQuery');
Route::get('export', 'DomainController@export');
Route::get('/juzi', 'JuziController@index');
