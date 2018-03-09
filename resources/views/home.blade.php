@extends('layouts.app')

@section('title', 'Counterparty Decentralized Exchange')

@section('description', 'XCP Dex is a Counterparty Dex Explorer that makes trade data more easily accessible outside the use of existing wallet software and apps.')

@section('content')
<h1 class="mb-3">Counterparty Dex</h1>
<p class="lead">XCPDEX.com is a blockchain explorer for the Counterparty protocol's decentralized exchange. <a href="https://counterparty.io/docs/buy_and_sell_assets_on_the_dex/" target="_blank">Learn More &raquo;</a></p>
<div class="row mt-4">
  <div class="col-6 col-sm-3">
    <div class="card mb-3">
      <div class="card-header">Active Markets</div>
      <div class="card-body">
        <h5 class="card-title">{{ number_format($active_markets) }} <small class="lead"><a href="{{ url(route('markets.index')) }}">view</a></small></h5>
      </div>
    </div>
  </div>
  <div class="col-6 col-sm-3">
    <div class="card mb-3">
      <div class="card-header">Open Orders</div>
      <div class="card-body">
        <h5 class="card-title">{{ number_format($open_orders) }} <small class="lead"><a href="{{ url(route('orders.index')) }}">view</a></small></h5>
      </div>
    </div>
  </div>
  <div class="col-6 col-sm-3">
    <div class="card mb-3">
      <div class="card-header">XCP Price <small>USD</small></div>
      <div class="card-body">
        <h5 class="card-title">${{ number_format($xcp_price, 2) }} <i class="fa fa-caret-{{ $xcp_price > $xcp_price_y ? 'up text-success' : 'down text-danger' }}"></i> <small class="lead">{{ round((($xcp_price / $xcp_price_y) - 1) * 100, 2) }}%</small></h5>
      </div>
    </div>
  </div>
  <div class="col-6 col-sm-3">
    <div class="card mb-3">
      <div class="card-header">XCP Price <small>BTC</small></div>
      <div class="card-body">
        <h5 class="card-title">{{ number_format($xcp_price / $btc_price, 8) }} <i class="fa fa-caret-{{ ($xcp_price / $btc_price) > ($xcp_price_y / $btc_price_y) ? 'up text-success' : 'down text-danger' }}"></i> <small class="lead">{{ round(((($xcp_price / $btc_price)/($xcp_price_y / $btc_price_y)) - 1) * 100, 2) }}%</small></h5>
      </div>
    </div>
  </div>
</div>
<div class="row mt-3">
  <div class="col-6 col-sm-3">
    <div class="card mb-3">
      <div class="card-header">Matches <small>24-Hour</small></div>
      <div class="card-body">
        <h5 class="card-title">{{ number_format($matches) }} <i class="fa fa-caret-{{ $matches > $matches_y ? 'up text-success' : 'down text-danger' }}"></i> <small class="lead">{{ round((($matches / $matches_y) - 1) * 100, 2) }}%</small></h5>
      </div>
    </div>
  </div>
  <div class="col-6 col-sm-3">
    <div class="card mb-3">
      <div class="card-header">Orders <small>24-Hour</small></div>
      <div class="card-body">
        <h5 class="card-title">{{ number_format($orders) }} <i class="fa fa-caret-{{ $orders > $orders_y ? 'up text-success' : 'down text-danger' }}"></i> <small class="lead">{{ round((($orders / $orders_y) - 1) * 100, 2) }}%</small></h5>
      </div>
    </div>
  </div>
  <div class="col-6 col-sm-3">
    <div class="card mb-3">
      <div class="card-header">Avg. Trade <small>24-Hour</small></div>
      <div class="card-body">
        <h5 class="card-title">${{ number_format($avg_trade, 2) }} <i class="fa fa-caret-{{ $avg_trade > $avg_trade_y ? 'up text-success' : 'down text-danger' }}"></i> <small class="lead">{{ round((($avg_trade / $avg_trade_y) - 1) * 100, 2) }}%</small></h5>
      </div>
    </div>
  </div>
  <div class="col-6 col-sm-3">
    <div class="card mb-3">
      <div class="card-header">Avg. Fee <small>24-Hour</small></div>
      <div class="card-body">
        <h5 class="card-title">{{ fromSatoshi($avg_fee) }} BTC <i class="fa fa-caret-{{ $avg_fee > $avg_fee_y ? 'up text-success' : 'down text-danger' }}"></i> <small class="lead">{{ round((($avg_fee / $avg_fee_y) - 1) * 100, 2) }}%</small></h5>
      </div>
    </div>
  </div>
</div>
<div class="row mt-3">
  <div class="col-6 col-sm-3">
    <div class="card mb-3">
      <div class="card-header">Volume <small>24-Hour</small></div>
      <div class="card-body">
        <h5 class="card-title">${{ number_format($d_volume) }} <i class="fa fa-caret-{{ $d_volume > $y_volume ? 'up text-success' : 'down text-danger' }}"></i> <small class="lead">{{ round((($d_volume / $y_volume) - 1) * 100, 2) }}%</small></h5>
      </div>
    </div>
  </div>
  <div class="col-6 col-sm-3">
    <div class="card mb-3">
      <div class="card-header">Volume <small>7-Day</small></div>
      <div class="card-body">
        <h5 class="card-title">${{ number_format($w_volume) }} <i class="fa fa-caret-{{ $w_volume > $lw_volume ? 'up text-success' : 'down text-danger' }}"></i> <small class="lead">{{ round((($w_volume / $lw_volume) - 1) * 100, 2) }}%</small></h5>
      </div>
    </div>
  </div>
  <div class="col-6 col-sm-3">
    <div class="card mb-3">
      <div class="card-header">Volume <small>30-Day</small></div>
      <div class="card-body">
        <h5 class="card-title">${{ number_format($m_volume) }} <i class="fa fa-caret-{{ $m_volume > $lm_volume ? 'up text-success' : 'down text-danger' }}"></i> <small class="lead">{{ round((($m_volume / $lm_volume) - 1) * 100, 2) }}%</small></h5>
      </div>
    </div>
  </div>
  <div class="col-6 col-sm-3">
    <div class="card mb-3">
      <div class="card-header">Volume <small>All-Time</small></div>
      <div class="card-body">
        <h5 class="card-title">${{ number_format($a_volume) }}</h5>
      </div>
    </div>
  </div>
</div>
<br />
@endsection