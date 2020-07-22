import Vue from 'vue';
import VueRouter, { RouteConfig } from 'vue-router';

Vue.use(VueRouter);

const routes: Array<RouteConfig> = [
    {
        path: '/',
        name: 'dashboard',
        component: () => import('./views/Dashboard.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('./views/Login.vue'),
        meta: {
            requiresGuest: true,
            isSinglePage: true
        }
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('./views/Register.vue'),
        meta: {
            requiresGuest: true,
            isSinglePage: true
        }
    }
];

const router = new VueRouter({
    mode: 'history',
    base: '/',
    routes
});

export default router;
