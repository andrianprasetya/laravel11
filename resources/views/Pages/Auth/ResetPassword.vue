<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import {computed} from "vue";

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
const logoUrl = computed(() => {
    return `/layout/images/logo.png`;
});

const handleLink = () => {
    window.location.href = "/"
};
</script>

<template>
    <Head>
        <title>Reset password</title>
    </Head>
    <div
        class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <img :src="logoUrl" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0"/>
            <div
                style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, rgb(2, 6, 23) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card " style="border-radius: 53px">

                    <div class="py-8 px-5 sm:px-8">
                        <form @submit.prevent="submit">
                            <div>
                                <label for="email" class="block text-900 text-xl font-medium mb-2">Email</label>
                                <InputText id="email" type="email" placeholder="Email address"
                                           :class="['w-full md:w-30rem mb-4', { 'is-invalid': form.errors.email }]"
                                           style="padding: 1rem" v-model="form.email" autofocus/>
                                <InlineMessage
                                    v-if="form.errors.email"
                                    class="block mt-1 text-red-600 mb-3">
                                    {{ form.errors.email }}
                                </InlineMessage>

                                <label for="password" class="block text-900 font-medium text-xl mb-2">Password</label>
                                <InputText id="password" type="password" v-model="form.password" placeholder="Password"
                                           :toggleMask="true"
                                           :class="['w-full md:w-30rem mb-4', { 'is-invalid': form.errors.password }]"
                                           inputClass="w-full" style="padding: 1rem"
                                           autocomplete="current-password"/>
                                <InlineMessage
                                    v-if="form.errors.password"
                                    class="block mt-1 text-red-600 mb-3">
                                    {{ form.errors.password }}
                                </InlineMessage>

                                <label for="password__confirmation" class="block text-900 font-medium text-xl mb-2">Confirm
                                    Password</label>
                                <InputText id="password_confirmation" type="password"
                                           v-model="form.password_confirmation"
                                           placeholder="Confirm Password"
                                           :toggleMask="true"
                                           :class="['w-full md:w-30rem mb-4', { 'is-invalid': form.errors.password_confirmation }]"
                                           inputClass="w-full" style="padding: 1rem"/>
                                <InlineMessage
                                    v-if="form.errors.password_confirmation"
                                    class="block mt-1 text-red-600 mb-3">
                                    {{ form.errors.password_confirmation }}
                                </InlineMessage>

                                <div class="flex justify-content-end flex-column sm:flex-row">
                                    <Button label="Reset Password" type="submit"
                                            class="block w-fit p-3 text-md"
                                            style="background: rgb(2, 6, 23) 10%"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.p-button {
    border-color: rgb(2, 6, 23, 0.1) !important;
}
.p-button.p-button-outlined {
    color: black !important;
}
</style>
