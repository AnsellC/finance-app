import Vue from 'vue';
import axios from 'axios';
import App from '@/App.vue';
import router from '@/router';
import vuetify from '@/plugins/vuetify';
import store from '@/vuex';

Vue.prototype.$axios = axios;
const app = new Vue({
    router,
    store,
    vuetify,
    render: h => h(App)
}).$mount('#app');
