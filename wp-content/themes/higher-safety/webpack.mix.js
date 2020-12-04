let mix = require('laravel-mix');

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

mix.webpackConfig(webpack => {
    return {
        module: {
            rules: [
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
})
.js('resources/js/main.js', './js/')
.sass('resources/sass/style.scss', './')
.sass('resources/sass/login.scss', './css/')
.copy('node_modules/font-awesome/fonts', 'fonts/FontAwesome')
.options({
     processCssUrls: false
});
