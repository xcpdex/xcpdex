<template>
<div class="table-responsive order-matches">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>Date</th>
        <th>Type</th>
        <th>Price</th>
        <th>Amount</th>
        <th>Total</th>
        <th>Source</th>
        <th>Match</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="match in matches">
        <td><a :href="'http://xcpdex.com/tx/' + match.order_match.tx_hash">{{ match.order_match.block.block_time }}</a></td>
        <td :class="match.order_match.type == 'buy' ? 'text-success' : 'text-danger'">{{ match.order_match.type }}</td>
        <td class="text-right">{{ match.order.exchange_rate }}</td>
        <td class="text-right">{{ match.base_quantity_normalized }}</td>
        <td class="text-right">{{ match.quote_quantity_normalized }}</td>
        <td><a :href="'http://xcpdex.com/address/' + match.order_match.source">{{ match.order_match.source }}</a></td>
        <td><a :href="'http://xcpdex.com/address/' + match.order.source">{{ match.order.source }}</a></td>
      </tr>
      <infinite-loading @infinite="infiniteHandler">
        <span slot="no-more"></span>
        <span slot="no-results"></span>
      </infinite-loading>
    </tbody>
  </table>
</div>
</template>

<script>
  import InfiniteLoading from 'vue-infinite-loading';

  export default {

    props: ['market'],

    data () {
      return {
        matches: [],
      }
    },

    methods: {
      infiniteHandler($state) {
        fetch('/api/matches/' + this.market + '?page=' + this.matches.length / 50 + 1).then((response) => {
          return response.json().then((json) => {
            if (json.data.length) {
              this.matches = this.matches.concat(json.data);
              $state.loaded();
              if (this.matches.length === json.total) {
                $state.complete();
              }
            } else {
              $state.complete();
            }
          })
        });
      },
    },

    components: {
      InfiniteLoading,
    },

  }
</script>
