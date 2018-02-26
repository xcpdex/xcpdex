<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assets = \App\Asset::withCount('baseMarkets', 'quoteMarkets')->orderBy('base_markets_count', 'desc')->paginate(500);

        return view('assets.index', compact('assets'));
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
        $asset = \App\Asset::whereName($slug)->first();

        // $subassets = \App\Asset::subassets($asset->name)->get();

        $daily_block = \App\Block::has('orders')->orderBy('block_time', '')->first();
        $month_block = \App\Block::has('orders')->where('block_time', '>', $daily_block->block_time->subDays(30))->first();

        $daily = $daily_block->orders()->first();
        $month = $month_block->orders()->first();

        $daily_volume = $asset->baseMarketsOrderMatches()->where('tx_index', '>', $daily->tx_index)->sum('base_quantity') + $asset->quoteMarketsOrderMatches()->where('tx_index', '>', $daily->tx_index)->sum('quote_quantity');
        $month_volume = $asset->baseMarketsOrderMatches()->where('tx_index', '>', $month->tx_index)->sum('base_quantity') + $asset->quoteMarketsOrderMatches()->where('tx_index', '>', $month->tx_index)->sum('quote_quantity');

        $active_markets = \App\Market::where('base_asset_id', '=', $asset->id)
            ->has('orderMatches')
            ->has('openOrders')
            ->orWhere('quote_asset_id', '=', $asset->id)
            ->has('orderMatches')
            ->has('openOrders')
            ->with('lastMatch', 'highestOpenBuyOrder', 'lowestOpenSellOrder')
            ->withCount('orders', 'orderMatches', 'openOrders')
            ->orderBy('open_orders_count', 'desc')
            ->orderBy('orders_count', 'desc')
            ->paginate(5000);

        $inactive_markets = \App\Market::where('base_asset_id', '=', $asset->id)
            ->has('orderMatches')
            ->has('openOrders', '=', 0)
            ->orWhere('quote_asset_id', '=', $asset->id)
            ->has('orderMatches')
            ->has('openOrders', '=', 0)
            ->with('lastMatch')
            ->withCount('orders', 'orderMatches', 'openOrders')
            ->orderBy('orders_count', 'desc')
            ->orderBy('order_matches_count', 'desc')
            ->paginate(5000);

        return view('assets.show', compact('asset', 'daily_volume', 'month_volume', 'active_markets', 'inactive_markets'));
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
