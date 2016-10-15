const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');

const merge = require('webpack-merge');
const configParts = require('./frontend-libs/webpack-parts');



const PATHS = {
    app: path.join(__dirname, 'app'),

    style: [
        path.join(__dirname, 'app', 'main.css'),
    ],
    build: path.join(__dirname, 'web/build')
};

const common = {
    // Entry accepts a path or an object of entries.
    // We'll be using the latter form given it's
    // convenient with more complex configurations.
    entry: {
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
            template : 'app/index.html'
        })
    ],
    resolve: {
        extensions: ['', '.js', '.jsx']
    }
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
                    filename: '[name].[chunkhash].js',
                    // This is used for require.ensure. The setup
                    // will work without but this is useful to set.
                    chunkFilename: '[chunkhash].js',

                }
            },
            configParts.clean(PATHS.build),
            configParts.html(PATHS.app),
            configParts.babel(PATHS.app),
            configParts.setFreeVariable(
                'process.env.NODE_ENV',
                'dev'
            ),
            configParts.extractBundle({
                name: 'vendor',
                entries: ['konva','react','react/lib/ReactDOM']
            }),
            configParts.extractCSS(PATHS.style)
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