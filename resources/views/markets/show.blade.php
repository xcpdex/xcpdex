@extends('layouts.app')

@section('title', $market->name)

@section('header')
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>
<script src="https://code.highcharts.com/stock/modules/drag-panes.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>

<script>

$.getJSON('https://xcpdex.com/api/ohlc/{{ $market->slug }}', function (data) {

    // split the data set into ohlc and volume
    var ohlc = [],
        volume = [],
        dataLength = data.length,
        // set the allowed units for data grouping
        groupingUnits = [[
            'minute',                         // unit name
            [10]                             // allowed multiples
        ], [
            'hour',                         // unit name
            [1]                             // allowed multiples
        ], [
            'day',                         // unit name
            [1]                             // allowed multiples
        ], [
            'week',                         // unit name
            [1]                             // allowed multiples
        ], [
            'month',
            [1, 2, 3, 4, 6]
        ]],

        i = 0;

    for (i; i < dataLength; i += 1) {
        ohlc.push([
            data[i][0], // the date
            data[i][1], // open
            data[i][2], // high
            data[i][3], // low
            data[i][4] // close
        ]);

        volume.push([
            data[i][0], // the date
            data[i][5] // the volume
        ]);
    }


    // create the chart
    Highcharts.stockChart('ohlc', {

        chart: {
            borderColor: '#DFD7CA',
            borderWidth: 1,
        },

        rangeSelector: {
            selected: 1
        },

        yAxis: [{
            labels: {
                align: 'right',
                x: -3
            },
            title: {
                text: 'OHLC'
            },
            min: 0,
            height: '60%',
            lineWidth: 2,
            resize: {
                enabled: true
            }
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

        tooltip: {
            split: true
        },

        rangeSelector: {
            selected: {{ null !== $last_match && $last_match->orderMatch->block->mined_at < \Carbon\Carbon::now()->subMonths(12) ? 7 : 5 }},
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
            type: 'candlestick',
            name: '{{ $market->baseAsset->name }}',
            data: ohlc,
            dataGrouping: {
                units: groupingUnits
            }
        }, {
            type: 'column',
            name: 'Volume ({{ $market->quoteAsset->name }})',
            data: volume,
            yAxis: 1,
            dataGrouping: {
                units: groupingUnits
            }
        }]
    });
});
</script>
@endsection

@section('sidebar')
@if(isset($market->baseAsset->meta['template']))
  @include('partials.' . $market->baseAsset->meta['template'], ['asset' => $market->baseAsset])
  @include('partials.image-modal', ['asset' => $market->baseAsset])
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
              <td>Last Price <small>{{ $market->quoteAsset->name }}</small><br><b>{{ $last_match ? $last_match->order->exchange_rate : '----------' }}</b></td>
              <td>Last Trade <br><b>{{ $last_match ? $last_match->orderMatch->block->mined_at->toDateString() : '----------' }}</b></td>
              <td>Est. Price <small>USD</small><br><b>{{ $last_match ? $last_match->order->exchange_rate_usd > 1 ? number_format($market->last_price_usd, 2) : $market->last_price_usd : '----------' }}</b></td>
            </tr>
            @if($last_match)
            <tr class="bg-light">
              <td colspan="3">Market Cap: <b>{{ number_format($market->quote_market_cap) }} <small>{{ $market->quoteAsset->name }}</small></b> / <b>{{ number_format($market->quote_market_cap_usd) }} <small>USD</small></b></td>
            </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if($last_order && $last_order->block->mined_at < \Carbon\Carbon::now()->subMonths(3))
  <div class="alert alert-{{ $last_order->block->mined_at < \Carbon\Carbon::now()->subMonths(12) ? 'danger' : 'warning' }}" role="alert">
    <strong>Alert:</strong> No orders for the last {{ str_replace(' ago', '', $last_order->block->mined_at->diffForHumans()) }}.
  </div>
  @elseif($last_match && $last_match->block->mined_at < \Carbon\Carbon::now()->subMonths(3))
  <div class="alert alert-{{ $last_order->block->mined_at < \Carbon\Carbon::now()->subMonths(12) ? 'danger' : 'warning' }}" role="alert">
    <strong>Alert:</strong> No order matches for the last {{ str_replace(' ago', '', $last_match->block->mined_at->diffForHumans()) }}.
  </div>
  @endif

  <div id="ohlc" style="height: 450px; min-width: 310px"></div>

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
