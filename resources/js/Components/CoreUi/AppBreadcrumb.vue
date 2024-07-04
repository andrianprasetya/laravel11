<!--<script setup>
import { ref, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';


const breadcrumbs = ref([]);

const getBreadcrumbs = (route) => {
    return route.matched.map((route) => {
        return {
            active: route.path === route.fullPath,
            name: route.name,
            path: `${route.path}`,
        };
    });
};

// Watch for changes in the current route
const { url } = usePage();

watch(
    () => url.value,
    (newRoute) => {
        breadcrumbs.value = getBreadcrumbs(newRoute);
    },
    { immediate: true }
);

// Set initial breadcrumbs
onMounted(() => {
    const { value: route } = usePage();
    breadcrumbs.value = getBreadcrumbs(route);
});
</script>-->
<script setup>
import { onMounted, ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

import {CBreadcrumb,CBreadcrumbItem} from '@coreui/vue'

const breadcrumbs = ref([])

const getBreadcrumbs = (routes) => {
    return routes.map((route) => ({
        active: route.path === window.location.pathname,
        name: route.name,
        path: route.path,
    }))
}

const { props } = usePage()

onMounted(() => {
    breadcrumbs.value = getBreadcrumbs(props.breadcrumbs)
})

watch(() => props.breadcrumbs, (newBreadcrumbs) => {
    breadcrumbs.value = getBreadcrumbs(newBreadcrumbs)
})
</script>

<template>
    <CBreadcrumb class="my-0">
        <CBreadcrumbItem
            v-for="item in breadcrumbs"
            :key="item.path"
            :href="item.active ? '' : item.path"
            :active="item.active"
        >
            {{ item.name }}
        </CBreadcrumbItem>
    </CBreadcrumb>
</template>
