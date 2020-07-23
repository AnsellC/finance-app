import config from '@/config';
import axios from '@/plugins/axios';
import store from '@/vuex';

export default class User {
    id = '';
    name = '';
    email = '';

    public async login(email: string, password: string): Promise<any> {
        const response = await axios.post(config.endpoints.auth.login, {
            email: email,
            password: password
        });

        if (!response.data.access_token || !response.data.expires_in) {
            throw new Error('Failed to retrieve token data.');
        }

        const accessToken = response.data.access_token;
        const expires = response.data.expires_in as number;

        await localStorage.setItem(
            config.localStorageKey,
            JSON.stringify({
                token: accessToken,
                expires: expires
            })
        );

        return this.me(accessToken, expires);
    }

    public async logout(): Promise<[void, any]> {
        return Promise.all([
            localStorage.removeItem(config.localStorageKey),
            store.dispatch('SET_USER_DATA', {
                isLoggedIn: false,
                name: '',
                email: '',
                auth: {
                    token: '',
                    expires: ''
                }
            })
        ]);
    }

    public async me(token: string, expires: number): Promise<any> {
        axios.defaults.headers.common.Authorization = `Bearer ${token}`;

        // get user data
        const userDataResponse = await axios.post(config.endpoints.auth.me);

        this.name = userDataResponse.data.name;
        this.email = userDataResponse.data.email;

        return store.dispatch('SET_USER_DATA', {
            isLoggedIn: true,
            name: userDataResponse.data.name,
            email: userDataResponse.data.email,
            auth: {
                token: token,
                expires: expires
            }
        });
    }
}
