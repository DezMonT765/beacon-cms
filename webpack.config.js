const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');
var webpack = require('webpack');
const merge = require('webpack-merge');
const configParts = require('./frontend-libs/webpack-parts');



const PATHS = {
    app: path.join(__dirname, 'app'),

    style: [
        path.join(__dirname, 'app', 'main.css'),
    ],
    build: path.join(__dirname,'web/libs/beacon-map')
};

const common = {
    // Entry accepts a path or an object of entries.
    // We'll be using the latter form given it's
    // convenient with more complex configurations.
    entry: {
        fetch : 'whatwg-fetch',
        app: PATHS.app,
        style : PATHS.style,
    },
    output: {
        path: PATHS.build,
        filename: '[name].js',
        library : 'lib',
        libraryTarget : 'var'
    },
    plugins: [
        new HtmlWebpackPlugin({
            title: 'Webpack demo',
            filename : 'index.php',
            template : 'app/beacon-map.php'
        }),
    ],
    resolve: {
        extensions: ['', '.js', '.jsx']
    },

};



var config;
switch(process.env.npm_lifecycle_event) {
    case 'build':
    case 'stats':
        config = merge(
            common,
            {
                devtool: 'source-map',
                output: {
                    path: PATHS.build,
                    filename: '[name].js',
                }
            },
            configParts.clean(PATHS.build),
            configParts.html(PATHS.app),
            configParts.babel(PATHS.app),
            configParts.setFreeVariable(
                'process.env.NODE_ENV',
                'prod'
            ),
            configParts.extractBundle({
                name: 'vendor',
                entries: ['./node_modules/pixi.js/bin/pixi','react','react/lib/ReactDOM']
            }),
            configParts.extractCSS(PATHS.style),
            configParts.minifyJS()

        );
        break;
    default :
        config = merge(
            common,
            {
                devtool: 'eval-source-map'
            },
            configParts.setupCSS(PATHS.app)
        );
}
module.exports = config;