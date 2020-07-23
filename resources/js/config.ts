const config = {
    localStorageKey: 'app_token',
    endpoints: {
        baseURL: '/api',

        auth: {
            login: '/auth/login',
            me: '/auth/me'
        },
        transactions: '/transactions'
    }
};

export default config;
