<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlocksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blocks = \App\Block::withCount('orders', 'orderMatches')->orderBy('block_index', 'desc')->paginate(100);

        return view('blocks.index', compact('blocks'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($block_index)
    {
        $block = \App\Block::with('orders', 'orderMatches')->whereBlockIndex($block_index)->firstOrFail();

        return view('blocks.show', compact('block'));
    }
}