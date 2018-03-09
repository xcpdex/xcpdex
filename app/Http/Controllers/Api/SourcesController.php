<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SourcesController extends Controller
{
    public function show(Request $request, $side, $slug)
    {
        $market = \App\Market::whereSlug($slug)->firstOrFail();

        $sources = $market->orders()->whereType($side)
            ->has('orderMatches')
            ->select('source', 'market_id', \DB::raw('count(*) as orders'))
            ->groupBy('source', 'market_id')
            ->orderBy('orders', 'desc')
            ->get();

        return \App\Http\Resources\SourceResource::collection($sources);
    }
}