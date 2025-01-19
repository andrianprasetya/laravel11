<script setup>
import AppLayoutWithoutSidebar from "@/Layouts/Template/AppLayoutWithoutSidebar.vue";
import {Head, usePage} from "@inertiajs/vue3";
import Card from 'primevue/card';
import Divider from 'primevue/divider';
import sessionScript from "../Script/session.js";

const {
    formatDate,
    getDecodedPayload,
} = sessionScript();


const {props} = usePage();
const menu = props.menu + " Show";
const session = props.session;
const user = props.user;
session.value = {
    ...session,
    last_activity: new Date(session.last_activity)
}

const goBack = () => {
    window.history.back();
};
</script>

<template>
    <Head>
        <title>{{ menu }}</title>
    </Head>

    <app-layout-without-sidebar>
        <div class="grid">
            <div class="col-12 md:col-4">
                <div class="form-container">
                    <Card>
                        <!-- Card Header -->
                        <template #header>
                            <div class="grid">
                                <div class="col-12 md:col-4">
                                    <Button class="mt-5 ml-5" @click.prevent="goBack" outlined>
                                        <i class="pi pi-arrow-left"></i>
                                        <span class="ml-2 text-bold">Back</span>
                                    </Button>
                                </div>
                                <div class="col-12 md:col-6 mt-5">
                                    <h3 class="text-lg font-bold">User Information</h3>
                                </div>
                            </div>
                            <Divider/>
                        </template>
                        <!-- Card Body -->
                        <template #content>
                            <div class="field">
                                <label for="name" class="block text-bold mb-2">Name</label>
                                <InputText id="name" class="w-full" :value="user.name" disabled/>
                            </div>
                            <div class="field">
                                <label for="email" class="block text-bold mb-2">Email</label>
                                <InputText id="email" type="email" :value="user.email" class="w-full" disabled/>
                            </div>
                            <!--                    <div class="field mb-4">
                                                    <label for="email_verified_at" class="block text-bold mb-2">Email Verified</label>
                                                    <InputText id="email_verified_at" type="text" :value="formatDate(session.value.email_verified_at)"
                                                               class="w-full" disabled/>
                                                </div>-->
                        </template>
                    </Card>
                </div>
            </div>
            <div class="col-12 md:col-8">
                <div class="form-container">
                    <Card>
                        <!-- Card Header -->
                        <template #header>
                            <div class="mt-5 text-center">
                                <h3 class="text-lg font-bold">Session Information</h3>
                            </div>
                            <Divider/>
                        </template>
                        <!-- Card Body -->
                        <template #content>
                            <div class="field mb-4">
                                <label for="ip_address" class="block text-bold mb-2">IP Address</label>
                                <InputText id="ip_address" class="w-full" :value="session.ip_address" disabled/>
                            </div>
                            <div class="field mb-4">
                                <label for="user_agent" class="block text-bold mb-2">Tools</label>
                                <InputText id="user_agent" type="text" :value="session.user_agent" class="w-full"
                                           disabled/>
                            </div>
                            <div class="field mb-4">
                                <label for="payload" class="block text-bold mb-2">Payload</label>
                                <Textarea id="payload" autoResize :value="getDecodedPayload(session.payload)"
                                          class="w-full" disabled/>
                            </div>
                            <div class="field mb-4">
                                <label for="last_activity" class="block text-bold mb-2">Last Activity</label>
                                <InputText id="last_activity" type="text" :value="formatDate(session.last_activity)" class="w-full"
                                           disabled/>
                            </div>
                            <!--                    <div class="field mb-4">
                                                    <label for="email_verified_at" class="block text-bold mb-2">Email Verified</label>
                                                    <InputText id="email_verified_at" type="text" :value="formatDate(session.value.email_verified_at)"
                                                               class="w-full" disabled/>
                                                </div>-->
                        </template>
                    </Card>
                </div>
            </div>
        </div>
    </app-layout-without-sidebar>
</template>

<style scoped lang="scss">
</style>
