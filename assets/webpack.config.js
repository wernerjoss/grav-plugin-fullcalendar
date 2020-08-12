const path = require('path');


module.exports = {
  watch: true,
  mode: 'development',
  entry: './src/index.js',
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: 'bundle.js'
  },
  target: 'web',
  module: {
    rules: [
      {
        test: /\.css$/i,
        use: ['style-loader', 'css-loader']
      },
      {
        test: /\.s[ac]ss$/i,
        use: [
          // Creates `style` nodes from JS strings
          'style-loader',
          // Translates CSS into CommonJS
          'css-loader',
          // Compiles Sass to CSS
          'sass-loader',
        ] 
      }
    ]
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
