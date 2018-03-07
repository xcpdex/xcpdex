@extends('layouts.app')

@section('title', 'Counterparty Decentralized Exchange')

@section('content')
<h1 class="mb-3">Counterparty Dex <small class="lead">{{ $assets->total() }} Assets</small></h1>
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>#</th>
        <th>Asset</th>
        <th>Volume <small>USD</small></th>
        <th>Orders</th>
        <th>Matches</th>
      </tr>
    </thead>
    <tbody>
      @foreach($assets as $asset)
      <tr>
        <th><img src="{{ $asset->display_icon_url }}" height="22" /></th>
        <td><a href="{{ url(route('assets.show', ['asset' => $asset->name])) }}">{{ $asset->long_name ? $asset->long_name : $asset->name }}</a></td>
        <td><a href="{{ url(route('assets.show', ['asset' => $asset->name])) }}">${{ number_format($asset->volume_total_usd) }}</a></td>
        <td><a href="{{ url(route('assets.show', ['asset' => $asset->name])) }}">{{ number_format($asset->orders_total) }}</a></td>
        <td><a href="{{ url(route('assets.show', ['asset' => $asset->name])) }}">{{ number_format($asset->order_matches_total) }}</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
{!! $assets->links('pagination::bootstrap-4') !!}
@endsection
