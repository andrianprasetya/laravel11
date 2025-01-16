<script setup>
import AppLayout from "@/Layouts/Template/AppLayout.vue";
import {Head} from "@inertiajs/vue3";
import sessionScript from "./Script/session.js";
import {usePage} from '@inertiajs/vue3';

const {props} = usePage();
const menu = props.menu

const {
    filters,
    sessions,
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
    getDecodedPayload,
} = sessionScript();

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
                <h5>{{ "User " + menu }}</h5>
                <DataTable :value="sessions"
                           :paginator="true"
                           :rows="rows"
                           :lazy="true"
                           :rowsPerPageOptions="rowOptions"
                           :first="page * rows"
                           dataKey="id"
                           :rowHover="true"
                           v-model:filters="filters"
                           :loading="loading"
                           :filters="filters"
                           :filterDisplay="'menu'"
                           showGridlines
                           :removable-sort="true"
                           :null-sort-order="-1"
                           :total-records="totalRecords"
                           @sort="onSort($event)"
                           @page="onPageChange($event)">
                    <template #header>
                        <div class="flex justify-content-between flex-column sm:flex-row">
                            <Button type="button" icon="pi pi-filter-slash" label="Clear" outlined
                                    @click="clearFilters()"/>
                            <!--                            <IconField iconPosition="left">
                                                            <InputIcon class="pi pi-search"/>
                                                            <InputText
                                                                v-model="filters.global.value"
                                                                placeholder="Keyword Search"
                                                                style="width: 100%"
                                                            />
                                                        </IconField>-->
                        </div>
                    </template>
                    <template #empty> Session Not Found.</template>
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
                            <span v-if="col.field === 'last_activity'">
                            {{ formatDate(data[col.field]) }}
                            </span>
                            <span v-else-if="col.field === 'actions'">
                                <a :href="data.actions.show" class="p-button p-button-link">
                                    <i class="pi pi-eye" style="color:blue"></i>
                                </a>
                            </span>
                            <span v-else-if="col.field === 'payload'">
                            {{ getDecodedPayload(data[col.field]) }}
                            </span>
                            <span v-else>
                                {{ data[col.field] }}
                            </span>
                        </template>
                        <template v-if="col.field !== 'payload'" #filter="{ filterModel }">
                            <InputText v-if="col.field === 'name' || col.field === 'email'"
                                       type="text"
                                       v-model="filterModel.value"
                                       class="p-column-filter"
                                       :placeholder="'Search ' + col.header"/>
                            <Calendar v-if="col.field === 'last_activity'" placeholder="mm/dd/yyyy"
                                      selection-mode="single"
                                      v-model="filterModel.value"/>
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
                    <!--                    <Column field="ip_address" header="IP Address" :style="{ width: '10%' }">
                                            <template #body="{ data }">
                                                <div style="text-align: center;">
                                                    {{ data.ip_address }}
                                                </div>
                                            </template>
                                            <template #filter="{ filterModel }">
                                                <InputText type="text" v-model="filterModel.value" class="p-column-filter"
                                                           placeholder="Search by name"/>
                                            </template>
                                        </Column>
                                        <Column field="user.name" header="Name" sortable filter :style="{ width: '10%' }">
                                            <template #body="{ data }">
                                                <div style="text-transform: capitalize;">
                                                    {{ data.user.name }}
                                                </div>
                                            </template>
                                            <template #filter="{ filterModel }">
                                                <InputText type="text" v-model="filterModel.value" class="p-column-filter"
                                                           placeholder="Search by name"/>
                                            </template>
                                        </Column>
                                        <Column field="user.email" header="Email" sortable filter :style="{ width: '10%' }">
                                            <template #body="{ data }">
                                                {{ data.user.email }}
                                            </template>
                                            <template #filter="{ filterModel }">
                                                <InputText type="text" v-model="filterModel.value" class="p-column-filter"
                                                           placeholder="Search by name"/>
                                            </template>
                                        </Column>
                                        <Column field="payload" header="Payload">
                                            <template #body="{ data }">
                                                {{ getDecodedPayload(data.payload) }}
                                            </template>
                                        </Column>
                                        <Column field="last_activity" header="Last Activity" sortable filter :style="{ width: '15%' }">
                                            <template #body="{ data }">
                                                <div style="text-align: center;">
                                                    {{ formatDate(data.last_activity) }}
                                                </div>
                                            </template>
                                            <template #filter="{ filterModel }">
                                                <Calendar v-model="filterModel.value" dateFormat="mm/dd/yy" placeholder="mm/dd/yyyy"/>
                                            </template>
                                        </Column>-->
                </DataTable>
            </div>
        </div>
    </app-layout>
</template>

<style scoped lang="scss">

</style>
