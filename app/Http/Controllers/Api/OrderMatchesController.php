<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class OrderMatchesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $market = \App\Market::whereSlug($slug)->firstOrFail();

        return $market->orderMatches()->with('order', 'orderMatch', 'orderMatch.block')->orderBy('tx_index', 'desc')->paginate(100);
    }
}