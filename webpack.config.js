const glob = require('glob');
const path = require('path');
const autoprefixer = require('autoprefixer');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

module.exports = {
  mode: 'production',
  target: 'web',
  plugins: [
    new RemoveEmptyScriptsPlugin(),
    new MiniCssExtractPlugin({
      filename: "timetoread.css",
      chunkFilename: "[id].css",
      ignoreOrder: false,
    }),
  ],
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [
          { loader: MiniCssExtractPlugin.loader },
          {
            loader: 'css-loader',
            options: {
              url: false
            }
          },
          {
            loader: 'postcss-loader',
            options: {
              postcssOptions: {
                plugins: () => [
                  autoprefixer()
                ],
              },
            },
          },
          {
            loader: "sass-loader",
            options: {
              sourceMap: true,
              implementation: require("sass"),
              webpackImporter: false,
              sassOptions: {
                outputStyle: "compressed",
              },
            },
          },
        ],
      },
    ],
  },
  entry: './assets/public/scss/styles.scss',
  output: {
    path: path.resolve(__dirname, './assets/public/css/'),
  },
  watch: true,
  stats: {
    chunks: false,
    warnings: false,
    source: false,
    modules: false,
    assets: false
  }
};
