import {SidebarConfigArray, SidebarItem} from '@vuepress/theme-default/lib/shared/nav';

/**
 * Loop through a v1 sidebar to transform it into a v2 compatible one.
 */
export function transformSidebar(
  children: SidebarV1ConfigArray | SidebarConfigArray,
): SidebarConfigArray {
  return children.map((child): SidebarItem | string => {
    if (typeof child !== 'string' && child.children) {
      child.children = transformSidebar(child.children);
    }

    if (typeof child === 'string') {
      return `/${child}`;
    }

    const newItem = child as SidebarItem;

    if (child.hasOwnProperty('title')) {
      const {title} = child as SidebarItemV1;
      newItem.text = title ?? '';
    }

    return newItem;
  });
}
