

<script setup>
import {Link} from "@inertiajs/vue3";
import Breadcrumb from 'primevue/breadcrumb';
import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';

const home = {
    icon: 'pi pi-home',
    url: '/'
};

const {props} = usePage();

const items = computed(() => props.breadcrumbs || []);
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
                        <span class="text-surface-700 dark:text-surface-0">{{ item.label }}</span>
                    </a>
                </template>
            </Breadcrumb>
        </div>
    </div>
</template>

<style scoped>
.active {
    font-weight: bold;
    color: #42b983;
}
</style>
