/**
 * @see https://github.com/ozum/vuepress-bar
 * @see https://www.npmjs.com/package/vuepress-bar
 */
declare module 'vuepress-bar' {
  import {NavbarConfig} from '@vuepress/theme-default/lib/shared/nav';

  export interface Config {
    nav: NavbarConfig;
    sidebar: SidebarV1ConfigArray;
  }

  export default function getConfig(path: string, options: Options): Config;

  export interface Options {

    /**
     * Remove number prefixes from directory names where it helps to sort. Default: true.
     */
    stripNumbers?: boolean;

    /**
     * Maximum level of recursion for subdirectory traversing. Default: 2.
     */
    maxLevel?: number;

    /**
     * Prefix for directories for navbar and multiple sidebars. Default: "nav".
     */
    navPrefix?: string | null;

    /**
     * Do not add item to sidebar if directory is empty. Default: true.
     */
    skipEmptySidebar?: boolean;

    /**
     * Do not add item to navbar if directory is empty. Default: true.
     */
    skipEmptyNavbar?: boolean;

    /**
     * Create multiple sidebars if there are navbar items. Default: true.
     */
    multipleSideBar?: boolean;

    /**
     * Add README.md into first group of sidebar (VuePress website's behaviour). Default: true.
     */
    addReadMeToFirstGroup?: boolean;

    /**
     * Add directories to alphabetic positions between files. (i.e. 01-file, 02-folder, 03-file). Default: true.
     */
    mixDirectoriesAndFilesAlphabetically?: boolean;

    /**
     * Translate chinese nav to pinyin. Default: false.
     */
    pinyinNav?: boolean;

    /**
     * Filter function to filter files. Front Matter metadata is passed as an object.
     *
     * @returns {boolean}
     */
    filter?: () => boolean;
  }

  // export interface NavbarItem {
  //   title?: string;
  //   children?: SidebarItemV1[];
  // }
}
