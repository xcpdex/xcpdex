<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $markets = \Cache::remember('markets.index', 10, function() {
            $block_index = \Cache::get('block_height') - 13140;
            return \App\Market::has('orderMatches', '>', 50)
                ->whereHas('orderMatches', function ($query) use ($block_index) {
                    $query->where('block_index', '>', $block_index);
                })
                ->withCount('openOrders', 'orders', 'orderMatches')
                ->orderBy('quote_market_cap_usd', 'desc')
                ->paginate(100);
        });

        return view('markets.index', compact('markets'));
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
        $market = \App\Market::whereSlug($slug)
            ->with('baseAsset', 'quoteAsset')
            ->withCount('orders', 'orderMatches')
            ->firstOrFail();

        $last_match = $market->orderMatches()
            ->whereStatus('completed')
            ->has('order.block')
            ->orderBy('tx_index', 'desc')
            ->first();

        $last_order = $market->orders()
            ->has('block')
            ->orderBy('tx_index', 'desc')
            ->first();

        return view('markets.show', compact('market', 'last_match', 'last_order'));
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
