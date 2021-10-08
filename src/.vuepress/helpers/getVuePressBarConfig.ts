import getConfig, {Config, Options} from 'vuepress-bar';
import path from 'path';

const options: Options = {
  navPrefix: null,
};

/**
 * Create the vuepress-bar configuration using above options.
 */
export function getVuePressBarConfig(): Config {
  return getConfig(path.resolve(__dirname, '../', '../'), options);
}
