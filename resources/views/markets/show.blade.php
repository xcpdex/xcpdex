@extends('layouts.app')

@section('title', "{$market->name} Price Chart")

@section('header')
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>

<script>
$.getJSON('http://xcpdex.com/api/charts/{{ $market->slug }}', function (data) {
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

@section('content')

  <div class="row">
    <div class="col-md-7">
      <h1>{{ $market->baseAsset->name }} <small class="lead d-none d-sm-inline">(<a href="{{ url(route('assets.show', ['asset' => $market->baseAsset->name])) }}">{{ $market->baseAsset->name }}</a>/<a href="{{ url(route('assets.show', ['asset' => $market->quoteAsset->name])) }}">{{ $market->quoteAsset->name }}</a>)</small></h1>
      <p class="lead"><span class="d-none d-sm-inline">{{ $market->baseAsset->name }}</span> Price Chart, Order Book and Match History.</p>
    </div>
    <div class="col-md-5">
      <div class="table-responsive">
        <table class="table table-sm table-bordered text-center">
          <tbody>
            <tr>
              <td>Last Price: <br><b>{{ $last_match ? $last_match->orderMatch->exchange_rate : '----------' }}</b></td>
              <td>Change: <br> <b class="text-success">0.00%</b></td>
              <td>24h Price: <br> <b>0.00170000</b></td>
            </tr>
            <tr class="active">
              <td colspan="3"><div title="{{ $market->baseAsset->locked ? 'Locked' : 'Not Locked' }}">Issuance: <b>{{ $market->baseAsset->issuance_normalized }} {{ $market->baseAsset->name }}</b></div></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if($last_order && $last_order->block->block_time < \Carbon\Carbon::now()->subMonths(3))
  <div class="alert alert-{{ $last_order->block->block_time < \Carbon\Carbon::now()->subMonths(12) ? 'danger' : 'warning' }}" role="alert">
    <strong>Alert:</strong> This trading pair has been inactive for {{ str_replace(' ago', '', $last_order->block->block_time->diffForHumans()) }}.
  </div>
  @endif

  <div id="highchart" style="height: 450px; min-width: 310px"></div>

  <div class="row">
    <div class="col-md-6">
      <h2 class="mt-3 mb-3">Buy Orders</h2>
      <order-book market="{{ $market->slug }}" side="buy"></order-book>
    </div>
    <div class="col-md-6">
      <h2 class="mt-3 mb-3">Sell Orders</h2>
      <order-book market="{{ $market->slug }}" side="sell"></order-book>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <h3 class="mt-3 mb-3">Order Matches <small class="float-right lead mt-2">{{ $market->orderMatches->count() }} Total</small></h3>
      <order-matches market="{{ $market->slug }}"></order-matches>
    </div>
  </div>

@endsection
