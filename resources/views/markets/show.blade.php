@extends('layouts.app')

@section('title', $market->name)

@section('header')
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>

<script>
$.getJSON('https://xcpdex.com/api/charts/{{ $market->slug }}', function (data) {
    var price = [],
        volume = [],
        dataLength = data.length,

        i = 0;

    for (i; i < dataLength; i += 1) {
        price.push([
            data[i][0], // the date
            data[i][1], // price
        ]);

        volume.push([
            data[i][0], // the date
            data[i][2] // volume
        ]);
    }

    // Create the chart
    Highcharts.stockChart('highchart', {

        chart: {
            borderColor: '#DFD7CA',
            borderWidth: 1,
        },

        rangeSelector: {
            selected: 1
        },

        title: {
            text: ''
        },

        xAxis: {
          type: 'datetime',
          dateTimeLabelFormats: { // don't display the dummy year
              month: '%e. %b',
              year: '%b'
          },
          title: {
              text: 'Date'
          }
        },

        yAxis: [{
            labels: {
                align: 'right',
                x: -3
            },
            title: {
                text: 'Price ({{ $market->quoteAsset->name }})'
            },
            height: '60%',
            lineWidth: 2,
            min: 0,
        }, {
            labels: {
                align: 'right',
                x: -3
            },
            title: {
                text: 'Volume'
            },
            top: '65%',
            height: '35%',
            offset: 0,
            lineWidth: 2
        }],

        exporting: {
            enabled: false
        },

        tooltip: {
            split: true
        },

        rangeSelector: {
            selected: {{ null !== $last_match && $last_match->orderMatch->block->block_time < \Carbon\Carbon::now()->subMonths(12) ? 7 : 5 }},
            buttons: [{
                type: 'hour',
                count: 6,
                text: '6h'
            },{
                type: 'day',
                count: 1,
                text: '1d'
            }, {
                type: 'week',
                count: 1,
                text: '7d'
            }, {
                type: 'month',
                count: 1,
                text: '1m'
            }, {
                type: 'month',
                count: 3,
                text: '3m'
            }, {
                type: 'year',
                count: 1,
                text: '1y'
            }, {
                type: 'ytd',
                text: 'YTD'
            }, {
                type: 'all',
                text: 'All'
            }]
        },

        series: [{
            type: 'line',
            name: 'Price ({{ $market->quoteAsset->name }})',
            data: price,
            tooltip: {
                valueDecimals: 8
            }
        }, {
            type: 'column',
            name: 'Volume ({{ $market->quoteAsset->name }})',
            data: volume,
            yAxis: 1,
        }],

    lang: {
        noData: "No Trades Found"
    },
    noData: {
        style: {
            fontWeight: 'bold',
            fontSize: '15px',
            color: '#303030'
        }
    }

    });
});
</script>
@endsection

@section('sidebar')
@if(isset($market->baseAsset->meta['template']))
  @if('rare-pepe' == $market->baseAsset->meta['template'])
  <img src="{{ $market->baseAsset->meta['image_url'] }}" alt="{{ $market->baseAsset->name }}" width="100%" height="auto" class="mt-3" role="button" data-toggle="modal" data-target="#cardModal" />
  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
    <span>Certified Rare</span>
  </h6>
  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="http://rarepepedirectory.com/?s={{ $market->baseAsset->name }}" class="nav-link" target="_blank">
        Series {{ $market->baseAsset->meta['series'] }}
      </a>
    </li>
    <li class="nav-item">
      <a href="http://rarepepedirectory.com/?s={{ $market->baseAsset->name }}" class="nav-link" target="_blank">
        Card {{ $market->baseAsset->meta['number'] }}
      </a>
    </li>
  </ul>
  @elseif('age-of-chains' == $market->baseAsset->meta['template']) 
  <img src="{{ $market->baseAsset->meta['image_url'] }}" alt="{{ $market->baseAsset->name }}" width="100%" height="auto" class="mt-3" role="button" data-toggle="modal" data-target="#cardModal" />
  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
    <span>Age of Chains</span>
  </h6>
  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="{{ $market->baseAsset->meta['asset_url'] }}" class="nav-link" target="_blank">
        Series {{ $market->baseAsset->meta['series'] }}
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ $market->baseAsset->meta['asset_url'] }}" class="nav-link" target="_blank">
        Card {{ $market->baseAsset->meta['number'] }}
      </a>
    </li>
  </ul>
  @elseif('age-of-rust' == $market->baseAsset->meta['template']) 
  <img src="{{ $market->baseAsset->meta['image_url'] }}" alt="{{ $market->baseAsset->name }}" width="100%" height="auto" class="mt-3" role="button" data-toggle="modal" data-target="#cardModal" />
  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
    <span>Age of Rust</span>
  </h6>
  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="{{ $market->baseAsset->meta['asset_url'] }}" class="nav-link" target="_blank">
        Card {{ $market->baseAsset->meta['number'] }}
      </a>
    </li>
  </ul>
  @elseif('spells-of-genesis' == $market->baseAsset->meta['template']) 
  <img src="{{ $market->baseAsset->meta['image_url'] }}" alt="{{ $market->baseAsset->name }}" width="100%" height="auto" class="mt-3" role="button" data-toggle="modal" data-target="#cardModal" />
  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
    <span>Spells of Genesis</span>
  </h6>
  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="{{ $market->baseAsset->meta['asset_url'] }}" class="nav-link" target="_blank">
        Element: {{ ucfirst($market->baseAsset->meta['series']) }}
      </a>
      <a href="{{ $market->baseAsset->meta['asset_url'] }}" class="nav-link" target="_blank">
        Rarity: {{ ucfirst($market->baseAsset->meta['number']) }}
      </a>
    </li>
  </ul>
  @elseif('penisium' == $market->baseAsset->meta['template']) 
  <img src="{{ $market->baseAsset->meta['image_url'] }}" alt="{{ $market->baseAsset->name }}" width="100%" height="auto" class="mt-3" role="button" data-toggle="modal" data-target="#cardModal" />
  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
    <span>Penisium</span>
  </h6>
  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="{{ $market->baseAsset->meta['asset_url'] }}" class="nav-link" target="_blank">
        {{ $market->baseAsset->name }}
      </a>
    </li>
  </ul>
  @endif

  <div class="modal fade" id="cardModal" tabindex="-1" role="dialog" aria-labelledby="cardModalLabel" aria-hidden="true">
    <div class="modal-dialog{{ 'age-of-chains' == $market->baseAsset->meta['template'] ? ' modal-lg' : '' }}" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <img src="{{ $market->baseAsset->meta['image_url'] }}" width="100%" height="auto" />
        </div>
      </div>
    </div>
  </div>

@endif
@endsection

@section('content')

  <div class="row">
    <div class="col-md-7">
      <h1>{{ $market->baseAsset->long_name ? $market->baseAsset->long_name : $market->baseAsset->name }} <small class="lead d-none d-sm-inline">(<a href="{{ url(route('assets.show', ['asset' => $market->baseAsset->name])) }}">{{ $market->baseAsset->name }}</a>/<a href="{{ url(route('assets.show', ['asset' => $market->quoteAsset->name])) }}">{{ $market->quoteAsset->name }}</a>)</small></h1>
      <p class="lead"><span class="d-none d-sm-inline">{{ $market->baseAsset->long_name ? $market->baseAsset->long_name : $market->baseAsset->name }}</span> Price Chart, Order Book and Match History.</p>
    </div>
    <div class="col-md-5">
      <div class="table-responsive">
        <table class="table table-sm table-bordered text-center">
          <tbody>
            <tr>
              <td>Last Trade <br><b>{{ $last_match ? $last_match->orderMatch->block->block_time->toDateString() : '' }}</b></td>
              <td>Price <small>{{ $market->quoteAsset->long_name ? $market->quoteAsset->long_name : $market->quoteAsset->name }}</small><br><b>{{ $last_match ? $last_match->order->exchange_rate : '----------' }}</b></td>
              <td>Est. Price <small>USD</small><br><b>{{ $last_match ? $last_match->order->exchange_rate_usd : '----------' }}</b></td>
            </tr>
            @if($last_match)
            <tr class="bg-light">
              <td colspan="3">Market Cap: <b>{{ isset($market->baseAsset->meta['burned']) ? number_format((($market->baseAsset->issuance_normalized - $market->baseAsset->meta['burned'])) * $last_match->order->exchange_rate) : number_format($market->baseAsset->issuance_normalized * $last_match->order->exchange_rate) }} <small>{{ $market->quoteAsset->name }}</small></b> / <b>{{ isset($market->baseAsset->meta['burned']) ? number_format((($market->baseAsset->issuance_normalized - $market->baseAsset->meta['burned'])) * $last_match->order->exchange_rate_usd) : number_format($market->baseAsset->issuance_normalized * $last_match->order->exchange_rate_usd) }} <small>USD</small></b></td>
            </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if($last_order && $last_order->block->block_time < \Carbon\Carbon::now()->subMonths(3))
  <div class="alert alert-{{ $last_order->block->block_time < \Carbon\Carbon::now()->subMonths(12) ? 'danger' : 'warning' }}" role="alert">
    <strong>Alert:</strong> No open orders for more than {{ str_replace(' ago', '', $last_order->block->block_time->diffForHumans()) }}.
  </div>
  @endif

  <div id="highchart" style="height: 450px; min-width: 310px"></div>

  <div class="row">
    <div class="col-md-6">
      <h2 class="mt-3 mb-3">Buy Orders <small class="float-right lead mt-2">{{ $market->openOrders()->whereType('buy')->count() }} Found</small></h2>
      <order-book market="{{ $market->slug }}" side="buy"></order-book>
    </div>
    <div class="col-md-6">
      <h2 class="mt-3 mb-3">Sell Orders <small class="float-right lead mt-2">{{ $market->openOrders()->whereType('sell')->count() }} Found</small></h2>
      <order-book market="{{ $market->slug }}" side="sell"></order-book>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <h3 class="mt-3 mb-3">All Matches <small class="float-right lead mt-2">{{ $market->orderMatches->count() }} Found</small></h3>
      <order-matches market="{{ $market->slug }}"></order-matches>
    </div>
  </div>

@endsection
