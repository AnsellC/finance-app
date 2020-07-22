const config = {
    localStorageKey: 'app_token',
    endpoints: {
        baseURL: '/api',

        auth: {
            login: '/auth/login',
            me: '/auth/me'
        }
    }
}

export default config;
