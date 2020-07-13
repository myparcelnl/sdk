/**
 * 2017-2018 Internetbureau get b.v.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (Version 3)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@gett.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Store Selector to newer
 * versions in the future. If you wish to customize Store Selector for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    Internetbureau get b.v. <support@gett.nl>
 * @copyright 2010-2018 Internetbureau get b.v.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html  GNU General Public License (Version 3)
 * GNU General Public License & Property of Internetbureau get b.v.
 */

const webpack = require('webpack');

module.exports = {
  entry: {
    front: __dirname + '/views/js/myparcelinit.js'
  },
  output: {
    path: __dirname + '/dist/',
    filename: '[name].bundle.js'
  },
  module: {
    loaders: [
      {
        test: /.js$/,
        loader: 'babel-loader',
        exclude: /node_modules/,
        query: {
          presets: ['es2015']
        }
      }
    ]
  },
  plugins: [
    new webpack.DefinePlugin({
      'process.env': {
        'NODE_ENV': JSON.stringify('production')
      }
    }),
  ]
};
