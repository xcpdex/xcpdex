@extends('layouts.app')

@section('title', 'Block #' . $block->block_index)

@section('description', 'Bitcoin Block #' . $block->block_index . ' - Counterparty Dex Transactions')

@section('content')
<ul class="pagination mt-1 float-right">
@if($block->block_index !== 278319)
  <li class="page-item"><a href="{{ url(route('blocks.show', ['block' => $block->block_index - 1])) }}" rel="prev" class="page-link">&laquo;</a></li>
@else
  <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
@endif
@if($next_block)
  <li class="page-item"><a href="{{ url(route('blocks.show', ['block' => $next_block->block_index])) }}" rel="next" class="page-link">&raquo;</a></li>
@else
  <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
@endif
</ul>
<h1 class="mb-3">Block #{{ $block->block_index }}</h1>
<div class="table-responsive block-data">
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th>Key</th>
        <th>Value</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>Block Hash</th>
        <td>{{ $block->block_hash }}</td>
      </tr>
      <tr>
        <th>Timestamp</th>
        <td>{{ $block->mined_at }} EST</td>
      </tr>
      <tr>
        <th>Difficulty</th>
        <td>{{ $block->difficulty }}</td>
      </tr>
    </tbody>
  </table>
</div>
<h3 class="mt-3 mb-3">Orders <small class="float-right lead mt-2">{{ count($orders) }} Found</small></h3>
<div class="table-responsive order-matches">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>Date</th>
        <th>Market</th>
        <th>Type</th>
        <th>Status</th>
        <th>Price</th>
        <th>Amount</th>
        <th>Total</th>
        <th>Source</th>
      </tr>
    </thead>
    <tbody>
      @foreach($orders as $order)
      <tr>
        <td><a href="{{ url(route('orders.show', ['order' => $order->tx_hash])) }}">{{ $order->block->mined_at }}</a></td>
        <td><a href="{{ url(route('markets.show', ['market' => $order->market->slug])) }}">{{ $order->market->name }}</a></td>
        <td class="{{ $order->type == 'buy' ? 'text-success' : 'text-danger' }}">{{ $order->type }}</td>
        <td>{{ $order->status }}</td>
        <td class="text-right">{{ $order->exchange_rate }}</td>
        <td class="text-right">{{ $order->base_quantity_normalized }}</td>
        <td class="text-right">{{ $order->quote_quantity_normalized }}</td>
        <td><a href="{{ url(route('addresses.show', ['address' => $order->source])) }}">{{ $order->source }}</a></td>
      </tr>
      @endforeach
      @if(! count($orders))
      <tr class="text-center">
        <td colspan="8">No Orders Found</td>
      </tr>
      @endif
    </tbody>
  </table>
</div>
<h3 class="mt-3 mb-3">Order Matches <small class="float-right lead mt-2">{{ count($matches) }} Found</small></h3>
<div class="table-responsive order-matches">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>Date</th>
        <th>Market</th>
        <th>Type</th>
        <th>Status</th>
        <th>Price</th>
        <th>Amount</th>
        <th>Total</th>
        <th>Source</th>
      </tr>
    </thead>
    <tbody>
      @foreach($matches as $match)
      <tr>
        <td><a href="{{ url(route('orders.show', ['order' => $match->order->tx_hash])) }}">{{ $match->order->block->mined_at }}</a></td>
        <td><a href="{{ url(route('markets.show', ['market' => $match->order->market->slug])) }}">{{ $match->order->market->name }}</a></td>
        <td class="{{ $match->order->type == 'buy' ? 'text-success' : 'text-danger' }}">{{ $match->order->type }}</td>
        <td>{{ $match->status }}</td>
        <td class="text-right">{{ $match->order->exchange_rate }}</td>
        <td class="text-right">{{ $match->base_quantity_normalized }}</td>
        <td class="text-right">{{ $match->quote_quantity_normalized }}</td>
        <td><a href="{{ url(route('addresses.show', ['slug' => $match->order->source])) }}">{{ $match->order->source }}</a></td>
      </tr>
      @endforeach
      @if(! count($matches))
      <tr class="text-center">
        <td colspan="8">No Matches Found</td>
      </tr>
      @endif
    </tbody>
  </table>
</div>
@endsection