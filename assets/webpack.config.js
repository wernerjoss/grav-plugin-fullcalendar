const path = require('path');
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');


module.exports = {
  watch: true,
  mode: 'development',
  entry: './src/index.js',
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: 'bundle.js'
  },
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
    modules: ['node_modules']
  },
  plugins : [],
  devtool: 'sourcemap'
};
