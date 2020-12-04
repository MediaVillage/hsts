var webpack = require('webpack');

module.exports = {
    module: {
        loaders: [
            { test: /\.html$/, loader: 'underscore-template-loader' }
        ]
    },
    resolve: {
        alias: {
            'masonry': 'masonry-layout',
            'isotope': 'isotope-layout'
        }
    },
    plugins: [
        new webpack.ProvidePlugin({
            '$': 'jquery'
        })
    ],
    externals: {
        jquery: 'window.jQuery'
    }
};