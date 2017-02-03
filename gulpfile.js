const elixir = require('laravel-elixir');

elixir((mix) => {
    mix.browserify('admin/admin.js')
        .browserify('web/web.js')
        .sass('admin/admin.scss', 'public/css/admin.css')
        .sass('web/web.scss', 'public/css/web.css')
        .sass([
            'vendors/bootstrap.scss',
            'vendors/toastr.css',
            'vendors/material-icon.scss',
            'app.scss'
        ], 'public/css/app.css')
        .version([
            'js/admin.js',
            'js/web.js',
            'css/app.css',
            'css/admin.css',
            'css/web.css'
        ]);
});
