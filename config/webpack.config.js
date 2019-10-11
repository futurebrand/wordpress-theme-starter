const webpack = require('webpack');
const path = require('path');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const LiveReloadPlugin = require('webpack-livereload-plugin');

module.exports = {
  entry: [
    path.resolve(__dirname, '../src/javascripts/app.js'),
    path.resolve(__dirname, '../src/styles/style.scss')
  ],
  output: {
    filename: 'app_bundle.js',
    path: path.resolve(__dirname, '../build/')
  },
  devServer: {
    hot: true,
    port: '3000',
    publicPath: '/build',
    contentBase: path.resolve(__dirname, '../build/'),
    watchContentBase: true,
    headers: {
      'Access-Control-Allow-Origin': '*'
    }
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: [ '@babel/preset-env' ]
          }
        }
      },
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          {
            loader: 'postcss-loader',
            options: {
              config: {
                path: path.resolve(__dirname, '../postcss.config.js')
              }
            }
          },
          'sass-loader'
        ]
      },
      {
        test: /\.(png|jpg|jpeg|gif|svg|otf|ttf|eot|woff)$/,
        use: [
          {
            loader: 'file-loader',
            options: {}
          }
        ]
      }
    ]
  },
  optimization: {
    minimizer:
      process.env.NODE_ENV === 'production'
        ? [ new OptimizeCSSAssetsPlugin({}), new TerserPlugin() ]
        : []
  },
  plugins: [
    new webpack.NamedModulesPlugin(),
    new webpack.DefinePlugin({
      NODE_ENV: JSON.stringify(process.env.NODE_ENV),
      config: JSON.stringify(require(`./${process.env.NODE_ENV}.json`))
    }),
    new MiniCssExtractPlugin({ filename: 'style.css' }),
    new LiveReloadPlugin()
  ]
};
