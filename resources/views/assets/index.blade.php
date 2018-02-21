@extends('layouts.app')

@section('title', 'Assets')

@section('content')
<h1>Assets <small class="lead">{{ $assets->total() }} Found</small></h1>
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>#</th>
        <th>Asset</th>
        <th>Orders</th>
        <th>Base Markets</th>
        <th>Quote Markets</th>
      </tr>
    </thead>
    <tbody>
      @foreach($assets as $asset)
      <tr>
        <th>{{ $loop->index }}</th>
        <td><a href="{{ url(route('assets.show', ['asset' => $asset->name])) }}">{{ $asset->long_name ? $asset->long_name : $asset->name }}</a></td>
        <td><a href="{{ url(route('assets.show', ['asset' => $asset->name])) }}">{{ $asset->orders_count }}</a></td>
        <td><a href="{{ url(route('assets.show', ['asset' => $asset->name])) }}">{{ $asset->base_markets_count }}</a></td>
        <td><a href="{{ url(route('assets.show', ['asset' => $asset->name])) }}">{{ $asset->quote_markets_count }}</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection