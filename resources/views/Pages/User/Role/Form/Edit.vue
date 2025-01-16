<script setup>
import AppLayoutWithoutSidebar from "@/Layouts/Template/AppLayoutWithoutSidebar.vue";
import {Head} from "@inertiajs/vue3";

</script>

<template>
    <Head>
        <title>{{ menu }}</title>
    </Head>

    <app-layout-without-sidebar>
        <div class="col-12">
            <div class="card">
                <!--dialog-->

            </div>
        </div>
    </app-layout-without-sidebar>
</template>

<style scoped lang="scss">
</style>
