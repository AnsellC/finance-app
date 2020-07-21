const mix = require('laravel-mix');
require('vuetifyjs-mix-extension');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.ts('resources/js/app.ts', 'public/dist/js').vuetify();
mix.webpackConfig({
    output: {
        chunkFilename: 'dist/js/[name].js'
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js')
        }
    }
});
