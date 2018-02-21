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

Route::get('/assets', [
    'as' => 'assets.index',
    'uses' => 'AssetsController@index',
]);

Route::get('/markets', [
    'as' => 'markets.index',
    'uses' => 'MarketsController@index',
]);

Route::get('/market/{market}', [
    'as' => 'markets.show',
    'uses' => 'MarketsController@show',
]);

Route::get('/matches', [
    'as' => 'matches.index',
    'uses' => 'OrderMatchesController@index',
]);

Route::get('/orders', [
    'as' => 'orders.index',
    'uses' => 'OrdersController@index',
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

Route::get('{asset}', [
    'as' => 'assets.show',
    'uses' => 'AssetsController@show',
]);