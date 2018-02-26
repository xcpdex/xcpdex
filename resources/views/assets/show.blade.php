@extends('layouts.app')

@section('title', $asset->long_name ? $asset->long_name : $asset->name)

@section('sidebar')
@if(isset($asset->meta['template']))
  @if('rare-pepe' == $asset->meta['template'])
  <img src="{{ $asset->meta['image_url'] }}" alt="{{ $asset->name }}" width="100%" height="auto" class="mt-3" role="button" data-toggle="modal" data-target="#cardModal" />
  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
    <span>Certified Rare</span>
  </h6>
  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="{{ $asset->meta['asset_url'] }}" class="nav-link" target="_blank">
        Series {{ $asset->meta['series'] }}
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ $asset->meta['asset_url'] }}" class="nav-link" target="_blank">
        Card {{ $asset->meta['number'] }}
      </a>
    </li>
  </ul>
  @elseif('age-of-chains' == $asset->meta['template']) 
  <img src="{{ $asset->meta['image_url'] }}" alt="{{ $asset->name }}" width="100%" height="auto" class="mt-3" role="button" data-toggle="modal" data-target="#cardModal" />
  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
    <span>Age of Chains</span>
  </h6>
  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="{{ $asset->meta['asset_url'] }}" class="nav-link" target="_blank">
        Series {{ $asset->meta['series'] }}
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ $asset->meta['asset_url'] }}" class="nav-link" target="_blank">
        Card {{ $asset->meta['number'] }}
      </a>
    </li>
  </ul>
  @elseif('age-of-rust' == $asset->meta['template']) 
  <img src="{{ $asset->meta['image_url'] }}" alt="{{ $asset->name }}" width="100%" height="auto" class="mt-3" role="button" data-toggle="modal" data-target="#cardModal" />
  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
    <span>Age of Rust</span>
  </h6>
  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="{{ $asset->meta['asset_url'] }}" class="nav-link" target="_blank">
        Card {{ $asset->meta['number'] }}
      </a>
    </li>
  </ul>
  @elseif('spells-of-genesis' == $asset->meta['template']) 
  <img src="{{ $asset->meta['image_url'] }}" alt="{{ $asset->name }}" width="100%" height="auto" class="mt-3" role="button" data-toggle="modal" data-target="#cardModal" />
  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
    <span>Spells of Genesis</span>
  </h6>
  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="{{ $asset->meta['asset_url'] }}" class="nav-link" target="_blank">
        Element: {{ ucfirst($asset->meta['series']) }}
      </a>
      <a href="{{ $asset->meta['asset_url'] }}" class="nav-link" target="_blank">
        Rarity: {{ ucfirst($asset->meta['number']) }}
      </a>
    </li>
  </ul>
  @elseif('penisium' == $asset->meta['template']) 
  <img src="{{ $asset->meta['image_url'] }}" alt="{{ $asset->name }}" width="100%" height="auto" class="mt-3" role="button" data-toggle="modal" data-target="#cardModal" />
  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
    <span>Penisium</span>
  </h6>
  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="{{ $asset->meta['asset_url'] }}" class="nav-link" target="_blank">
        {{ $asset->name }}
      </a>
    </li>
  </ul>
  @endif

  <div class="modal fade" id="cardModal" tabindex="-1" role="dialog" aria-labelledby="cardModalLabel" aria-hidden="true">
    <div class="modal-dialog{{ 'age-of-chains' == $asset->meta['template'] ? ' modal-lg' : '' }}" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <img src="{{ $asset->meta['image_url'] }}" width="100%" height="auto" />
        </div>
      </div>
    </div>
  </div>

@endif
@endsection

@section('content')

  <div class="row">
    <div class="col-md-7">
      <h1>{{ $asset->long_name ? $asset->long_name : $asset->name }}</h1>
      <p class="lead">{{ strip_tags($asset->description) }}</p>
    </div>
    <div class="col-md-5">
      <div class="table-responsive">
        <table class="table table-sm table-bordered text-center">
          <tbody>
            <tr>
              <td><img src="{{ isset($asset->meta['icon_url']) ? $asset->meta['icon_url'] : asset('/img/token.png')}}" height="48" /></td>
              <td>Volume <small>24H</small> <br /><b>{{ $asset->divisible ? fromSatoshi($daily_volume) : $daily_volume }}</b></td>
              <td>Volume <small>30D</small> <br /><b>{{ $asset->divisible ? fromSatoshi($month_volume) : $month_volume }}</b></td>
            </tr>
            <tr class="bg-light">
              @if(isset($asset->meta['burned']) && $asset->meta['burned'])
              <td colspan="3">Supply: <b>{{ $asset->divisible ? $asset->issuance_normalized - $asset->meta['burned'] : number_format($asset->issuance_normalized - $asset->meta['burned']) }}</b> &nbsp; <small>Burned: <b>{{ $asset->divisible ? $asset->meta['burned'] : number_format($asset->meta['burned']) }}</b></small></td>
              @else
              <td colspan="3">Supply: <b>{{ $asset->divisible ? $asset->issuance_normalized : number_format($asset->issuance_normalized) }}</b></td>
              @endif
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

@if($active_markets->count())

<h2 class="mb-3">Active Markets <small class="float-right lead mt-2">{{ $active_markets->total() }} Total</small></h2>

<div class="table-responsive order-matches mb-3">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>Market</th>
        <th>Market Cap <small>USD</small></th>
        <th>Price <small>USD</small></th>

      </tr>
    </thead>
    <tbody>
      @foreach($active_markets as $market)
      <tr>
        <td><a href="{{ url(route('markets.show', ['market' => $market->slug])) }}">{{ $market->name }}</a></td>
        <td class="text-right">${{ isset($market->baseAsset->meta['burned']) ? number_format((($market->baseAsset->issuance_normalized - $market->baseAsset->meta['burned'])) * $market->lastMatch->order->exchange_rate_usd) : number_format($market->baseAsset->issuance_normalized * $market->lastMatch->order->exchange_rate_usd) }}</td>
        <td class="text-right">${{ number_format($market->lastMatch->order->exchange_rate_usd, 2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endif

@if($inactive_markets->count())

<h2 class="mb-3">Inactive Markets <small class="float-right lead mt-2">{{ $inactive_markets->total() }} Total</small></h2>

<div class="table-responsive order-matches mb-3">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>Market</th>
        <th>Market Cap <small>USD</small></th>
        <th>Price <small>USD</small></th>

      </tr>
    </thead>
    <tbody>
      @foreach($inactive_markets as $market)
      <tr>
        <td><a href="{{ url(route('markets.show', ['market' => $market->slug])) }}">{{ $market->name }}</a></td>
        <td class="text-right">${{ isset($market->baseAsset->meta['burned']) ? number_format((($market->baseAsset->issuance_normalized - $market->baseAsset->meta['burned'])) * $market->lastMatch->order->exchange_rate_usd) : number_format($market->baseAsset->issuance_normalized * $market->lastMatch->order->exchange_rate_usd) }}</td>
        <td class="text-right">${{ number_format($market->lastMatch->order->exchange_rate_usd, 2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endif

@endsection