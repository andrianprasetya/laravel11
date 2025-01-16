<script setup>
import {Link} from "@inertiajs/vue3";
import Breadcrumb from 'primevue/breadcrumb';
import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';

const home = {
    icon: 'pi pi-home',
    url: '/',
    slug: 'home'
};

const {props} = usePage();
const currentRoute = computed(() => {
    const path = window.location.pathname;
    const segments = path.split('/').filter(Boolean);
    return segments[segments.length - 1]; // Last segment
});

// Dynamically set the active status based on the current route
const items = computed(() => {
    return props.breadcrumbs || [];
});


const isActiveBreadcrumb = (route) => {
console.log(currentRoute)
    // Check if the current route matches the breadcrumb's route
    return currentRoute.value === route;  // Or use a strict match based on your app's needs
};

</script>

<template>
    <div class="grid">
        <div class="col-12">
            <Breadcrumb :home="home" :model="items">
                <template #item="{ item, props }">
                    <Link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
                        <a :href="href" v-bind="props.action" @click="navigate">
                            <span :class="[item.icon, 'text-color']"></span>
                            <span class="text-primary font-semibold">{{ item.label }}</span>
                        </a>
                    </Link>
                    <a v-else :href="item.url" :target="item.target" v-bind="props.action">
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
