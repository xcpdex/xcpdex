<template>
<div>
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a v-if="filter === 'index'" class="nav-link active" :href="'https://xcpdex.com/asset/' + asset + '?filter=index&order_by=' + order_by">All <small class="badge badge-secondary ml-1">{{ total }}</small></a>
    <a v-else class="nav-link" :href="'https://xcpdex.com/asset/' + asset + '?filter=index&order_by=' + order_by">All</a>
  </li>
  <li class="nav-item">
    <a v-if="filter === 'active'" class="nav-link active" :href="'https://xcpdex.com/asset/' + asset + '?filter=active&order_by=' + order_by">Active <small class="badge badge-secondary ml-1">{{ total }}</small></a>
    <a v-else class="nav-link" :href="'https://xcpdex.com/asset/' + asset + '?filter=active&order_by=' + order_by">Active</a>
  </li>
  <li class="nav-item">
    <a v-if="filter === 'base'" class="nav-link active" :href="'https://xcpdex.com/asset/' + asset + '?filter=base&order_by=' + order_by">Base <small class="badge badge-secondary ml-1">{{ total }}</small></a>
    <a v-else class="nav-link" :href="'https://xcpdex.com/asset/' + asset + '?filter=base&order_by=' + order_by">Base</a>
  </li>
  <li class="nav-item">
    <a v-if="filter === 'quote'" class="nav-link active" :href="'https://xcpdex.com/asset/' + asset + '?filter=quote&order_by=' + order_by">Quote <small class="badge badge-secondary ml-1">{{ total }}</small></a>
    <a v-else class="nav-link" :href="'https://xcpdex.com/asset/' + asset + '?filter=quote&order_by=' + order_by">Quote</a>
  </li>
</ul>
<div class="table-responsive asset-markets mb-3" infinite-wrapper>
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>Trading Pair <a :href="'https://xcpdex.com/asset/' + asset + '?filter=' + filter + '&order_by=name&direction=' + reverse('name')"><i class="fa fa-sort" :class="order_by === 'name' && direction === 'desc' ? 'text-success' : order_by === 'name' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
        <th>Market Cap <small>USD</small> <a :href="'https://xcpdex.com/asset/' + asset + '?filter=' + filter + '&order_by=quote_market_cap_usd&direction=' + reverse('quote_market_cap_usd')"><i class="fa fa-sort" :class="order_by === 'quote_market_cap_usd' && direction === 'desc' ? 'text-success' : order_by === 'quote_market_cap_usd' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
        <th>Price <small>USD</small> <a :href="'https://xcpdex.com/asset/' + asset + '?filter=' + filter + '&order_by=last_price_usd&direction=' + reverse('last_price_usd')"><i class="fa fa-sort" :class="order_by === 'last_price_usd' && direction === 'desc' ? 'text-success' : order_by === 'last_price_usd' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
        <th>Volume <small>ALL</small> <a :href="'https://xcpdex.com/asset/' + asset + '?filter=' + filter + '&order_by=quote_volume_usd&direction=' + reverse('quote_volume_usd')"><i class="fa fa-sort" :class="order_by === 'quote_volume_usd' && direction === 'desc' ? 'text-success' : order_by === 'quote_volume_usd' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
        <th>Volume <small>30D</small> <a :href="'https://xcpdex.com/asset/' + asset + '?filter=' + filter + '&order_by=quote_volume_usd_month&direction=' + reverse('quote_volume_usd_month')"><i class="fa fa-sort" :class="order_by === 'quote_volume_usd_month' && direction === 'desc' ? 'text-success' : order_by === 'quote_volume_usd_month' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
        <th>Open Orders <a :href="'https://xcpdex.com/asset/' + asset + '?filter=' + filter + '&order_by=open_orders_total&direction=' + reverse('open_orders_total')"><i class="fa fa-sort" :class="order_by === 'open_orders_total' && direction === 'desc' ? 'text-success' : order_by === 'open_orders_total' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
        <th>Orders <a :href="'https://xcpdex.com/asset/' + asset + '?filter=' + filter + '&order_by=orders_total&direction=' + reverse('orders_total')"><i class="fa fa-sort" :class="order_by === 'orders_total' ? 'text-success' && direction === 'desc' : order_by === 'orders_total' ? 'text-danger' && direction === 'asc' : ''"></i></a></th>
        <th>Matches <a :href="'https://xcpdex.com/asset/' + asset + '?filter=' + filter + '&order_by=order_matches_total&direction=' + reverse('order_matches_total')"><i class="fa fa-sort" :class="order_by === 'order_matches_total' && direction === 'desc' ? 'text-success' : order_by === 'order_matches_total' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
        <th>Last Trade <a :href="'https://xcpdex.com/asset/' + asset + '?filter=' + filter + '&order_by=last_traded_at&direction=' + reverse('last_traded_at')"><i class="fa fa-sort" :class="order_by === 'last_traded_at' && direction === 'desc' ? 'text-success' : order_by === 'last_traded_at' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="market in markets">
        <td><a :href="'https://xcpdex.com/market/' + market.slug">{{ market.name }}</a></td>
        <td v-if="market.order_matches_total == 0" class="text-right">---------</td>
        <td v-else class="text-right">{{ market.quote_market_cap_usd | formatLarge }}</td>
        <td v-if="market.order_matches_total == 0" class="text-right">---------</td>
        <td v-else-if="market.last_price_usd > 0 && market.last_price_usd < 0.01" class="text-right">&dollar;{{ market.last_price_usd }}</td>
        <td v-else class="text-right" :title="market.last_price_usd">{{ market.last_price_usd | formatDollars }}</td>
        <td v-if="market.order_matches_total == 0" class="text-right">---------</td>
        <td v-else-if="market.quote_volume_usd > 0 && market.quote_volume_usd < 0.01" class="text-right">&dollar;{{ market.quote_volume_usd }}</td>
        <td v-else-if="market.quote_volume_usd < 1"class="text-right">{{ market.quote_volume_usd | formatDollars }}</td>
        <td v-else class="text-right">{{ market.quote_volume_usd | formatLarge }}</td>
        <td v-if="market.order_matches_total == 0" class="text-right">---------</td>
        <td v-else-if="market.quote_volume_usd_month > 0 && market.quote_volume_usd_month < 0.01" class="text-right">&dollar;{{ market.quote_volume_usd_month }}</td>
        <td v-else-if="market.quote_volume_usd_month < 1"class="text-right">{{ market.quote_volume_usd_month | formatDollars }}</td>
        <td v-else class="text-right">{{ market.quote_volume_usd_month | formatLarge }}</td>
        <td class="text-right">{{ market.open_orders_total }}</td>
        <td class="text-right">{{ market.orders_total }}</td>
        <td class="text-right">{{ market.order_matches_total }}</td>
        <td class="text-right">{{ market.last_traded_at ? market.last_traded_at : '---------' }}</td>
      </tr>
      <tr v-if="markets.length == 0">
        <td class="text-center" colspan="9">No {{ filter === 'index' ? '' : filter }} markets found.</td>
      </tr>
      <infinite-loading force-use-infinite-wrapper="true" @infinite="infiniteHandler">
        <span slot="no-more"></span>
        <span slot="no-results"></span>
      </infinite-loading>
    </tbody>
  </table>
</div>
</div>
</template>

<script>
  import InfiniteLoading from 'vue-infinite-loading';

  var numeral = require("numeral");

  Vue.filter("formatLarge", function (value) {
    return numeral(value).format('$0,0'); // displaying other groupings/separators is possible, look at the docs
  });

  Vue.filter("formatDollars", function (value) {
    return numeral(value).format('$0,0.00'); // displaying other groupings/separators is possible, look at the docs
  });

  export default {

    props: ['asset', 'filter', 'order_by', 'direction'],

    data () {
      return {
        markets: [],
        total: 0,
        page: 1,
      }
    },

    computed: {
      reverse: function () {
        var vm = this;
        return function (sort) {
          return vm.order_by === sort && vm.direction === 'desc' ? 'asc' : 'desc' 
        };
      }
    },

    methods: {
      infiniteHandler($state) {
        axios.get('/api/markets/' + this.asset + '?page=' + this.page + '&filter=' + this.filter + '&order_by=' + this.order_by + '&direction=' + this.direction)
        .then(response => {
            var json = response.data
            this.page = json.current_page + 1
            this.total = json.total
            if (json.data.length) {
              this.markets = this.markets.concat(json.data)
              $state.loaded()
              if (json.current_page == json.last_page) {
                $state.complete()
              }
            } else {
              $state.complete()
            }
        });
      },
    },

    components: {
      InfiniteLoading,
    },

  }
</script>
