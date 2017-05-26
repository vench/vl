var path = require('path');
var webpack = require('webpack'); 

module.exports = {
  entry: {  
      app: [  './web/js/app.js' ] 
 },
 
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, './web/assets/'),
    publicPath: '/assets/',
  
  },
  
  //
  module: {
    loaders: [
      {
        test: /.js$/,
        exclude: /node_modules/,
        loader: 'babel-loader?blacklist=react',
        options: { 
          presets: [ 
            'es2015'  
          ] 
        } 
      },
      
      {
        test: /.js$/,
        exclude: /node_modules/,
        loader: 'jsx-loader',
        options: { 
          presets: [ 
            'es2015'  
          ] 
        } 
      }
    ]
  },
  //
  
  externals: {
    jquery: 'jQuery'
  },
  
  //
 plugins: [
        new webpack.ProvidePlugin({
            jQuery: 'jquery',
            $: 'jquery',
            jquery: 'jquery'
        })
    ]
}