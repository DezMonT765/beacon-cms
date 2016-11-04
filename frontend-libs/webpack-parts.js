/**
 * Created by Dezmont on 29.09.2016.
 */
const webpack = require('webpack');

const CleanWebpackPlugin = require('clean-webpack-plugin');

const ExtractTextPlugin = require('extract-text-webpack-plugin');

const PurifyCSSPlugin = require('purifycss-webpack-plugin');

exports.purifyCSS = function (paths) {
    return {
        plugins: [
            new PurifyCSSPlugin({
                basePath: process.cwd(),
                // `paths` is used to point PurifyCSS to files not
                // visible to Webpack. You can pass glob patterns
                // to it.
                paths: paths
            })
        ]
    }
};
exports.html = function (paths) {
    return {
        module: {
            loaders: {
                test: '/\.html$/',
                loaders: ['html?interpolate'],
                include: paths
            }
        }
    }
};
exports.babel = function (paths) {
    return {
        module: {
            loaders: [
                {
                    test: /\.jsx?$/,
                    // Enable caching for improved performance during development
                    // It uses default OS directory by default. If you need
                    // something more custom, pass a path to it.
                    // I.e., babel?cacheDirectory=<path>
                    loaders: ['babel?cacheDirectory'],
                    // Parse only app files! Without this it will go through
                    // the entire project. In addition to being slow,
                    // that will most likely result in an error.
                    include: paths
                }
            ]
        }
    }
};

exports.setupCSS = function (paths) {
    return {
        module: {
            loaders: [
                {
                    test: /\.css$/,
                    loaders: ['style', 'css'],
                    include: paths
                }
            ]
        }
    };
};

exports.extractCSS = function (paths) {
    return {
        module: {
            loaders: [
                // Extract CSS during build
                {
                    test: /\.css$/,
                    loader: ExtractTextPlugin.extract('style', 'css'),
                    include: paths
                }
            ]
        },
        plugins: [
            // Output extracted CSS to a file
            new ExtractTextPlugin('[name].css')
        ]
    };
};

exports.minifyJS = function () {
    return {
        plugins: [
            new webpack.optimize.UglifyJsPlugin({
                beautify: false,
                comments: false,
                compress: {
                    warnings: false,
                    drop_console: true
                },
                mangle: {
                    screw_ie8: true
                }
            })
        ]
    };
};

exports.setFreeVariable = function (key, value) {
    const env = {};
    env[key] = JSON.stringify(value);

    return {
        plugins: [
            new webpack.DefinePlugin(env)
        ]
    };
};

exports.extractBundle = function (options) {
    const entry = {};
    entry[options.name] = options.entries;

    return {
        // Define an entry point needed for splitting.
        entry: entry,
        plugins: [
            // Extract bundle and manifest files. Manifest is
            // needed for reliable caching.
            new webpack.optimize.CommonsChunkPlugin({
                names: [options.name, 'manifest']
            })
        ]
    };
};

exports.clean = function (path) {
    return {
        plugins: [
            new CleanWebpackPlugin([path], {
                // Without `root` CleanWebpackPlugin won't point to our
                // project and will fail to work.
                root: process.cwd()
            })
        ]
    };
};

exports.extractFonts = function (paths) {
    return {
        module: {
            loaders: [
                {test: /\.eot(\?v=\d+\.\d+\.\d+)?$/, loader: "file", include: paths},
                {test: /\.(woff|woff2)$/, loader: "url?prefix=font/&limit=5000", include: paths},
                {test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/, loader: "url?limit=10000&mimetype=application/octet-stream", include: paths},
                {test: /\.svg(\?v=\d+\.\d+\.\d+)?$/, loader: "url?limit=10000&mimetype=image/svg+xml", include: paths}
            ]
        }
    }
};

