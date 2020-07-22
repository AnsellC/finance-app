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
            text: '',
            errors: [],
        });
    }

    protected handleError(error: any) {
        if (! error.response?.data?.message && !error.response?.data?.errors) {
            return this.showAlertMessage({
                text: 'An unknown error occurred.',
                type: 'error',
            });
        }
        const errorMessages: string[] = [];
        let errorTitle = 'An error occurred.';

        if (error.response?.data?.message) {
            errorTitle = error.response.data.message;
        }

        if (error.response?.data?.errors) {
            const keys = Object.keys(error.response.data.errors);
            keys.forEach(key => {
                error.response.data.errors[key].forEach((message: string) => {
                    errorMessages.push(message);
                })
            })
        }
        return this.showAlertMessage({
            text: errorTitle,
            errors: errorMessages,
            type: 'error',
        });

    }
}
