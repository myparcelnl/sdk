import {DefaultThemeOptions, defineUserConfig} from 'vuepress';
import {createNavAndSidebar} from './helpers/createNavAndSidebar';
import path from 'path';

const {navbar, sidebar} = createNavAndSidebar();

const faviconUrl = 'https://assets.myparcel.nl/skin/images/favicon';

export default defineUserConfig<DefaultThemeOptions>({
  title: 'MyParcel PHP SDK',
  description: 'Official PHP SDK which makes integrating with the MyParcel API easy.',
  base: '/sdk/',

  /* eslint-disable max-len */
  head: [
    ['meta', {name: 'theme-color', content: '#ffffff'}],
    ['meta', {name: 'apple-mobile-web-app-capable', content: 'yes'}],
    ['meta', {name: 'apple-mobile-web-app-status-bar-style', content: 'black'}],
    ['link',{rel: 'apple-touch-icon', sizes: '57x57', href: `${faviconUrl}/apple-icon-57x57.png`}],
    ['link',{rel: 'apple-touch-icon', sizes: '60x60', href: `${faviconUrl}/apple-icon-60x60.png`}],
    ['link',{rel: 'apple-touch-icon', sizes: '72x72', href: `${faviconUrl}/apple-icon-72x72.png`}],
    ['link',{rel: 'apple-touch-icon', sizes: '76x76', href: `${faviconUrl}/apple-icon-76x76.png`}],
    ['link',{rel: 'apple-touch-icon', sizes: '114x114', href: `${faviconUrl}/apple-icon-114x114.png`}],
    ['link',{rel: 'apple-touch-icon', sizes: '120x120', href: `${faviconUrl}/apple-icon-120x120.png`}],
    ['link',{rel: 'apple-touch-icon', sizes: '144x144', href: `${faviconUrl}/apple-icon-144x144.png`}],
    ['link',{rel: 'apple-touch-icon', sizes: '152x152', href: `${faviconUrl}/apple-icon-152x152.png`}],
    ['link',{rel: 'apple-touch-icon', sizes: '180x180', href: `${faviconUrl}/apple-icon-180x180.png`}],
    ['link',{rel: 'icon', type: 'image/png', sizes: '192x192', href: `${faviconUrl}/android-icon-192x192.png`}],
    ['link',{rel: 'icon', type: 'image/png', sizes: '32x32', href: `${faviconUrl}/favicon-32x32.png`}],
    ['link',{rel: 'icon', type: 'image/png', sizes: '96x96', href: `${faviconUrl}/favicon-96x96.png`}],
    ['link',{rel: 'icon', type: 'image/png', sizes: '16x16', href: `${faviconUrl}/favicon-16x16.png`}],
    ['meta', {name: 'msapplication-TileColor', content: '#ffffff'}],
    ['meta', {name: 'msapplication-TileImage', content: `${faviconUrl}/ms-icon-144x144.png`}],
  ],
  /* eslint-enable max-len */

  /**
   * @see https://v2.vuepress.vuejs.org/reference/default-theme/config.html
   */
  themeConfig: {
    contributors: false,
    docsBranch: 'docs',
    docsDir: '/src/',
    editLinks: true,
    logo: 'images/logo.svg',
    logoDark: 'images/logo_dark.svg',
    repo: 'https://github.com/myparcelnl/sdk',

    navbar,
    sidebar,
  },

  /**
   * @see https://v2.vuepress.vuejs.org/guide/plugin.html#community-plugin
   */
  plugins: [
    '@vuepress/plugin-search',
    [
      '@vuepress/register-components',
      {
        components: {
          ApiLink: path.resolve(__dirname, './components/ApiLink.vue'),
        },
      },
    ],
  ],
});
