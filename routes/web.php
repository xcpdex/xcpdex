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

Route::get('/markets', [
    'as' => 'markets.index',
    'uses' => 'MarketsController@index',
]);

Route::get('/address/{address}', [
    'as' => 'addresses.show',
    'uses' => 'AddressesController@show',
]);

Route::get('/tx/{tx_hash}', [
    'as' => 'orders.show',
    'uses' => 'OrdersController@show',
]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/{market}', [
    'as' => 'markets.show',
    'uses' => 'MarketsController@show',
]);