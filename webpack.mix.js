const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
const path = 'public/';

mix.setPublicPath(path)
    // Admin
    .js([
        'resources/js/admin/app.js'
    ], 'js/admin/app.min.js')
    .js([
        'resources/js/admin/alpine.js'
    ], 'js/admin/alpine.js')
    .sass('resources/scss/admin/app.scss', 'css/admin/app.min.css')
    .postCss('resources/css/admin/tailwind.css', 'css/admin/tailwind.css' , [
        require('tailwindcss'),
    ])

mix.copyDirectory('resources/ckeditor', 'public/ckeditor');

if (mix.inProduction()) {
    mix.version();
}

mix.browserSync({
    open: true,
    proxy: '127.0.0.1:8000',
    files: [
        'app/**/*',
        'public/**/*',
        'routes/**/*'
    ]
});

mix.copyDirectory('resources/fonts', 'public/themes/default/fonts')
mix.copyDirectory('resources/img', 'public/themes/default/img')

// mix.scripts([
//     'resources/vendor/js/jquery.min.js',
// ], 'public/themes/default/js/vendor.min.js')

// js
mix.js([
    'resources/js/app.js',
], 'public/themes/default/js/app.min.js');
//sass
mix.disableNotifications();
mix.sass('resources/scss/app.scss', 'public/themes/default/css/app.min.css')
.sourceMaps()
