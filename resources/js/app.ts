import Vue from 'vue';
import axios from 'axios';
import App from '@/App.vue';
import router from '@/router';
import vuetify from '@/plugins/vuetify';
import store from '@/vuex';
import { upperFirst, camelCase } from 'lodash'

Vue.prototype.$axios = axios;
Vue.prototype.$axios.defaults.baseURL = '/api';

// Automatic component registration
const requireComponent = require.context(
    './components',
    true,
    /[A-Z]\w+\.(vue|js)$/
);

requireComponent.keys().forEach(fileName => {
    const componentConfig = requireComponent(fileName);
    const componentName = upperFirst(
        camelCase(
            fileName
                .split('/')
                .pop()!
                .replace(/\.\w+$/, '')
        )
    );

    Vue.component(componentName, componentConfig.default || componentConfig);
});

const isLoggedIn = store.getters['isLoggedIn'];

router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(x => x.meta.requiresAuth);
    Vue.prototype.$isSinglePage = to.matched.some(x => x.meta.isSinglePage);

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
