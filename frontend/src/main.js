import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'

Vue.config.productionTip = false;

axios.defaults.baseURL = 'http://127.0.0.1:8081';

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app');
