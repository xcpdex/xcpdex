<template>
<div class="table-responsive order-matches" infinite-wrapper>
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
        <td><a :href="'https://xcpdex.com/tx/' + match.order_match.tx_hash">{{ match.order_match.block.mined_at }}</a></td>
        <td :class="match.order_match.type == 'buy' ? 'text-success' : 'text-danger'">{{ match.order_match.type }}</td>
        <td class="text-right" :title="match.order.exchange_rate_usd + ' USD'">{{ match.order.exchange_rate }}</td>
        <td class="text-right">{{ match.base_quantity_normalized }}</td>
        <td class="text-right" :title="match.quote_quantity_usd + ' USD'">{{ match.quote_quantity_normalized }}</td>
        <td><a :href="'https://xcpdex.com/address/' + match.order_match.source">{{ match.order_match.source }}</a></td>
        <td><a :href="'https://xcpdex.com/address/' + match.order.source">{{ match.order.source }}</a></td>
      </tr>
      <tr v-if="matches.length == 0">
        <td class="text-center" colspan="7">No order matches found.</td>
      </tr>
      <infinite-loading force-use-infinite-wrapper="true" @infinite="infiniteHandler">
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
        page: 1,
      }
    },

    methods: {
      infiniteHandler($state) {
        axios.get('/api/matches/' + this.market + '?page=' + this.page)
        .then(response => {
            var json = response.data
            this.page = json.current_page + 1
            if (json.data.length) {
              this.matches = this.matches.concat(json.data);
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
