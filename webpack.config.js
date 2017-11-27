const path = require('path')
const ExtractTextPlugin = require("extract-text-webpack-plugin");

module.exports = env => {
  const config = {
    context: __dirname,
    entry: {
      app: './assets/ts/index.tsx',
      vendor: [
        'bootstrap-sass/assets/stylesheets/_bootstrap.scss',
      ]
    },
    output: {
      path: path.resolve(__dirname, 'web/build'),
      filename: '[name].app.js',
      publicPath: '/build/',
      pathinfo: true,
    },

    devtool: 'source-map',

    resolve: {
      extensions: ['.ts', '.tsx', '.js'],
    },

    module: {
      rules: [
        {
          test: /\.tsx?$/,
          use: {
            loader: 'awesome-typescript-loader',
          }
        },
        {
          test: /\.js$/,
          use: {
            loader: 'source-map-loader',
          }
        },
        {
          test: /\.(woff|woff2|eot|ttf|otf|svg)$/,
          use: {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]'
            }
          }
        },
        {
          test: /\.css$/,
          use: ExtractTextPlugin.extract({
            fallback: "style-loader",
            use: "css-loader"
          })
        },
        {
          test: /\.scss$/,
          use: ExtractTextPlugin.extract({
            fallback: 'style-loader',
            use: ['css-loader', 'sass-loader'],
          }),
        },
      ]
    },

    // you cat setup copy-webpack-plugin to exclude React dist from compiling
    // externals: {
    //   'react': 'React',
    //   'react-dom': 'ReactDOM',
    // },

    plugins: [
      new ExtractTextPlugin('style.css')
    ]
  }

  return config
}
