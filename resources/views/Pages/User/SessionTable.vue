<script setup>



export default {
    name: "SessionTable",
    props: {
        sessions: {
            type: Array,
            required: true,
        },
        totalRecords: {
            type: Number,
            required: true,
        },
    },
    methods: {
        onPage(event) {
            this.$emit('paginate', event);
        },
    },
}
</script>
<template>
    <div>
        <DataTable :value="sessions" :rows="5" :paginator="true" :rowsPerPageOptions="[5, 10, 20, 50]"  :totalRecords="totalRecords" responsiveLayout="scroll"
                   :lazy="true" @page="onPage">
            <Column field="user.name" header="Name" :sortable="true" style="width: 35%"></Column>
            <Column field="ip_address" header="Ip Address" :sortable="true" style="width: 35%"></Column>
            <Column field="last_activity" header="Activity" style="width: 15%" :sortable="true">
            </Column>
        </DataTable>
    </div>
</template>

<style scoped>

</style>
