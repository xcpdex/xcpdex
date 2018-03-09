<template>
<div>
<h1 class="mb-3">{{ name }} <small class="lead">{{ total }} Found</small></h1>
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a v-if="filter === 'index'" class="nav-link active" :href="'https://xcpdex.com/project/' + project + '?filter=index&order_by=' + order_by">All <small class="badge badge-secondary ml-1">{{ total }}</small></a>
    <a v-else class="nav-link" :href="'https://xcpdex.com/project/' + project + '?filter=index&order_by=' + order_by">All</a>
  </li>
</ul>
<div class="table-responsive asset-markets mb-3" infinite-wrapper>
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>Card Art</th>
        <th>Asset <a :href="'https://xcpdex.com/project/' + project + '?filter=' + filter + '&order_by=name&direction=' + reverse('name')"><i class="fa fa-sort" :class="order_by === 'name' && direction === 'desc' ? 'text-success' : order_by === 'name' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
        <th>Issuance</th>
        <th>Volume <small>ALL</small> <a :href="'https://xcpdex.com/project/' + project + '?filter=' + filter + '&order_by=volume_total_usd&direction=' + reverse('volume_total_usd')"><i class="fa fa-sort" :class="order_by === 'volume_total_usd' && direction === 'desc' ? 'text-success' : order_by === 'volume_total_usd' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
        <th>Orders <a :href="'https://xcpdex.com/project/' + project + '?filter=' + filter + '&order_by=orders_total&direction=' + reverse('orders_total')"><i class="fa fa-sort" :class="order_by === 'orders_total' ? 'text-success' && direction === 'desc' : order_by === 'orders_total' ? 'text-danger' && direction === 'asc' : ''"></i></a></th>
        <th>Matches <a :href="'https://xcpdex.com/project/' + project + '?filter=' + filter + '&order_by=order_matches_total&direction=' + reverse('order_matches_total')"><i class="fa fa-sort" :class="order_by === 'order_matches_total' && direction === 'desc' ? 'text-success' : order_by === 'order_matches_total' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
      </tr>
    </thead>
    <tbody style="font-size: 16px">
      <tr v-for="asset in assets">
        <td style="vertical-align: middle"><a :href="'https://xcpdex.com/asset/' + asset.name"><img :src="asset.image_url" width="70" :alt="asset.name" /></a></td>
        <td style="vertical-align: middle"><a :href="'https://xcpdex.com/asset/' + asset.name">{{ asset.name }}</a></td>
        <td style="vertical-align: middle">{{ asset.divisible ? asset.issuance_normalized : asset.issuance_normalized | formatted }}</td>
        <td style="vertical-align: middle">{{ asset.volume_total_usd | formatDollars }}</td>
        <td style="vertical-align: middle">{{ asset.orders_total }}</td>
        <td style="vertical-align: middle">{{ asset.order_matches_total }}</td>
      </tr>
      <tr v-if="assets.length == 0">
        <td class="text-center" colspan="5">No assets found.</td>
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

  Vue.filter("formatted", function (value) {
    return numeral(value).format('0,0'); // displaying other groupings/separators is possible, look at the docs
  });

  Vue.filter("formatLarge", function (value) {
    return numeral(value).format('$0,0'); // displaying other groupings/separators is possible, look at the docs
  });

  Vue.filter("formatDollars", function (value) {
    return numeral(value).format('$0,0.00'); // displaying other groupings/separators is possible, look at the docs
  });

  export default {

    props: ['project', 'name', 'filter', 'order_by', 'direction'],

    data () {
      return {
        assets: [],
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
        axios.get('/api/assets/' + this.project + '?page=' + this.page + '&filter=' + this.filter + '&order_by=' + this.order_by + '&direction=' + this.direction)
        .then(response => {
            var json = response.data
            this.page = json.current_page + 1
            this.total = json.total
            if (json.data.length) {
              this.assets = this.assets.concat(json.data)
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