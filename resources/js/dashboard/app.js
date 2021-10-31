import BootstrapVue from 'bootstrap-vue';
import Loading from 'vue-loading-overlay';

// import 'vue-slider-component/theme/default.css'
require('./bootstrap');
require('./filter');

window.Vue = require('vue').default;
Vue.use(BootstrapVue);
window.dayjs = require('dayjs');
Vue.component('loading', Loading);



Vue.component('dashboard', require('./views/Dashboard.vue').default);



const app = new Vue({
  el: '#app',
});
