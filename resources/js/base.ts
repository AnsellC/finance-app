import { Component, Vue } from 'vue-property-decorator';
import { mapGetters } from 'vuex';

@Component({
    computed: {
        ...mapGetters(['isLoggedIn', 'alertMessage'])
    }
})
export default class BaseClass extends Vue {
    // Loading indicator
    loading = false;

    protected showAlertMessage(message: AlertMessage): Promise<any> {
        return this.$store.dispatch('SET_ALERT_MESSAGE', message);
    }

    protected clearAlertMessage(): Promise<any> {
        return this.$store.dispatch('SET_ALERT_MESSAGE', {
            type: null,
            text: ''
        });
    }
}
