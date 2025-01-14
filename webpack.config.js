const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('app', './assets/app.js')
    .addStyleEntry('Home', './assets/styles/Home.scss')
    .addStyleEntry('Inscription', './assets/styles/Inscription.scss')
    .addStyleEntry('Connexion', './assets/styles/Connexion.scss')
    .addStyleEntry('recette', './assets/styles/Recette.scss')
    .addStyleEntry('recetteDetail', './assets/styles/RecetteDetail.scss')


    .splitEntryChunks()

    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    })

    .enableSassLoader()
    .enableSourceMaps(false)
      
   
module.exports = Encore.getWebpackConfig();
