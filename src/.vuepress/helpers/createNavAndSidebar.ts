import {NavbarConfig, SidebarConfigArray} from '@vuepress/theme-default/lib/shared/nav';
import {getVuePressBarConfig} from './getVuePressBarConfig';
import {transformSidebar} from './transformSidebar';

/**
 * Gets the sidebar and nav config from vuepress-bar and converts it to support VuePress 2.
 */
export function createNavAndSidebar(): {
  navbar: NavbarConfig;
  sidebar: SidebarConfigArray;
} {
  const {sidebar} = getVuePressBarConfig();

  const sidebarV2: SidebarConfigArray = transformSidebar(sidebar);

  return {
    navbar: [
      {
        text: 'Backoffice',
        link: 'https://backoffice.myparcel.nl',
      },
      {
        text: 'API docs',
        link: 'https://myparcelnl.github.io/api',
      },
    ],
    sidebar: sidebarV2,
  };
}
