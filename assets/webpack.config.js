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
    rules: [
      {
        test: /\.css$/i,
        use: [
          'style-loader', 
          {
            loader: 'css-loader',
            options: {
              sourceMap: true,
            },
          },
        ],
      },
      {
        test: /\.s[ac]ss$/i,
        use: [
          // Creates `style` nodes from JS strings
          'style-loader', 
          // Translates CSS into CommonJS
          {
            loader: 'css-loader',
            options: {
              sourceMap: true,
            },
          },
          // Compiles Sass to CSS
          {
            loader: 'sass-loader',
            options: {
              sourceMap: true,
              sassOptions: {
                outputStyle: 'compressed',
              },
            },
          },
        ],
      },
    ],
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
