@extends('layouts.app')

@section('content')
<h1 class="mb-3">Order Details <small class="lead">#{{ $order->tx_index }}</small></h1>
<div class="table-responsive">
  <table class="table table-sm table-bordered">
    <tbody>
      <tr>
        <th>TX Hash</th>
        <td>{{ $order->tx_hash }}</td>
      </tr>
      <tr>
        <th>Type</th>
        <td class="{{ $order->type == 'buy' ? 'text-success' : 'text-danger' }}">{{ strtoupper($order->type) }}</td>
      </tr>
      <tr>
        <th>Status</th>
        <td>{{ $order->status }}</td>
      </tr>
      <tr>
        <th>Market</th>
        <td><a href="{{ url(route('markets.show', ['market' => $order->market->slug])) }}">{{ $order->market->name }}</a></td>
      </tr>
      <tr>
        <th>Source</th>
        <td>{{ $order->source }}</td>
      </tr>
      <tr>
        <th>Block Index</th>
        <td>{{ $order->block_index }}</td>
      </tr>
      <tr>
        <th>Expire Index</th>
        <td>{{ $order->expire_index }}</td>
      </tr>
      <tr>
        <th>Base Remaining</th>
        <td>{{ $order->base_remaining_normalized }}</td>
      </tr>
      <tr>
        <th>Base Quantity</th>
        <td>{{ $order->base_quantity_normalized }}</td>
      </tr>
      <tr>
        <th>Quote Remaining</th>
        <td>{{ $order->quote_remaining_normalized }}</td>
      </tr>
      <tr>
        <th>Quote Quantity</th>
        <td>{{ $order->quote_quantity_normalized }}</td>
      </tr>
      <tr>
        <th>Exchange Rate</th>
        <td>{{ $order->exchange_rate }}</td>
      </tr>
      <tr>
        <th>Duration</th>
        <td>{{ $order->duration }}</td>
      </tr>
      <tr>
        <th>Summary</th>
        <td>{{ strtoupper($order->type) }} {{ $order->base_quantity_normalized }} {{ $order->market->baseAsset->name }} @ {{ $order->exchange_rate }} {{ $order->market->quoteAsset->name }} ({{ $order->quote_quantity_normalized }} {{ $order->market->quoteAsset->name }})</td>
      </tr>
    </tbody>
  </table>
</div>
<h2>Order Matches</h2>
<div class="table-responsive order-matches">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>Date</th>
        <th>Type</th>
        <th>Status</th>
        <th>Price ({{ $order->market->quoteAsset->name }})</th>
        <th>Amount ({{ $order->market->baseAsset->name }})</th>
        <th>Total ({{ $order->market->quoteAsset->name }})</th>
        <th>Source</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->orderMatches()->orderBy('tx_index', 'desc')->get() as $match)
      <tr>
        <td><a href="{{ url(route('orders.show', ['order' => $match->orderMatch->tx_hash])) }}">{{ $match->orderMatch->block->mined_at }}</a></td>
        <td class="{{ $match->orderMatch->type === 'buy' ? 'text-success' : 'text-danger' }}">{{ $match->orderMatch->type }}</td>
        <td>{{ $match->status }}</td>
        <td class="text-right">{{ $match->orderMatch->exchange_rate }}</td>
        <td class="text-right">{{ $match->base_quantity_normalized }}</td>
        <td class="text-right">{{ $match->quote_quantity_normalized }}</td>
        <td><a href="{{ url(route('addresses.show', ['address' => $match->orderMatch->source])) }}">{{ $match->orderMatch->source }}</a></td>
      </tr>
      @endforeach
      @if($order->orderMatchesReverse->count())
      @foreach($order->orderMatchesReverse()->orderBy('tx_index', 'desc')->get() as $match)
      <tr>
        <td><a href="{{ url(route('orders.show', ['order' => $match->order->tx_hash])) }}">{{ $match->order->block->mined_at }}</a></td>
        <td class="{{ $match->orderMatch->type === 'buy' ? 'text-success' : 'text-danger' }}">{{ $match->orderMatch->type }}</td>
        <td>{{ $match->status }}</td>
        <td class="text-right">{{ $match->order->exchange_rate }}</td>
        <td class="text-right">{{ $match->base_quantity_normalized }}</td>
        <td class="text-right">{{ $match->quote_quantity_normalized }}</td>
        <td><a href="{{ url(route('addresses.show', ['address' => $match->order->source])) }}">{{ $match->order->source }}</a></td>
      </tr>
      @endforeach
      @endif
    </tbody>
  </table>
</div>
@endsection
