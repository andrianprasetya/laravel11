<script setup>
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import { useToast } from 'primevue/usetoast';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const loading = ref(false);
const toast = useToast();

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    loading.value = true;
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            loading.value = false;
            toast.add({severity: 'success', summary: 'Success', detail: 'Password update successfully!', life: 3000});
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
            loading.value = false;
            toast.add({severity: 'error', summary: 'Error', detail: 'Password update failed!', life: 3000});
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Password</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div class="formgrid grid">
                <div class="field col-12 md:col-12">
                    <label for="current_password"><b>Current Password</b></label>
                    <InputText id="current_password" size="large" :class="{'is-invalid': form.errors.current_password}" v-model="form.current_password" type="password"/>
                    <InlineMessage
                        v-if="form.errors.current_password"
                        class="block mt-1 text-red-600 mb-3">
                        {{ form.errors.current_password }}
                    </InlineMessage>
                </div>
                <div class="field col-12 md:col-12">
                    <label for="password"><b>New Password</b></label>
                    <InputText id="password" size="large" :class="{'is-invalid': form.errors.password}" v-model="form.password" type="password"/>
                    <InlineMessage
                        v-if="form.errors.password"
                        class="block mt-1 text-red-600 mb-3">
                        {{ form.errors.password }}
                    </InlineMessage>
                </div>
                <div class="field col-12 md:col-12">
                    <label for="password_confirmation"><b>Confirm Password</b></label>
                    <InputText id="password_confirmation" size="large" :class="{'is-invalid': form.errors.password_confirmation}" v-model="form.password_confirmation" type="password"/>
                    <InlineMessage
                        v-if="form.errors.password_confirmation"
                        class="block text-red-600">
                        {{ form.errors.password_confirmation }}
                    </InlineMessage>
                </div>
            </div>
                <div class="flex justify-content-end">
                    <div class="col-12 md:col-2">
                        <Button type="submit" label="Save" outlined :loading="loading"/>
                    </div>
                </div>
            <!--            <div>
                            <InputLabel for="current_password" value="Current Password" />

                            <TextInput
                                id="current_password"
                                ref="currentPasswordInput"
                                v-model="form.current_password"
                                type="password"
                                class="mt-1 block w-full"
                                autocomplete="current-password"
                            />

                            <InputError :message="form.errors.current_password" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="password" value="New Password" />

                            <TextInput
                                id="password"
                                ref="passwordInput"
                                v-model="form.password"
                                type="password"
                                class="mt-1 block w-full"
                                autocomplete="new-password"
                            />

                            <InputError :message="form.errors.password" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="password_confirmation" value="Confirm Password" />

                            <TextInput
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                autocomplete="new-password"
                            />

                            <InputError :message="form.errors.password_confirmation" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                            </Transition>
                        </div>-->
        </form>
    </section>
</template>
