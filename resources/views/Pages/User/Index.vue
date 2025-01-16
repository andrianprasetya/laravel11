<script setup>
import AppLayout from "@/Layouts/Template/AppLayout.vue";
import {Head} from "@inertiajs/vue3";
import userScript from "./Script/user.js";

const {
    filters,
    users,
    page,
    loading,
    totalRecords,
    rowOptions,
    rows,
    loadData,
    initFilters,
    formatDate,
    columns,
    onMounted,
    clearFilters,
    onPageChange,
    onSort,
    onFilterApply,
    onFilterClear,
    handleDelete,
    menu
} = userScript();

onMounted(() => {
    initFilters();
    loadData({page: page.value, rows: rows.value, sortField: null, sortOrder: null, filters: filters.value});
});

</script>

<template>
    <Head>
        <title>{{ menu }}</title>
    </Head>

    <app-layout>
        <div class="col-12">
            <div class="card">
                <!--dialog-->
                <ConfirmDialog/>
                <h5>{{ menu }}</h5>
                <DataTable :value="users"
                           :paginator="true"
                           :rows="rows"
                           :lazy="true"
                           :rowsPerPageOptions="rowOptions"
                           dataKey="id"
                           :rowHover="true"
                           :first="page * rows"
                           v-model:filters="filters"
                           :loading="loading"
                           :filterDisplay="'menu'"
                           showGridlines
                           :removable-sort="true"
                           :null-sort-order="-1"
                           :filters="filters"
                           :total-records="totalRecords"
                           @sort="onSort($event)"
                           @page="onPageChange($event)">
                    <template #header>
                        <div class="flex justify-content-between flex-column sm:flex-row">
                            <Button type="button" icon="pi pi-filter-slash" label="Clear" outlined
                                    @click="clearFilters()"/>
                        </div>
                    </template>
                    <template #empty> Users Not Found.</template>
                    <template #loading> Loading customers data. Please wait.</template>
                    <Column v-for="col in columns" :key="col.field"
                            :field="col.field"
                            :header="col.header"
                            :sortable="col.field !== 'actions'"
                            :filter="true"
                            :body-style="col.bodyStyle"
                            :showFilterMatchModes="false"
                            :style="col.style">
                        <template #body="{ data }">
                            <span v-if="col.field === 'created_at'">
                            {{ formatDate(data[col.field]) }}
                            </span>
                            <span v-else-if="col.field === 'email_verified_at'">
                                <i v-if="data[col.field]" class="pi pi-check-circle" style="color:green"></i>
                                <i v-else class="pi pi-times-circle" style="color:red"></i>
                            </span>
                            <span v-else-if="col.field === 'actions'">
                                <a :href="data.actions.edit" class="p-button p-button-link">
                                    <i class="pi pi-pen-to-square" style="color:green"></i>
                                </a>
                                <a :href="data.actions.show" class="p-button p-button-link">
                                    <i class="pi pi-eye" style="color:blue"></i>
                                </a>
                                <a href="#" class="p-button p-button-link"
                                   @click.prevent="handleDelete(data.actions.delete)">
                                    <i class="pi pi-trash" style="color:red"></i>
                                </a>
                            </span>
                            <span v-else>
                                {{ data[col.field] }}
                            </span>
                        </template>
                        <template v-if="col.field !== 'actions'" #filter="{ filterModel }">
                            <InputText v-if="col.field === 'name' || col.field === 'email'"
                                       type="text"
                                       v-model="filterModel.value"
                                       class="p-column-filter"
                                       :placeholder="'Search ' + col.header"/>
                            <Calendar v-if="col.field === 'created_at'" selection-mode="range"
                                      v-model="filterModel.value" placeholder="mm/dd/yyyy"/>
                            <label v-if="col.field === 'email_verified_at'" for="verified-filter"
                                   class="font-bold block my-2"> Verified </label>
                            <Checkbox v-if="col.field === 'email_verified_at'" v-model="filterModel.value"
                                      :indeterminate="filterModel.value === null" class="mx-2" binary
                                      inputId="verified-filter"/>
                        </template>
                        <template #filterapply="{ filterModel, field }">
                            <Button label="Apply" class="p-button-outlined p-button-sm"
                                    @click="onFilterApply(field, filterModel.value)"
                            />
                        </template>
                        <template #filterclear="{ field }">
                            <Button label="Clear" type="button" class="p-button-outlined p-button-sm"
                                    @click="onFilterClear(field)"
                            />
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </app-layout>
</template>

<style scoped lang="scss">
</style>
