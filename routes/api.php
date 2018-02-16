<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/orders/{market}', [
    'as' => 'api.orders.show',
    'uses' => 'Api\OrdersController@show',
]);

Route::get('/matches/{market}', [
    'as' => 'api.orderMatches.show',
    'uses' => 'Api\OrderMatchesController@show',
]);

Route::get('/charts/{market}', [
    'as' => 'api.highCharts.show',
    'uses' => 'Api\HighChartsController@show',
]);