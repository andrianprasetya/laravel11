<script setup>
import Breadcrumb from 'primevue/breadcrumb';
import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';

const home = {
    icon: 'pi pi-home',
    url: '/',
    slug: 'home'
};

const {props} = usePage();
const activeUrl = computed(() => {
    const path = window.location.pathname;
    const segments = path.split('/').filter(Boolean);
    if (segments.includes('show')) {
        return "show";
    } else if (segments.includes('edit')) {
        return "edit"
    } else {
        return segments[segments.length - 1];
    }
});

// Dynamically set the active status based on the current route
const items = computed(() => {
    return props.breadcrumbs || [];
});


const isActiveBreadcrumb = (slug) => {
    return activeUrl.value === slug;
};

</script>

<template>
    <div class="grid">
        <div class="col-12">
            <Breadcrumb :home="home" :model="items">
                <template #item="{ item, props }">
                    <a :href="item.url" :target="item.target" v-bind="props.action">
                        <span :class="[item.icon, 'text-color']"></span>
                        <span :class="{'action' : isActiveBreadcrumb(item.slug)}">{{ item.label }}</span>
                    </a>
                </template>
            </Breadcrumb>
        </div>
    </div>
</template>

<style scoped>
.action {
    font-weight: bold;
    color: #42b983;
}
</style>
