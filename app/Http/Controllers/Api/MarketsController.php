<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarketsController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'filter' => 'sometimes|in:index,active,popular',
            'order_by' => 'sometimes|in:quote_market_cap_usd,name,last_price_usd,quote_volume_usd,quote_volume_usd_month,open_orders_total,orders_total,order_matches_total,last_traded_at',
            'direction' => 'sometimes|in:asc,desc',
        ]);

        $page = $request->input('page', 1);
        $filter = $request->input('filter', 'index');
        $order_by = $request->input('order_by', 'quote_volume_usd_month');
        $direction = $request->input('direction', 'desc');

        return \Cache::remember('api_markets_' . $page . '_' . $filter . '_' . $order_by . '_' . $direction, 5, function() use($filter, $order_by, $direction) {
            if('active' === $filter)
            {
                return \App\Market::where('open_orders_total', '>', 0)
                    ->where('order_matches_total', '>', 0)
                    ->orderBy($order_by, $direction)
                    ->orderBy('last_traded_at', $direction)
                    ->paginate(30);
            }
            elseif('popular' === $filter)
            {
                return \App\Market::where('open_orders_total', '>', 0)
                    ->where('order_matches_total', '>', 50)
                    ->orderBy($order_by, $direction)
                    ->orderBy('last_traded_at', $direction)
                    ->paginate(30);
            }
            else
            {
                return \App\Market::orderBy($order_by, $direction)
                    ->orderBy('last_traded_at', $direction)
                    ->paginate(30);
            }
        });
    }

    public function show(Request $request, $slug)
    {
        $asset = \App\Asset::whereSlug($slug)->firstOrFail();

        $request->validate([
            'filter' => 'sometimes|in:index,active,base,quote',
            'order_by' => 'sometimes|in:quote_market_cap_usd,name,last_price_usd,quote_volume_usd,quote_volume_usd_month,open_orders_total,orders_total,order_matches_total,last_traded_at',
            'direction' => 'sometimes|in:asc,desc',
        ]);

        $page = $request->input('page', 1);
        $filter = $request->input('filter', 'index');
        $order_by = $request->input('order_by', 'quote_volume_usd_month');
        $direction = $request->input('direction', 'desc');

        return \Cache::remember('api_markets_' . $slug . '_' . $page . '_' . $filter . '_' . $order_by . '_' . $direction, 5, function() use($asset, $filter, $order_by, $direction) {
            if('active' === $filter)
            {
                return \App\Market::where('base_asset_id', '=', $asset->id)
                    ->where('open_orders_total', '>', 0)
                    ->where('order_matches_total', '>', 0)
                    ->orWhere('quote_asset_id', '=', $asset->id)
                    ->where('open_orders_total', '>', 0)
                    ->where('order_matches_total', '>', 0)
                    ->orderBy($order_by, $direction)
                    ->orderBy('quote_volume_usd', $direction)
                    ->orderBy('last_traded_at', $direction)
                    ->paginate(30);
            }
            elseif('base' === $filter)
            {
                return \App\Market::where('base_asset_id', '=', $asset->id)
                    ->orderBy($order_by, $direction)
                    ->orderBy('quote_volume_usd', $direction)
                    ->orderBy('last_traded_at', $direction)
                    ->paginate(30);
            }
            elseif('quote' === $filter)
            {
                return \App\Market::where('quote_asset_id', '=', $asset->id)
                    ->orderBy($order_by, $direction)
                    ->orderBy('quote_volume_usd', $direction)
                    ->orderBy('last_traded_at', $direction)
                    ->paginate(30);
            }
            else
            {
                return \App\Market::where('base_asset_id', '=', $asset->id)
                    ->orWhere('quote_asset_id', '=', $asset->id)
                    ->orderBy($order_by, $direction)
                    ->orderBy('quote_volume_usd', $direction)
                    ->orderBy('last_traded_at', $direction)
                    ->paginate(30);
            }
        });
    }
}