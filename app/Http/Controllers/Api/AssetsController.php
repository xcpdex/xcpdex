<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return \Cache::remember('assets_index_' . $request->input('page', 1), 360, function() {
            return \App\Asset::has('baseMarkets')->orHas('quoteMarkets')
                ->withCount('baseMarkets', 'quoteMarkets')
                ->orderBy('orders_total', 'desc')
                ->paginate(30);
        });

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
    public function show(Request $request, $slug)
    {
        $project = \App\Project::whereSlug($slug)->firstOrFail();

        $request->validate([
            'filter' => 'sometimes|in:index',
            'order_by' => 'sometimes|in:name,volume_total_usd,orders_total,order_matches_total',
            'direction' => 'sometimes|in:asc,desc',
        ]);

        $page = $request->input('page', 1);
        $filter = $request->input('filter', 'index');
        $order_by = $request->input('order_by', 'volume_total_usd');
        $direction = $request->input('direction', 'desc');

        return \Cache::remember('api_projects_'. $slug .'_' . $page . '_' . $filter . '_' . $order_by . '_' . $direction, 5, function() use($project, $filter, $order_by, $direction) {
            return $project->assets()
                ->orderBy($order_by, $direction)
                ->paginate(10);
        });
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
