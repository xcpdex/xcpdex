@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
      <h1 class="h2">{{ $slug }}</h1>
    </div>
<div class="table-responsive">
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
