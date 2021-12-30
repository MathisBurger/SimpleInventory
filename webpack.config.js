var Encore = require('@symfony/webpack-encore');
const { VuetifyLoaderPlugin } = require('vuetify-loader');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .enableVueLoader()
    .enableTypeScriptLoader()
    .enableSassLoader()

    .addEntry('app', './assets/src/main.ts')

    .splitEntryChunks()

    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .addPlugin(new VuetifyLoaderPlugin())

;

module.exports = Encore.getWebpackConfig();