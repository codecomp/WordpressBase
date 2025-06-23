const path = require('path');
const magicImporter = require('node-sass-magic-importer');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const webpack = require('webpack');
const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const CopyPlugin = require('copy-webpack-plugin');
const StylelintPlugin = require('stylelint-webpack-plugin');

module.exports = (env, argv) => {
    const isDevMode = argv.mode === 'development';

    return {
        entry: {
            main: ['./assets/js/main.js', './assets/css/main.scss'],
            admin: ['./assets/css/admin-styles.scss'],
            'admin-editor': ['./assets/css/admin-editor-styles.scss'],
        },
        output: {
            filename: 'js/[name].js',
            path: path.resolve(__dirname, 'dist'),
        },
        module: {
            rules: [
                {
                    test: /\.(scss|css)$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        {
                            loader: 'css-loader',
                            options: {
                                sourceMap: isDevMode,
                            },
                        },
                        {
                            loader: 'postcss-loader',
                            options: {
                                sourceMap: isDevMode,
                            },
                        },
                        {
                            loader: 'sass-loader',
                            options: {
                                sourceMap: isDevMode,
                                implementation: require('sass'),
                                sassOptions: {
                                    importer: magicImporter(),
                                },
                            },
                        },
                    ],
                },
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    enforce: 'pre',
                    use: [
                        {
                            loader: 'babel-loader',
                            options: {
                                presets: [['@babel/preset-env', { modules: false }], '@babel/preset-modules'],
                            },
                        },
                        {
                            loader: 'eslint-loader',
                            options: {},
                        },
                    ],
                },
            ],
        },
        devServer: {
            hot: true, // Enable Hot Module Replacement
            port: 8080, // Webpack Dev Server port
            proxy: {
                '**': {
                    target: 'http://localhost:8000', // WordPress container port
                    changeOrigin: true,
                    secure: false,
                },
            },
            watchOptions: {
                ignored: /node_modules/,
            },
            hotOnly: true, // Force full page reload for non-HMR updates
            liveReload: true, // Enable live reloading for non-HMR updates
            static: path.join(__dirname, 'dist'), // Serve static files from the 'dist' directory
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: 'css/[name].css',
            }),

            isDevMode &&
                new StylelintPlugin({
                    files: '**/*.scss',
                }),

            isDevMode &&
                new webpack.HotModuleReplacementPlugin({
                    // Force full page reload for these file types
                    reloadAll: [/\.php$/],
                }),

            new CopyPlugin({
                patterns: [
                    {
                        from: path.resolve(__dirname, 'assets/images'),
                        to: 'images',
                    },
                ],
            }),

            new CopyPlugin({
                patterns: [
                    {
                        from: path.resolve(__dirname, 'assets/fonts'),
                        to: 'fonts',
                    },
                ],
            }),

            new CopyPlugin({
                patterns: [
                    {
                        from: path.resolve(__dirname, 'assets/favicons'),
                        to: 'favicons',
                    },
                ],
            }),
        ].filter(Boolean),
        optimization: {
            minimize: true,
            minimizer: [
                !isDevMode && new TerserPlugin(),
                new ImageMinimizerPlugin({
                    generator: [
                        {
                            type: 'asset',
                            implementation: ImageMinimizerPlugin.imageminGenerate,
                            options: {
                                plugins: [
                                    ['imagemin-optipng'],
                                    ['imagemin-mozjpeg'],
                                    ['imagemin-gifsicle'],
                                    ['imagemin-svgo'],
                                ],
                            },
                        },
                    ],
                }),
            ].filter(Boolean),
        },
        devtool: isDevMode ? 'source-map' : false,
    };
};
