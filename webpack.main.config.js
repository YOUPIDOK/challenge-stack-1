const Encore = require("@symfony/webpack-encore");

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

Encore.setOutputPath("public/build/main")
    .setPublicPath("/build/main")
    .addEntry("main", "./assets/main/app.js")
    .addStyleEntry("login", "./assets/main/styles/pages/login.scss")
    .addStyleEntry("daily-report", "./assets/main/styles/pages/daily_report.scss")
    .addStyleEntry("sidebarcss", "./assets/main/styles/partials/sidebar.scss")
    .addStyleEntry("home", "./assets/main/styles/pages/home.scss")
    .addStyleEntry("dashboard", "./assets/main/styles/pages/dashboard.scss")
    .enableStimulusBridge("./assets/main/controllers.json")
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(true)
    .configureBabel((config) => {
        config.plugins.push("@babel/plugin-proposal-class-properties");
    })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = "usage";
        config.corejs = 3;
    })
    .enableSassLoader();

if (Encore.isProduction()) {
    Encore.copyFiles({
        from: "./assets/main/images",
        to: "images/[path][name].[hash:8].[ext]",
        pattern: /\.(png|jpg|jpeg|gif|ico|svg|webp)$/,
    });
} else {
    Encore.copyFiles({
        from: "./assets/main/images",
        to: "images/[path][name].[hash:8].[ext]",
        pattern: /\.(png|jpg|jpeg|gif|ico|svg|webp)$/,
    });
}

module.exports = Encore.getWebpackConfig();
