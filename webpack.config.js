const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

const isDev = process.env.NODE_ENV !== 'production';

module.exports = {
    mode: isDev ? 'development' : 'production',
    entry: {
        'web': './assets/js/app',
        'admin': './assets/js/admin/index.js'
    },
    output: {
        filename: '[name].js',
        chunkFilename: '[id].[chunkhash].js',
        path: path.resolve('./public/build')
    },
    resolve: {
        extensions: ['*', '.js', '.scss']
    },
    module: {
        rules: [
            {
                test: /\.s[ac]ss$/i,
                use: ["style-loader", "css-loader", "sass-loader"],
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: isDev ? '[name].css' : '[name].[hash].css',
            chunkFilename: isDev ? '[id].css' : '[id].[hash].css',
        })
    ]
}
