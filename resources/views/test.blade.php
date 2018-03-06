@extends('layouts.app')

@section('header')
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>

<script>
$.getJSON('https://xcpdex.com/api/dex/volume', function (data) {
    var volume = [],
        dataLength = data.length,

        i = 0;

    for (i; i < dataLength; i += 1) {
        volume.push([
            data[i][0], // the date
            data[i][1] // volume
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

        series: [ {
            type: 'column',
            name: 'Volume (USD)',
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
  <div id="highchart" style="height: 450px; min-width: 310px"></div>
@endsection