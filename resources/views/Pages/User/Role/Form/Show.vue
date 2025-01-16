<script setup>
import AppLayoutWithoutSidebar from "@/Layouts/Template/AppLayoutWithoutSidebar.vue";
import {Head, usePage} from "@inertiajs/vue3";
import Card from 'primevue/card';
import {ref} from "vue";
import roleScript from "../Script/role.js";

const {
    getSeverity,
    getLabel,
} = roleScript();
const {props} = usePage();
const menu = props.menu + " Show";
const role = props.role;
const dropdownValue = ref(role.is_active);
const dropdownValues = ref([
    {name: 'In-active', value: 0},
    {name: 'Active', value: 1},
]);

const goBack = () => {
    window.history.back();
};
</script>

<template>
    <Head>
        <title>{{ menu }}</title>
    </Head>

    <app-layout-without-sidebar>
        <div class="form-container">
            <Card>
                <!-- Card Header -->
                <template #header>
                    <Button class="mt-5 ml-5" @click.prevent="goBack" outlined>
                        <i class="pi pi-arrow-left"></i>
                        <span class="ml-2 text-bold">Back</span>
                    </Button>
                </template>

                <!-- Card Body -->
                <template #content>
                    <div class="field mb-4">
                        <label for="name" class="block text-bold mb-2">Name</label>
                        <InputText id="name" class="w-full" :value="role.name" disabled/>
                    </div>
                    <div class="field mb-4">
                        <label for="email" class="block text-bold mb-2">Description</label>
                        <Textarea id="description" :value="role.description" class="w-full" disabled/>
                    </div>
                    <div class="field mb-4">
                        <label for="is_active" class="block text-bold mb-2">Status</label>
                        <Dropdown id="is_active" v-model="dropdownValue" option-value="value" :options="dropdownValues"
                                  optionLabel="name"
                                  placeholder="Select a city" disabled>
                            <template #value="slotProps">
                                <Tag :severity="getSeverity(slotProps.value)"
                                     v-if="slotProps.value !== null && slotProps.value !== undefined">
                                    {{ getLabel(slotProps.value) }}
                                </Tag>
                                <span v-else>{{ slotProps.placeholder }}</span>
                            </template>
                        </Dropdown>
                    </div>
                </template>
            </Card>
        </div>
    </app-layout-without-sidebar>
</template>

<style scoped lang="scss">
</style>
