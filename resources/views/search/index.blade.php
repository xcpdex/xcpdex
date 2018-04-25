@extends('layouts.app')

@section('title', 'Search')

@section('header')
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
@endsection

@section('content')
<h1 class="mb-3">Search <small class="lead">{{ number_format($results->total()) }} {{ str_plural('Result', $results->total()) }} for "{{ $request->q }}"</small></h1>
<p class="mt-3">Showing Top 50 Results</p>
<div class="table-responsive table-dex">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th scope="col">Asset</th>
        <th scope="col">Volume <small>USD</small></th>
        <th>Issuance</th>
        <th scope="col">Markets</th>
        <th scope="col">Orders</th>
        <th scope="col">Matches</th>
      </tr>
    </thead>
    <tbody>
      @foreach($results as $asset)
      <tr>
        <td><a href="{{ url(route('assets.show', ['asset' => $asset->slug])) }}"><img src="{{ $asset->display_icon_url }}" height="22" /> {{ $asset->long_name ? $asset->long_name : $asset->name }}</a></td>
        <td class="text-right"><a href="{{ url(route('assets.show', ['asset' => $asset->slug])) }}">${{ number_format($asset->volume_total_usd) }}</a></td>
        <td class="text-right"><a href="{{ url(route('assets.show', ['asset' => $asset->slug])) }}">{{ isset($asset->meta['burned']) ? number_format($asset->issuance_normalized - $asset->meta['burned']) : number_format($asset->issuance_normalized) }}</a></td>
        <td class="text-right"><a href="{{ url(route('assets.show', ['asset' => $asset->slug])) }}">{{ $asset->base_markets_count + $asset->quote_markets_count }}</a></td>
        <td class="text-right"><a href="{{ url(route('assets.show', ['asset' => $asset->slug])) }}">{{ number_format($asset->orders_total) }}</a></td>
        <td class="text-right"><a href="{{ url(route('assets.show', ['asset' => $asset->slug])) }}">{{ number_format($asset->order_matches_total) }}</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection