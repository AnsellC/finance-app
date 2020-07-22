import Vue from 'vue';
import axios from '@/plugins/axios';
import App from '@/App.vue';
import router from '@/router';
import vuetify from '@/plugins/vuetify';
import store from '@/vuex';
import * as _ from 'lodash'
import User from "@/models/user";
import { UserPlugin } from "@/plugins/user";
import config from "@/config";

Vue.prototype.$axios = axios;
Vue.use(UserPlugin);

// Automatic component registration
const requireComponent = require.context(
    './components',
    true,
    /[A-Z]\w+\.(vue|js)$/
);

requireComponent.keys().forEach(fileName => {
    const componentConfig = requireComponent(fileName);
    const componentName = _.upperFirst(
        _.camelCase(
            fileName
                .split('/')
                .pop()!
                .replace(/\.\w+$/, '')
        )
    );

    Vue.component(componentName, componentConfig.default || componentConfig);
});

(async () => {
    // Check if browser has token stored.
    const storageToken = localStorage.getItem(config.localStorageKey)
    if (storageToken) {
        const auth = JSON.parse(storageToken);
        try {
            await Vue.prototype.$user.me(auth.token, auth.expires);
        }
        catch(error) {
            localStorage.removeItem(config.localStorageKey);
        }
    }
})().then(() => {
    const isLoggedIn = store.getters['isLoggedIn'];

    router.beforeEach((to, from, next) => {
        const requiresAuth = to.matched.some(x => x.meta.requiresAuth);
        const requiresGuest = to.matched.some(x => x.meta.requiresGuest);
        Vue.prototype.$isSinglePage = to.matched.some(x => x.meta.isSinglePage);

        if (requiresAuth && !isLoggedIn) {
            next('/login');
        } else if (requiresGuest && isLoggedIn) {
            next('/dashboard');
        } else if (to.path === '/') {
            next('/dashboard');
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

})
