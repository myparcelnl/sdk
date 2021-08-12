const { addGitHubPlugin } = require('@myparcel/semantic-release-config/src/plugins/addGitHubPlugin');
const mainConfig = require('@myparcel/semantic-release-config');

module.exports = {
  ...mainConfig,
  'extends': '@myparcel/semantic-release-config/npm',
  'plugins': [
    ...mainConfig.plugins,
    addGitHubPlugin(),
  ],
};
