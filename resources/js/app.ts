import Vue from 'vue';
import axios from 'axios';
import App from '@/App.vue';
import router from '@/router';
import vuetify from '@/plugins/vuetify';
import store from '@/vuex';

Vue.prototype.$axios = axios;
Vue.prototype.$axios.defaults.baseURL = '/api';

const isLoggedIn = store.getters['isLoggedIn'];

router.beforeEach((to, from, next) => {
    const requiresAuth = !!to.matched.some(x => x.meta.requiresAuth);
    const isSinglePage = !!to.matched.some(x => x.meta.isSinglePage);

    Vue.prototype.$isSinglePage = isSinglePage;

    if (requiresAuth && !isLoggedIn) {
        next('/login');
    } else {
        next();
    }
});

const app = new Vue({
    router,
    store,
    vuetify,
    render: h => h(App)
}).$mount('#app');
