<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import {computed} from "vue";

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
const logoUrl = computed(() => {
    return `/layout/images/logo.png`;
});
</script>

<template>
    <Head>
        <title>Email Verification</title>
    </Head>
    <div
        class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <img :src="logoUrl" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0"/>
            <div
                style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, rgb(2, 6, 23) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card " style="border-radius: 53px">
                    <div class="py-8 px-5 sm:px-8">
                        <div class="custom-card">
                            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400 custom-text-wrap">
                                Thanks for signing up! Before getting started, could you verify your email address by
                                clicking on the link
                                we just emailed to you? If you didn't receive the email, we will gladly send you
                                another.
                            </div>

                            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 custom-text-wrap"
                                 v-if="verificationLinkSent">
                                A new verification link has been sent to the email address you provided during
                                registration.
                            </div>
                        </div>
                        <form @submit.prevent="submit">
                            <div class="flex justify-content-between items-center w-full mt-4">
                                <!-- Resend Button -->
                                <Button
                                    label="Resend Verification Email"
                                    type="submit"
                                    class="p-button p-button-sm"
                                    style="background-color: #020617; color: #ffffff;"
                                />

                                <!-- Log Out Link -->

                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    class="text-sm justify-content-end text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 rounded"
                                >
                                    Log Out
                                </Link>

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
