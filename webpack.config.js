var Encore = require('@symfony/webpack-encore');
const CopyWebpackPlugin = require('copy-webpack-plugin');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addStyleEntry('styles', './assets/sass/app.sass')
    .addEntry('app', './assets/js/app.js')
    .addEntry('map', './assets/js/map.js')
    // .addPlugin(new CopyWebpackPlugin([
    //     // copies to {output}/static
    //     { from: '../assets/js/scripts.min.js', to: 'scripts' }
    // ]))
    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    // .createSharedEntry('vendor', [
    //     './node_modules/jquery/dist/jquery.min.js',
    //     './node_modules/jquery-ui-dist/jquery-ui.min.js',
    //     './node_modules/jquery.fancybox/source/jquery.fancybox.js',
    //     './node_modules/jquery.maskedinput/src/jquery.maskedinput.js'
    // ])
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you're having problems with a jQuery plugin
    // .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
