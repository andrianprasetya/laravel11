<script setup>
import AppLayout from "@/Layouts/Template/AppLayout.vue";
import {Head, usePage, router} from "@inertiajs/vue3";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
defineProps({
    sessions: {
        type: Array
    }
})

</script>

<template>
    <Head>
        <title>Log</title>
    </Head>

    <app-layout>
        <div class="col-12">
            <div class="card">
                <h5>Log Session</h5>
                <DataTable :data="sessions" :columns="columns" tableStyle="min-width: 50rem">
                    <Column field="user_id" header="User ID"></Column>
                    <Column field="ip_address" header="Ip Address"></Column>
                    <Column field="last_activity" header="Last Activity"></Column>
                </DataTable>
            </div>
        </div>
    </app-layout>
</template>

<style scoped>

</style>
