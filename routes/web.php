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

Route::get('/asset/{asset}', [
    'as'   => 'assets.show',
    'uses' => 'AssetsController@show',
]);

Route::get('/address/{address}', [
    'as'   => 'addresses.show',
    'uses' => 'AddressesController@show',
]);

Route::get('/txstats', [
    'as'   => 'txstats.show',
    'uses' => 'TxStatsController@show',
]);

Route::get('/stats', [
    'as'   => 'txstats.index',
    'uses' => 'TxStatsController@index',
]);

Route::get('/test', [
    'as'   => 'test',
    'uses' => 'TestController@show',
]);