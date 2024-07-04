import {defineComponent, h, onMounted, ref, resolveComponent} from 'vue'
import {Link, usePage} from '@inertiajs/vue3'

import {CBadge, CSidebarNav, CNavItem, CNavGroup, CNavTitle} from '@coreui/vue'
import nav from '@/_nav.js'

import simplebar from 'simplebar-vue'
import 'simplebar-vue/dist/simplebar.min.css'

const normalizePath = (path) =>
    decodeURI(path)
        .replace(/#.*$/, '')
        .replace(/(index)?\.(html)$/, '')

const isActiveLink = (route, link) => {

    if (link === undefined) {
        return false
    }

    if (route === link) {
        return true
    }

    const currentPath = normalizePath(route.url)
    const targetPath = normalizePath(link)

    return currentPath === targetPath
}

const isActiveItem = (route, item) => {
    if (isActiveLink(route, item.to)) {
        return true
    }
    if (item.items) {
        return item.items.some((child) => isActiveItem(route, child))
    }

    return false
}

const AppSidebarNav = defineComponent({
    name: 'AppSidebarNav',
    components: {
        CNavItem,
        CNavGroup,
        CNavTitle,
    },
    setup() {
        const { url: route } = usePage();
        const firstRender = ref(true);

        onMounted(() => {
            firstRender.value = false;
        });

        const renderItem = (item) => {
            if (item.items) {
                return h(
                    CNavGroup,
                    {
                        as: 'div',
                        compact: true,
                        ...(firstRender.value && {
                            visible: item.items.some((child) => isActiveItem(route, child)),
                        }),
                    },
                    {
                        togglerContent: () => [
                            h(resolveComponent('CIcon'), {
                                customClassName: 'nav-icon',
                                name: item.icon,
                            }),
                            item.name,
                        ],
                        default: () => item.items.map((child) => renderItem(child)),
                    },
                );
            }

            return item.to
                ? h(
                    'div',
                    { class: 'nav-item' },
                    [
                        h(
                            'a',
                            {
                                class: isActiveLink(route, item.to) ? 'active nav-link' : 'nav-link',
                                href: item.to,
                                'aria-current': isActiveLink(route, item.to) ? 'page' : undefined,
                            },
                            [
                                item.icon
                                    ? h(resolveComponent('CIcon'), {
                                        customClassName: 'nav-icon',
                                        name: item.icon,
                                    })
                                    : h('span', { class: 'nav-icon' }, h('span', { class: 'nav-icon-bullet' })),
                                item.name,
                                item.badge &&
                                h(
                                    CBadge,
                                    {
                                        class: 'ms-auto',
                                        color: item.badge.color,
                                    },
                                    {
                                        default: () => item.badge.text,
                                    },
                                ),
                            ],
                        ),
                    ]
                )
                : h(
                    resolveComponent(item.component),
                    {
                        as: 'div',
                    },
                    {
                        default: () => item.name,
                    },
                );
        };

        return () =>
            h(
                CSidebarNav,
                {
                    as: simplebar,
                },
                {
                    default: () => nav.map((item) => renderItem(item)),
                },
            );
    },
});

export {AppSidebarNav}
