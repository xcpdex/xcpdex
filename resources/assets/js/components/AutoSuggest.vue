<template>
  <autocomplete
    placeholder=""
    source="https://xcpdex.com/api/search?q="
    input-class="form-control form-control-dark w-100"
    results-property="data"
    results-display="name"
    @enter="searchUrl"
    @selected="goToUrl">
  </autocomplete>
</template>

<script>
    import Vue from 'vue'
    import Autocomplete from 'vuejs-auto-complete'

    export default {
      data () {
        return {
          apiResults: null,
          keyword: '',
        }
      },
      components: {
        Autocomplete,
      },
      methods: {
        goToUrl (obj) {
          this.apiResults = obj
          return window.location.href = 'https://xcpdex.com/asset/' + this.apiResults.selectedObject.slug
        },
        searchUrl (keyword) {
          this.keyword = keyword
          return window.location.href = 'https://xcpdex.com/search?q=' + this.keyword
        }
      },
    }
</script>
