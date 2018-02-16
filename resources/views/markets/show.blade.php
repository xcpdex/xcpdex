@extends('layouts.app')

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
                text: 'Price (XCP)'
            },
            height: '60%',
            lineWidth: 2
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
            selected: 7,
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
            name: 'Price (XCP)',
            data: price,
            tooltip: {
                valueDecimals: 8
            }
        }, {
            type: 'column',
            name: 'Volume (XCP)',
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

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
      <h1 class="h2">{{ $market->name }} <small class="lead">Price History</small></h1>
    </div>

    <div id="highchart" style="height: 500px; min-width: 310px"></div>

    <div class="row">
        <div class="col">
            <h2 class="mt-3 mb-3">Buy Orders</h2>
            <order-book market="{{ $market->slug }}" side="buy"></order-book>
        </div>
        <div class="col">
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

    <hr class="mt-4 mb-4" />

@endsection
