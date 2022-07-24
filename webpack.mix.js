const mix = require('laravel-mix');

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

mix.webpackConfig({
    module: {
        rules: [
            {
                // Matches all PHP or JSON files in `resources/lang` directory.
                test: /resources[\\\/]lang.+\.(php|json)$/,
                loader: 'laravel-localization-loader',
            }
        ]
    }
});

mix.js('resources/js/app.js', 'public/js').vue()
    .sass('resources/sass/app.scss', 'public/css').options({processCssUrls: false})
    .copy('node_modules/open-iconic/font/fonts', 'public/fonts')
    .sass('node_modules/open-iconic/font/css/open-iconic-bootstrap.scss', 'public/css')
    .sass('node_modules/font-awesome/scss/font-awesome.scss', 'public/css')
    .copy('node_modules/font-awesome/fonts', 'public/fonts')
    .copy('node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.css', 'public/css');
