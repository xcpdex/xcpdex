<template>
<div class="table-responsive order-book">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>Price</th>
        <th>{{ baseAsset }}</th>
        <th>{{ quoteAsset }}</th>
        <th>Sum ({{ quoteAsset }})</th>
        <th>Source</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(order, index) in orders">
        <td class="text-right" :class="order.type == 'buy' ? 'text-success' : 'text-danger'">{{ order.exchange_rate }}</td>
        <td class="text-right">{{ order.base_remaining_normalized }}</td>
        <td class="text-right">{{ order.quote_remaining_normalized }}</td>
        <td class="text-right">{{ subtotal(index) }}</td>
        <td><a :href="'http://xcpdex.com/address/' + order.source">{{ order.source }}</a></td>
      </tr>
      <tr v-if="orders.length == 0">
        <td class="text-center" colspan="5">No {{ side }} orders found.</td>
      </tr>
    </tbody>
  </table>
</div>
</template>

<script>
  export default {

    props: ['market', 'side'],

    data () {
      return {
        orders: [],
        baseAsset: '',
        quoteAsset: '',
      }
    },

    created: function() {
      let vm = this
      fetch('/api/orders/' + this.market).then((response) => {
        return response.json().then((json) => {
          this.orders = this.orders.concat(this.side == 'buy' ? json.buy_orders : json.sell_orders)
          this.baseAsset = json.base_asset.name
          this.quoteAsset = json.quote_asset.name
        })
      })
    },

    methods: {
      subtotal: function (index) {
        return this.orders.slice(0,index+1).reduce((sum, order) => sum + parseFloat(order.quote_remaining_normalized), 0).toFixed(8)
      }
    },

  }
</script>