const mix = require('laravel-mix');
const fs = require('fs');

/* Remove old build and restore required file structure */
if (fs.existsSync('resources/dist')) {
    fs.rmdirSync('resources/dist', { recursive: true });

    fs.mkdirSync('resources/dist');
    fs.mkdirSync('resources/dist/images');
}

/* Configure the public path */
mix.setPublicPath('resources/dist');

mix.setResourceRoot('/vendor/sqms-servers');

/* Build SCSS/JS assets */
mix
.sass('resources/scss/sqms-servers.scss', 'resources/dist/css')
.options({
  postCss: [
      require('postcss-import'),
      require('tailwindcss')('./tailwind.config.js'),
      require('autoprefixer'),
  ],
})

.js('resources/js/server-status-listener.js', 'resources/dist/js')

.version();

/* Copy static images */
mix.copyDirectory('resources/images/static', 'resources/dist/static-images');