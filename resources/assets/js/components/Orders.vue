<template>
<div>
<h1 class="mb-3">Orders <small class="lead">{{ total }} Found</small></h1>
<div class="table-responsive order-matches" infinite-wrapper>
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>Date</th>
        <th>Market</th>
        <th>Type</th>
        <th>Status</th>
        <th>Price</th>
        <th>Amount</th>
        <th>Total</th>
        <th>Source</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="order in orders">
        <td><a :href="'https://xcpdex.com/tx/' + order.tx_hash">{{ order.block.mined_at }}</a></td>
        <td><a :href="'https://xcpdex.com/market/' +  order.market.slug">{{ order.market.name }}</a></td>
        <td :class="order.type == 'buy' ? 'text-success' : 'text-danger'">{{ order.type }}</td>
        <td>{{ order.status }}</td>
        <td class="text-right">{{ order.exchange_rate }}</td>
        <td class="text-right">{{ order.base_quantity_normalized }}</td>
        <td class="text-right">{{ order.quote_quantity_normalized }}</td>
        <td><a :href="'https://xcpdex.com/address/' + order.source">{{ order.source }}</a></td>
      </tr>
      <tr v-if="orders.length == 0">
        <td class="text-center" colspan="7">No order found.</td>
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

  export default {

    data () {
      return {
        orders: [],
        total: 0,
        page: 1,
      }
    },

    methods: {
      infiniteHandler($state) {
        axios.get('/api/orders?page=' + this.page)
        .then(response => {
            var json = response.data
            this.total = json.total
            this.page = json.current_page + 1
            if (json.data.length) {
              this.orders = this.orders.concat(json.data);
              $state.loaded();
              if (json.current_page == json.last_page) {
                $state.complete();
              }
            } else {
              $state.complete();
            }
        });
      },
    },

    components: {
      InfiniteLoading,
    },

  }
</script>