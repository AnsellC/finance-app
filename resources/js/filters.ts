import Vue from 'vue'


Vue.filter('currencyFormat', (value: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(value);
});
