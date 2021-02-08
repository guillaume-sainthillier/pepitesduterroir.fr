const webpack = require('webpack');
const path = require('path');
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');

module.exports = function(env, argv) {
  let production = (argv['mode'] === 'production');
  return {
    devtool: production ? '' : 'source-map',
    entry: {
      main: [
        './js/theme.js',
        './css/theme.scss'
      ]
    },
    output: {
      path: path.resolve(__dirname, '../assets/js'),
      filename: 'theme.js'
    },
    module: {
      rules: [
        {
          test: /\.js/,
          loader: 'babel-loader'
        },
        {
          test: /\.scss$/,
          use: ExtractTextPlugin.extract({
            fallback: 'style-loader',
            use: [
              {
                loader: 'css-loader',
                options: {
                  minimize: production ? true : false,
                  sourceMap: production ? false : true
                }
              },
              {
                loader: 'postcss-loader',
                options: {
                  minimize: production ? true : false,
                  sourceMap: production ? false : true
                }
              },
              {
                loader: 'sass-loader',
                options: {
                  minimize: production ? true : false,
                  sourceMap: production ? false : true,
                  precision: '8'
                }
              }
            ]
          })
        },
        {
          test: /.(gif|jpg|png)(\?[a-z0-9=\.]+)?$/,
          use: [
            {
              loader: 'file-loader',
              options: {
                name: '../img/[hash:base64:6].[ext]'
              }
            }
          ]
        },
        {
          test: /.(woff(2)?|eot|ttf|svg)(\?[a-z0-9=\.]+)?$/,
          use: [
            {
              loader: 'file-loader',
              options: {
                name: '../fonts/[hash:base64:6].[ext]'
              }
            }
          ]
        },
        {
          test : /\.css$/,
          use: ['style-loader', 'css-loader', 'postcss-loader']
        }
      ]
    },
    externals: {
      prestashop: 'prestashop',
      $: '$',
      jquery: 'jQuery'
    },
    plugins: [
      new ExtractTextPlugin(path.join('..', 'css', 'theme.css')),
    ],
    optimization: {
      minimizer: [
        new UglifyJSPlugin({
          test: /\.js($|\?)/i,
          exclude: /node_modules/,
          cache: true,
          parallel: true,
          uglifyOptions: {
            compress: {
              ecma: 5,
              keep_fnames: false,
              keep_fargs: false,
              drop_console: true,
            },
            ecma: 5,
            warnings: false,
            keep_classnames: false,
            keep_fnames: false,
            output: {
              comments: false,
              beautify: false,
              ecma: 5,
            },
          },
          extractComments: false,
          sourceMap: false,
        })
      ]
    },
  };
};