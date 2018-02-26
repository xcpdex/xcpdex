@extends('layouts.app')

@section('content')
<h1>{{ $slug }}</h1>
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
      </tr>
    </thead>
    <tbody>
      @foreach($orders as $order)
      <tr>
        <td><a href="{{ url(route('orders.show', ['order' => $order->tx_hash])) }}">{{ $order->block->block_time }}</a></td>
        <td><a href="{{ url(route('markets.show', ['market' => $order->market->slug])) }}">{{ $order->market->name }}</a></td>
        <td class="{{ $order->type == 'buy' ? 'text-success' : 'text-danger' }}">{{ $order->type }}</td>
        <td>{{ $order->status }}</td>
        <td class="text-right">{{ $order->exchange_rate }}</td>
        <td class="text-right">{{ $order->base_quantity_normalized }}</td>
        <td class="text-right">{{ $order->quote_quantity_normalized }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
