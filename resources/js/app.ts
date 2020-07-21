import Vue from 'vue';
import axios from 'axios';
import App from './App.vue';
import vuetify from './plugins/vuetify';

Vue.prototype.$axios = axios;
const app = new Vue({
    vuetify,
    render: h => h(App)
}).$mount('#app');
