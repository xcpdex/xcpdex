<template>
<div>
<h1 class="mb-3">Assets <small class="lead">{{ total }} Found</small></h1>
<div class="table-responsive asset-markets mb-3" infinite-wrapper>
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>Name <small>USD</small> <a :href="'https://xcpdex.com/assets?filter=' + filter + '&order_by=name&direction=' + reverse('name')"><i class="fa fa-sort" :class="order_by === 'name' && direction === 'desc' ? 'text-success' : order_by === 'name' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
        <th>Volume <small>ALL</small> <a :href="'https://xcpdex.com/assets?filter=' + filter + '&order_by=volume_total_usd&direction=' + reverse('volume_total_usd')"><i class="fa fa-sort" :class="order_by === 'volume_total_usd' && direction === 'desc' ? 'text-success' : order_by === 'volume_total_usd' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
        <th>Issuance <a :href="'https://xcpdex.com/assets?filter=' + filter + '&order_by=issuance&direction=' + reverse('issuance')"><i class="fa fa-sort" :class="order_by === 'issuance' && direction === 'desc' ? 'text-success' : order_by === 'issuance' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
        <th>Orders <a :href="'https://xcpdex.com/assets?filter=' + filter + '&order_by=orders_total&direction=' + reverse('orders_total')"><i class="fa fa-sort" :class="order_by === 'orders_total' ? 'text-success' && direction === 'desc' : order_by === 'orders_total' ? 'text-danger' && direction === 'asc' : ''"></i></a></th>
        <th>Matches <a :href="'https://xcpdex.com/assets?filter=' + filter + '&order_by=order_matches_total&direction=' + reverse('order_matches_total')"><i class="fa fa-sort" :class="order_by === 'order_matches_total' && direction === 'desc' ? 'text-success' : order_by === 'order_matches_total' && direction === 'asc' ? 'text-danger' : ''"></i></a></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="asset in assets">
        <td><a :href="'https://xcpdex.com/asset/' + asset.slug"><img :src="asset.display_icon_url" height="22" :alt="asset.slug" /></a> <a :href="'https://xcpdex.com/asset/' + asset.slug">{{ asset.slug }}</a></td>
        <td class="text-right">{{ asset.volume_total_usd | formatDollars }}</td>
        <td class="text-right">{{ asset.issuance_normalized | formatted }}</td>
        <td class="text-right">{{ asset.orders_total }}</td>
        <td class="text-right">{{ asset.order_matches_total }}</td>
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

  Vue.filter("formatLarge", function (value) {
    return numeral(value).format('$0,0'); // displaying other groupings/separators is possible, look at the docs
  });

  Vue.filter("formatDollars", function (value) {
    return numeral(value).format('$0,0.00'); // displaying other groupings/separators is possible, look at the docs
  });

  export default {

    props: ['filter', 'order_by', 'direction'],

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
        axios.get('/api/assets?page=' + this.page + '&filter=' + this.filter + '&order_by=' + this.order_by + '&direction=' + this.direction)
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