<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderMatchesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return \Cache::remember('api_matches_' . $request->input('page', 1), 5, function() {
            return \App\OrderMatch::has('block')
                ->with('block', 'market')
                ->orderBy('tx_index', 'desc')
                ->paginate(30);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $market = \App\Market::whereSlug($slug)->firstOrFail();

        return $market->orderMatches()->whereStatus('completed')->with('order', 'orderMatch', 'orderMatch.block')->orderBy('tx_index', 'desc')->paginate(30);
    }
}