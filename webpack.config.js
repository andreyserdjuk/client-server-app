const path = require('path')
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const CopyWebpackPlugin = require('copy-webpack-plugin');

let reactFile = 'react.production.min.js',
    reactDomFile = 'react-dom.development.js';
if (process.env.NODE_ENV !== 'production') {
  console.log('Looks like we are in development mode!');
  reactFile = 'react.development.js';
  reactDomFile = 'react-dom.production.min.js';
}

module.exports = (env) => {
  const buildPath = path.resolve(__dirname, 'web/build');  
  const config = {
    context: __dirname,
    entry: {
      app: './assets/ts/index.tsx',
      vendor: [
        'bootstrap-sass/assets/stylesheets/_bootstrap.scss',
      ]
    },
    output: {
      path: buildPath,
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

    externals: {
      'react': 'React',
      'react-dom': 'ReactDOM',
    },

    plugins: [
      new ExtractTextPlugin('style.css'),
      new CopyWebpackPlugin([
        { from: `node_modules/react/umd/${reactFile}`, to: `${buildPath}/react.js` },
        { from: `node_modules/react-dom/umd/${reactDomFile}`, to: `${buildPath}/react-dom.js` },
      ]),
    ]
  }

  return config
}
