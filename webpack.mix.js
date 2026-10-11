const mix = require('laravel-mix');

const webpack = require('webpack');

mix.webpackConfig({
    plugins: [
        new webpack.DefinePlugin({
            __VUE_OPTIONS_API__: true,
            __VUE_PROD_DEVTOOLS__: false,
            __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: false,
        }),
    ],
});


// mix.js('src/app.js', 'dist').setPublicPath('dist');


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


mix
  // Vue SFC support
  .vue({ version: 3 })

  // Main JS bundle
  .js('resources/js/app.js', 'public/js')

  // Tailwind CSS via PostCSS
  .postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
  ])

  // Plain scripts (no imports)
  .scripts([
    'resources/js/alert.js',
  ], 'public/js/script.js')

  .options({
    processCssUrls: false,
  })

  .version()

if (mix.inProduction()) {
  mix.minify()
}