<template>
<div>
<h1>Address <small class="lead">{{ total }} Order(s)</small></h1>
<p>{{ source }}</p>
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

    props: ['source'],

    data () {
      return {
        orders: [],
        total: 0,
        page: 1,
      }
    },

    methods: {
      infiniteHandler($state) {
        axios.get('/api/orders/address/' + this.source + '?page=' + this.page)
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