@extends('layouts.app')

@section('title', 'Matches')

@section('content')
<h1 class="mb-3">Matches <small class="lead">{{ $matches->total() }} Found</small></h1>
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
        <th>Match</th>
      </tr>
    </thead>
    <tbody>
      @foreach($matches as $match)
      <tr>
        <td>{{ $match->orderMatch->block->mined_at }}</td>
        <td><a href="{{ url(route('markets.show', ['market' => $match->orderMatch->market->slug])) }}">{{ $match->orderMatch->market->name }}</a></td>
        <td class="{{ $match->orderMatch->type == 'buy' ? 'text-success' : 'text-danger' }}">{{ $match->orderMatch->type }}</td>
        <td>{{ $match->status }}</td>
        <td class="text-right">{{ $match->orderMatch->exchange_rate }}</td>
        <td class="text-right">{{ $match->base_quantity_normalized }}</td>
        <td class="text-right">{{ $match->quote_quantity_normalized }}</td>
        <td><a href="{{ url(route('addresses.show', ['slug' => $match->orderMatch->source])) }}">{{ $match->orderMatch->source }}</a></td>
        <td><a href="{{ url(route('addresses.show', ['slug' => $match->orderMatch->source])) }}">{{ $match->order->source }}</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
