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
        $block = \App\Block::whereBlockIndex($block_index)->firstOrFail();
        $next_block = \App\Block::whereBlockIndex($block_index + 1)->first();
        $orders = $block->orders;
        $matches = $block->orderMatches;

        return view('blocks.show', compact('block', 'next_block', 'orders', 'matches'));
    }
}