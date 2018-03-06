@extends('layouts.app')

@section('title', 'Markets')

@section('content')
<h1 class="mb-3">Markets <small class="lead">{{ $markets->total() }} Found</small></h1>
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>Asset Pair</th>
        <th>Market Cap</th>
        <th>Price</th>
        <th>Volume (30D)</th>
        <th>Issuance</th>
        <th>Open Orders</th>
        <th>Orders</th>
        <th>Matches</th>
      </tr>
    </thead>
    <tbody>
      @foreach($markets as $market)
      <tr>
        <td><a href="{{ url(route('markets.show', ['market' => $market->slug])) }}">{{ $market->name }}</a></td>
        <td class="text-right">${{ number_format($market->quote_market_cap_usd) }}</td>
        <td class="text-right">${{ number_format($market->last_price_usd) ? $market->last_price_usd > 1000 ? number_format($market->last_price_usd) : number_format($market->last_price_usd, 2) : $market->last_price_usd }}</td>
        <td class="text-right">${{ number_format($market->quote_volume_usd) }}</td>
        <td class="text-right">{{ number_format($market->baseAsset->issuance_normalized) }} {{ $market->baseAsset->name }}</td>
        <td class="text-right"><a href="{{ url(route('markets.show', ['market' => $market->slug])) }}">{{ $market->open_orders_count }}</a></td>
        <td class="text-right"><a href="{{ url(route('markets.show', ['market' => $market->slug])) }}">{{ $market->orders_count }}</a></td>
        <td class="text-right"><a href="{{ url(route('markets.show', ['market' => $market->slug])) }}">{{ $market->order_matches_count }}</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
{!! $markets->links('pagination::bootstrap-4') !!}
@endsection
