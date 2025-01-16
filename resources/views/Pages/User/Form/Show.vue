<script setup>
import AppLayoutWithoutSidebar from "@/Layouts/Template/AppLayoutWithoutSidebar.vue";
import {Head, usePage} from "@inertiajs/vue3";
import Card from 'primevue/card';
import userScript from "../Script/user.js";

const {formatDate} = userScript()
const {props} = usePage();
const menu = props.menu + " Show";
const user = props.user;

user.value = {
    ...user,
    email_verified_at: new Date(user.email_verified_at)
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
        <!--        <div class="col-12">
                    <div class="card">
                        <div class="p-card-title">
                            <Button @click.prevent="goBack" outlined>
                                <i class="pi pi-arrow-left"></i>
                                <span class="ml-2 text-bold">Back</span>
                            </Button>
                        </div>
                        <div class="p-card-body">
                            <div class="p-fluid formgrid grid">
                                <div class="field col-12 md:col-12">
                                    <label for="name"><b>Name</b></label>
                                    <InputText id="name" size="large" type="text"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->

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
                        <InputText id="name" class="w-full" :value="user.value.name" disabled/>
                    </div>
                    <div class="field mb-4">
                        <label for="email" class="block text-bold mb-2">Email</label>
                        <InputText id="email" type="email" :value="user.value.email" class="w-full" disabled/>
                    </div>
                    <div class="field mb-4">
                        <label for="email_verified_at" class="block text-bold mb-2">Email Verified</label>
                        <InputText id="email_verified_at" type="text" :value="formatDate(user.value.email_verified_at)"
                                   class="w-full" disabled/>
                    </div>
                </template>
            </Card>
        </div>
    </app-layout-without-sidebar>
</template>

<style scoped lang="scss">
</style>
