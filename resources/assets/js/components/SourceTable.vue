<template>
<div class="table-responsive order-book">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>Source</th>
        <th>Orders</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="source in sources">
        <td><a :href="'https://xcpdex.com/address/' + source.address">{{ source.address }}</a></td>
        <td class="text-right">{{ source.orders }}</td>
      </tr>
      <tr v-if="sources.length == 0">
        <td class="text-center" colspan="5">No {{ side }}ers found.</td>
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
        sources: [],
      }
    },

    created: function() {
      let vm = this
      axios.get('/api/sources/' + this.side + '/' + this.market)
      .then(response => {
        var json = response.data
        this.sources = this.sources.concat(json.data)
      })
    },

  }
</script>