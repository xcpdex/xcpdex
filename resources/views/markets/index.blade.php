@extends('layouts.app')

@section('title', 'Markets')

@section('content')
<h1>Markets <small class="lead">{{ $markets->total() }} Found</small></h1>
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>#</th>
        <th>Market</th>
        <th>Orders</th>
        <th>Matches</th>
      </tr>
    </thead>
    <tbody>
      @foreach($markets as $market)
      <tr>
        <th>{{ $loop->index }}</th>
        <td><a href="{{ url(route('markets.show', ['market' => $market->slug])) }}">{{ $market->name }}</a></td>
        <td><a href="{{ url(route('markets.show', ['market' => $market->slug])) }}">{{ $market->orders_count }}</a></td>
        <td><a href="{{ url(route('markets.show', ['market' => $market->slug])) }}">{{ $market->order_matches_count }}</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
