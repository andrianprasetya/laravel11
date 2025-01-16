<script setup>
import AppLayoutWithoutSidebar from "@/Layouts/Template/AppLayoutWithoutSidebar.vue";
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import {Head} from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile"/>

    <AppLayoutWithoutSidebar>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Profile</h2>
        </template>

        <div class="grid">
            <div class="col-12 md:col-6">
                <div class="card p-fluid">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>
            </div>
            <div class="col-12 md:col-6">
                <div class="card p-fluid">
                    <UpdatePasswordForm class="max-w-xl"/>
                </div>
            </div>
            <div class="col-12">
                <div class="card p-fluid">
                    <DeleteUserForm class="max-w-xl"/>
                </div>
            </div>
        </div>
    </AppLayoutWithoutSidebar>
</template>
