
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('orders', require('./components/Orders.vue'));
Vue.component('order-book', require('./components/OrderBook.vue'));
Vue.component('order-matches', require('./components/OrderMatches.vue'));
Vue.component('markets', require('./components/Markets.vue'));
Vue.component('matches', require('./components/Matches.vue'));
Vue.component('assets', require('./components/Assets.vue'));
Vue.component('asset-markets', require('./components/AssetMarkets.vue'));
Vue.component('auto-suggest', require('./components/AutoSuggest.vue'));
Vue.component('source-table', require('./components/SourceTable.vue'));
Vue.component('address-orders', require('./components/AddressOrders.vue'));
Vue.component('project-assets', require('./components/ProjectAssets.vue'));

const app = new Vue({
    el: '#app'
});
