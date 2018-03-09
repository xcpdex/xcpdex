<template>
<div>
<h1 class="mb-3">Matches <small class="lead">{{ total }} Found</small></h1>
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
        <th>Match</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="match in matches">
        <td><a :href="'https://xcpdex.com/tx/' + match.tx_hash">{{ match.block.mined_at }}</a></td>
        <td><a :href="'https://xcpdex.com/market/' +  match.market.slug">{{ match.market.name }}</a></td>
        <td :class="match.order.type == 'buy' ? 'text-danger' : 'text-success'">{{ match.order.type == 'buy' ? 'sell' : 'buy' }}</td>
        <td>{{ match.status }}</td>
        <td class="text-right">{{ match.exchange_rate }}</td>
        <td class="text-right">{{ match.base_quantity_normalized }}</td>
        <td class="text-right">{{ match.quote_quantity_normalized }}</td>
        <td><a :href="'https://xcpdex.com/address/' + match.source">{{ match.source }}</a></td>
        <td><a :href="'https://xcpdex.com/address/' + match.order.source">{{ match.order.source }}</a></td>
      </tr>
      <tr v-if="matches.length == 0">
        <td class="text-center" colspan="7">No match found.</td>
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
        matches: [],
        total: 0,
        page: 1,
      }
    },

    methods: {
      infiniteHandler($state) {
        axios.get('/api/matches?page=' + this.page)
        .then(response => {
            var json = response.data
            this.total = json.total
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