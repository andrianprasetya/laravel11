<script setup>
import AppMenuItem from './AppMenuItem.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const menu = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get('/api/menus');
        menu.value = response.data;
    } catch (error) {
        console.error('Error fetching menu:', error);
    }
});
console.log(menu)
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in menu" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
        <li>
            <a href="https://www.primefaces.org/primeblocks-vue/#/" target="_blank">
                <img src="/layout/images/banner-primeblocks.png" alt="Prime Blocks" class="w-full mt-3" />
            </a>
        </li>
    </ul>
</template>

<style lang="scss" scoped></style>
