<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Super Unoptimized
        $xcp_price = fromSatoshi(\App\Asset::whereName('XCP')->first()->histories()->whereType('price')->orderBy('reported_at', 'desc')->first()->value);
        $xcp_price_y = fromSatoshi(\App\Asset::whereName('XCP')->first()->histories()->whereType('price')->where('reported_at', '<', \Carbon\Carbon::now()->subDay())->orderBy('reported_at', 'desc')->first()->value);
        $btc_price = fromSatoshi(\App\Asset::whereName('BTC')->first()->histories()->whereType('price')->orderBy('reported_at', 'desc')->first()->value);
        $btc_price_y = fromSatoshi(\App\Asset::whereName('BTC')->first()->histories()->whereType('price')->where('reported_at', '<', \Carbon\Carbon::now()->subDay())->orderBy('reported_at', 'desc')->first()->value);
        $avg_trade = \App\OrderMatch::whereStatus('completed')->where('block_index', '>', \Cache::get('block_height') - 144)->avg('quote_quantity_usd');
        $avg_trade_y = \App\OrderMatch::whereStatus('completed')->where('block_index', '>', \Cache::get('block_height') - 288)->where('block_index', '<', \Cache::get('block_height') - 144)->avg('quote_quantity_usd');
        $avg_fee = \App\Order::where('block_index', '>', \Cache::get('block_height') - 144)->avg('fee_paid');
        $avg_fee_y = \App\Order::where('block_index', '>', \Cache::get('block_height') - 288)->where('block_index', '<', \Cache::get('block_height') - 144)->avg('fee_paid');
        $d_volume = \App\OrderMatch::whereStatus('completed')->where('block_index', '>', \Cache::get('block_height') - 144)->sum('quote_quantity_usd');
        $y_volume = \App\OrderMatch::whereStatus('completed')->where('block_index', '>', \Cache::get('block_height') - 288)->where('block_index', '<', \Cache::get('block_height') - 144)->sum('quote_quantity_usd');
        $w_volume = \App\OrderMatch::whereStatus('completed')->where('block_index', '>', \Cache::get('block_height') - 1000)->sum('quote_quantity_usd');
        $lw_volume = \App\OrderMatch::whereStatus('completed')->where('block_index', '>', \Cache::get('block_height') - 2000)->where('block_index', '<', \Cache::get('block_height') - 1000)->sum('quote_quantity_usd');
        $m_volume = \App\OrderMatch::whereStatus('completed')->where('block_index', '>', \Cache::get('block_height') - 4300)->sum('quote_quantity_usd');
        $lm_volume = \App\OrderMatch::whereStatus('completed')->where('block_index', '>', \Cache::get('block_height') - 8600)->where('block_index', '<', \Cache::get('block_height') - 4300)->sum('quote_quantity_usd');
        $a_volume = \App\OrderMatch::whereStatus('completed')->sum('quote_quantity_usd');
        $active_markets = \App\Market::where('open_orders_total', '>', 0)->where('order_matches_total', '>', 0)->count();
        $open_orders = \App\Order::open()->count();
        $orders = \App\Order::where('block_index', '>', \Cache::get('block_height') - 144)->count();
        $orders_y = \App\Order::where('block_index', '>', \Cache::get('block_height') - 288)->where('block_index', '<', \Cache::get('block_height') - 144)->count();
        $matches = \App\OrderMatch::where('block_index', '>', \Cache::get('block_height') - 144)->count();
        $matches_y = \App\OrderMatch::where('block_index', '>', \Cache::get('block_height') - 288)->where('block_index', '<', \Cache::get('block_height') - 144)->count();

        return view('home', compact('xcp_price', 'xcp_price_y', 'btc_price', 'btc_price_y', 'avg_trade', 'avg_trade_y', 'avg_fee', 'avg_fee_y', 'd_volume', 'y_volume', 'w_volume', 'lw_volume', 'm_volume', 'lm_volume', 'a_volume', 'active_markets', 'open_orders', 'orders', 'orders_y', 'matches', 'matches_y'));
    }
}
