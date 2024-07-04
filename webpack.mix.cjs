const mix = require('laravel-mix');
const webpack = require('webpack');

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .webpackConfig({
        plugins: [
            new webpack.DefinePlugin({
                '__VUE_OPTIONS_API__': JSON.stringify(true),
                '__VUE_PROD_DEVTOOLS__': JSON.stringify(false),
                '__VUE_PROD_HYDRATION_MISMATCH_DETAILS__': JSON.stringify(false) // o true, dependiendo de tus necesidades
            })
        ]
    });
