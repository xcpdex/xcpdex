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
        $assets = \App\Asset::withCount('baseMarkets', 'quoteMarkets')->orderBy('quote_markets_count', 'desc')->paginate(500);

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

        $subassets = \App\Asset::subassets($asset->name)->get();

        $markets = \App\Market::where('base_asset_id', '=', $asset->id)
            ->orWhere('quote_asset_id', '=', $asset->id)
            ->has('orderMatches')
            ->withCount('orders', 'orderMatches')
            ->orderBy('order_matches_count', 'desc')
            ->orderBy('orders_count', 'desc')
            ->get();

        return view('assets.show', compact('asset', 'subassets', 'markets'));
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
