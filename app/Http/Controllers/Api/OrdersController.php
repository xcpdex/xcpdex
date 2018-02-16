<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $markets = \App\Market::withCount('orders', 'orderMatches')->paginate(100);

        return $markets;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $market = \App\Market::whereSlug($slug)->with('baseAsset', 'quoteAsset')->firstOrFail();

        $buy_orders = $market->orders()->whereType('buy')->whereStatus('open')->where('expire_index', '>', \Cache::get('block_height'))->orderBy('exchange_rate', 'desc')->orderBy('tx_index', 'desc')->get();
        $sell_orders = $market->orders()->whereType('sell')->whereStatus('open')->where('expire_index', '>', \Cache::get('block_height'))->orderBy('exchange_rate', 'asc')->orderBy('tx_index', 'desc')->get();

        return [
            'base_asset' => $market->baseAsset,
            'quote_asset' => $market->quoteAsset,
            'buy_orders' => \App\Http\Resources\OrderResource::collection($buy_orders),
            'sell_orders' => \App\Http\Resources\OrderResource::collection($sell_orders),
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}