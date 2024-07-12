<script setup>
import { ref, computed, watchEffect } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

const breadcrumbHome = ref({ icon: 'pi pi-home', to: '/' });
const activeIndex = ref(0);

const page = usePage();
const currentUrl = computed(() => page.url);

const breadcrumbItems = computed(() => {
    const parts = currentUrl.value.split('/').filter(Boolean);
    console.log(parts)
    return parts.map((part, index) => ({
        label: part.charAt(0).toUpperCase() + part.slice(1),
        to: '/' + parts.slice(0, index + 1).join('/')
    }));
});

watchEffect(() => {
    // Update activeIndex based on the current URL
    activeIndex.value = breadcrumbItems.value.length - 1;
});


</script>

<template>
    <div class="grid">
        <div class="col-12">
            <Breadcrumb :home="breadcrumbHome" :model="breadcrumbItems">
                <template #item="{ item, props }">
                    <Link v-if="item.to" :href="item.to" custom :class="{ 'active': props.isActive }" v-bind="props.action">
                            <span :class="[item.icon, 'text-color']"></span>
                            <span class="text-primary font-semibold">{{ item.label }}</span>
                    </Link>
                    <a v-else :href="item.url" :target="item.target" :class="{ 'active': props.isActive }" v-bind="props.action">
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
