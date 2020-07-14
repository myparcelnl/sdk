const path = require('path');

module.exports = {
  entry: __dirname + '/views/js/myparcelinit.js',
  output: {
    filename: 'front.js',
    path: path.resolve(__dirname, 'views/dist'),
  },
  module: {
    rules: [
      { test: /\.js$/, exclude: /node_modules/, loader: "babel-loader" }
    ]
  }
};