<script setup>
import {ref, computed} from 'vue';

const email = ref('');
const password = ref('');
const checked = ref(false);

// Errors object
const errors = ref({});

import {Head, Link, useForm} from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },

});
const form = useForm({
    email: '',
    password: '',
    remember: false,
});
// Submit Form
const submit = () => {
        form.post('/login', {
            onFinish: () => form.reset('password'),
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
        <title>Login</title>
    </Head>
    <div class="surface-ground flex align-items-center justify-content-center
     min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <img :src="logoUrl" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0"/>
            <div
                style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card " style="border-radius: 53px">
                    <Link :href="route('home')">
                    <Button class="ml-5 mt-5" outlined>
                        <i class="pi pi-arrow-left"></i>
                        <span class="ml-2 text-bold">Back</span>
                    </Button>
                    </Link>
                    <div class="py-8 px-5 sm:px-8">
                        <div class="text-center mb-5">
                            <img src="/layout/images/avatar.jpg" alt="Image" height="50" class="mb-3"/>
                            <div class="text-900 text-3xl font-medium mb-3">Welcome!</div>
                            <span class="text-600 font-medium">Sign in to continue</span>
                        </div>
                        <form @submit.prevent="submit">
                            <div>
                                <label for="email" class="block text-900 text-xl font-medium mb-2">Email</label>
                                <InputText id="email" type="email" placeholder="Email address"
                                           class="w-full md:w-30rem mb-3"
                                           :invalid="!!form.errors.email"
                                           style="padding: 1rem" v-model="form.email"
                                           aria-describedby="email-help"
                                           autofocus/>
                                <InlineMessage
                                    v-if="form.errors.email"
                                    class="block mt-1 text-red-600 mb-3">
                                    {{ form.errors.email }}
                                </InlineMessage>

                                <label for="password" class="block text-900 font-medium text-xl mb-2">Password</label>
                                <InputText id="password" type="password" v-model="form.password" placeholder="Password"
                                           :toggleMask="true"
                                           class="w-full md:w-30rem mb-3"
                                           :invalid="!!form.errors.password"
                                           inputClass="w-full" style="padding: 1rem"
                                           autocomplete="current-password" />
                                <InlineMessage
                                    v-if="form.errors.password"
                                    class="block mt-1 text-red-600 mb-3">
                                    {{ form.errors.password }}
                                </InlineMessage>

                                <div class="flex align-items-center justify-content-between mb-5 gap-5">
                                    <div class="flex align-items-center">
                                        <Checkbox v-model:checked="form.remember" id="rememberme1" binary
                                                  class="mr-2"></Checkbox>
                                        <label for="rememberme1">Remember me</label>
                                    </div>
                                    <a href="forgot-password" class="font-medium no-underline ml-2 text-right cursor-pointer"
                                       style="color: var(--primary-color)">Forgot password?</a>
                                </div>
                                <Button label="Sign In" type="submit" class="w-full p-3 text-xl"
                                />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
