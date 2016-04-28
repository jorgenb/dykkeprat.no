var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');

    mix.styles([
        'bootstrap.min.css',
        'custom.min.css',
        'posts.css'
    ]);

    mix.scripts([
        'jquery-1.10.2.min.js',
        'bootstrap.min.js',
        'custom.js',
        'vue.js',
        'vue-resource.js',
        'main.js'
    ]);
});