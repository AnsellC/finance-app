import Vue from 'vue'
import axios from 'axios'
import App from './App.vue'

Vue.prototype.$axios = axios;
const app = new Vue({
    render: h => h(App),
}).$mount('#app');
