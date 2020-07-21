import Vue from 'vue'
import axios from 'axios'

Vue.prototype.$axios = axios;
const app = new Vue({
    el: '#app',
});
