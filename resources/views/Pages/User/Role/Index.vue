<script setup>
import AppLayout from "@/Layouts/Template/AppLayout.vue";
import {Head} from "@inertiajs/vue3";
import roleScript from "./Script/role.js";

const {
    filters,
    roles,
    page,
    loading,
    totalRecords,
    rowOptions,
    rows,
    loadData,
    initFilters,
    columns,
    onMounted,
    clearFilters,
    onPageChange,
    onSort,
    onFilterApply,
    onFilterClear,
    handleDelete,
    statuses,
    getSeverity,
    getLabel,
    menu
} = roleScript();

onMounted(() => {
    initFilters();
    loadData({page: page.value, rows: rows.value, sortField: null, sortOrder: null, filters: filters.value});
});

</script>

<template>
    <Head>
        <title>{{menu}}</title>
    </Head>

    <app-layout>
        <div class="col-12">
            <div class="card">
                <!--dialog-->
                <ConfirmDialog/>
                <h5>{{ "User " + menu }}</h5>
                <DataTable :value="roles"
                           :paginator="true"
                           :rows="rows"
                           :rowsPerPageOptions="rowOptions"
                           dataKey="id"
                           :rowHover="true"
                           v-model:filters="filters"
                           :first="page * rows"
                           :loading="loading"
                           filterDisplay="menu"
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
                                    @click="clearFilters"/>
                        </div>
                    </template>
                    <template #empty> Roles Not Found.</template>
                    <template #loading> Loading roles data. Please wait.</template>
                    <!-- Dynamic Columns -->
                    <Column v-for="col in columns" :key="col.field"
                            :field="col.field"
                            :header="col.header"
                            :sortable="col.field !== 'description' || col.field === 'actions'"
                            :filter="true"
                            :body-style="col.bodyStyle"
                            :showFilterMatchModes="false"
                            :style="col.style">
                        <template #body="{ data }">
                            <!-- Custom rendering for 'is_active' column -->
                            <span v-if="col.field === 'is_active'">
                                <Tag :severity="getSeverity(data[col.field])">
                                    {{ data[col.field] === 0 ? 'In-active' : 'Active' }}
                                </Tag>
                            </span>
                            <span v-else-if="col.field === 'actions'">
                                <a :href="data.actions.edit" class="p-button p-button-link">
                                    <i class="pi pi-pen-to-square" style="color:green"></i>
                                </a>
                                <a :href="data.actions.show" class="p-button p-button-link">
                                    <i class="pi pi-eye" style="color:blue"></i>
                                </a>
                                <a href="#" class="p-button p-button-link" @click.prevent="handleDelete(data.actions.delete)">
                                    <i class="pi pi-trash" style="color:red"></i>
                                </a>
                            </span>
                            <span v-else>
                                {{ data[col.field] }}
                            </span>
                        </template>
                        <template v-if="col.field !== 'description' || col.field === 'actions'" #filter="{ filterModel }">
                            <InputText v-if="col.field === 'name'"
                                       type="text"
                                       v-model="filterModel.value"
                                       class="p-column-filter"
                                       :placeholder="'Search ' + col.header"/>
                            <Textarea v-if="col.field === 'description'"
                                      type="text"
                                      v-model="filterModel.value"
                                      class="p-column-filter"
                                      :placeholder="'Search ' + col.header"/>
                            <Dropdown v-if="col.field === 'is_active'" option-label="label" option-value="value"
                                      v-model="filterModel.value" :options="statuses" placeholder="Any"
                                      class="p-column-filter" :showClear="true">
                                <template #value="slotProps">
                                    <Tag :severity="getSeverity(slotProps.value)"
                                         v-if="slotProps.value !== null && slotProps.value !== undefined">
                                        {{ getLabel(slotProps.value) }}
                                    </Tag>
                                    <span v-else>{{ slotProps.placeholder }}</span>
                                </template>
                                <template #option="slotProps">
                                    <Tag :severity="getSeverity(slotProps.option.value)">{{
                                            slotProps.option.label
                                        }}
                                    </Tag>
                                </template>
                            </Dropdown>

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
