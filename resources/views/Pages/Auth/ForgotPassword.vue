<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import {computed} from "vue";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
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
        <title>Forgot Password</title>
    </Head>
    <div
        class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <img :src="logoUrl" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0"/>
            <div
                style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, rgb(2, 6, 23) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card" style="border-radius: 53px">
                    <Button class="ml-5 mt-5" @click="handleLink" outlined>
                        <i class="pi pi-arrow-left"></i>
                        <span class="ml-2 text-bold">Back</span>
                    </Button>
                    <div class="py-6 px-4 sm:px-6">
                        <div class="custom-card">
                            <div class="mb-4 text-sm text-gray-700 custom-text-wrap">
                                Forgot your password? No problem. Just let us know your email address and we will email
                                you a password reset link that will allow you to choose a new one.
                            </div>
                        </div>

                        <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ status }}
                        </div>
                        <form @submit.prevent="submit">
                            <div>
                                <label for="email" class="block text-900 text-xl font-medium mb-2">Email</label>
                                <InputText id="email" type="email" placeholder="Email"
                                           :class="['w-full mb-4', { 'is-invalid': form.errors.email }]"
                                           style="padding: 1rem" v-model="form.email" autofocus/>
                                <InlineMessage
                                    v-if="form.errors.email"
                                    class="block mt-1 text-red-600 mb-3">
                                    {{ form.errors.email }}
                                </InlineMessage>

                                <div class="flex justify-content-end flex-column sm:flex-row">
                                    <Button label="EMAIL PASSWORD RESET LINK" type="submit"
                                            class="block w-fit p-3 text-sm"
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

.custom-text-wrap {
    word-wrap: break-word; /* Breaks long words to wrap to the next line */
    overflow-wrap: break-word; /* Same as word-wrap for modern browsers */
    white-space: normal; /* Ensures the text will wrap */
    max-width: 100%; /* Constrain the text within the container */
    display: block; /* Ensures it takes up available width */
    word-break: break-word; /* Break words if necessary */
    line-height: 1.6; /* Adds line spacing for better readability */
}

/* Optional: To further control the container's max width */
.custom-card {
    max-width: 500px; /* Adjust this based on your preferred card width */
}
</style>
