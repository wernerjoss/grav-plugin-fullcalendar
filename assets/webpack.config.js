const path = require('path');

module.exports = {
  watch: true,
  mode: 'development',
  entry: './src/index.js',
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: 'bundle.js',
  },
  target: 'web',
  module: {
    /*sass?*/
  },
  resolve: {
    extensions: ['.js'],
    modules: [
      path.resolve('./node_modules'),
      path.resolve(__dirname, './node_modules'),
      'node_modules'
    ]
  },
  plugins : [],
  devtool: 'sourcemap'
};
