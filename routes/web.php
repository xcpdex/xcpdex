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

Route::get('/', 'HomeController@index')->name('home');

Route::get('/privacy', function () {
    return view('privacy');
});

Route::get('/terms', function () {
    return view('terms');
});

Route::get('/testing', function () {
    return view('test');
});

Route::get('/assets', [
    'as' => 'assets.index',
    'uses' => 'AssetsController@index',
]);

Route::get('/asset/{asset}', [
    'as' => 'assets.show',
    'uses' => 'AssetsController@show',
]);

Route::get('/project/{project}', [
    'as' => 'projects.show',
    'uses' => 'ProjectsController@show',
]);

Route::get('/test', [
    'uses' => 'HomeController@test',
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

Route::get('/blocks', [
    'as' => 'blocks.index',
    'uses' => 'BlocksController@index',
]);

Route::get('/block/{block_index}', [
    'as' => 'blocks.show',
    'uses' => 'BlocksController@show',
]);

Route::get('/addresses', [
    'as' => 'addresses.index',
    'uses' => 'AddressesController@index',
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

Route::get('/{asset}', function ($asset) {
    return redirect(route('assets.show', ['asset' => $asset]));
});