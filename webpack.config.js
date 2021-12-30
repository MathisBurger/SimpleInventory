var Encore = require('@symfony/webpack-encore');

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

;

module.exports = Encore.getWebpackConfig();