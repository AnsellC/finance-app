import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const baseState = {
    // auth
    isLoggedIn: false,

    // Alert message used globally
    alertMessage: {
        text: '',
        type: null,
        errors: [],
    } as AlertMessage
};

export default new Vuex.Store({
    state: baseState,
    getters: {
        isLoggedIn: state => state.isLoggedIn,
        alertMessage: state => state.alertMessage
    },
    mutations: {
        ALERT_MESSAGE(state, message) {
            state.alertMessage = message;
        }
    },
    actions: {
        SET_ALERT_MESSAGE(context, msg) {
            context.commit('ALERT_MESSAGE', msg);
        }
    },
    modules: {}
});
