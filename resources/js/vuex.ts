import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const baseState = {

    user: {
        isLoggedIn: false,
        name: '',
        email: '',
        auth: {
            token: '',
            expires: '',
        }
    },


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
        alertMessage: state => state.alertMessage,
        user: state => state.user,
        isLoggedIn: state => state.user.isLoggedIn
    },
    mutations: {
        ALERT_MESSAGE(state, message) {
            state.alertMessage = message;
        },

        USER_DATA(state, data) {
            state.user = data;
        }
    },
    actions: {
        SET_ALERT_MESSAGE(context, msg) {
            if (! msg.errors) {
                context.commit('ALERT_MESSAGE', {...msg, errors: []});
            } else {
                context.commit('ALERT_MESSAGE', msg);
            }
        },

        SET_USER_DATA(context, data) {
            context.commit('USER_DATA', data);
        }
    },
    modules: {}
});
