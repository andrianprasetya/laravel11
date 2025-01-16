<script setup>
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';

const position = ref('center');
const visible = ref(false);
const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);
const errorMessage = ref('');

const form = useForm({
    password: '',
});

const deleteUser = () => {
    errorMessage.value = '';
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => {
            errorMessage.value = 'Password is incorrect. Please try again.';
            passwordInput.value.focus();
        },
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};

const openDialog = () => {
    position.value = 'top';
    visible.value = true;
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Delete Account</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting
                your account, please download any data or information that you wish to retain.
            </p>
        </header>

        <Button label="DELETE ACCOUNT" icon="pi pi-trash" class="p-button-md mt-3" @click="openDialog" severity="danger"
                style="width:auto"/>

        <Dialog v-model:visible="visible" header="Are you sure you want to delete your account ?" :style="{ width: '50rem' }" :position="position" :modal="true" :draggable="false">
            <span class="p-text-secondary block mb-5">Once your account is deleted, all of its resources and data will be permanently deleted. Please
                            enter your password to confirm you would like to permanently delete your account.</span>
            <div class="flex align-items-center gap-3 mb-5">
                <label for="password" class="font-semibold w-6rem">Password</label>
                <InputText id="password" @keyup.enter="deleteUser" v-model="form.password" class="flex-auto" autocomplete="off" />
            </div>
            <InlineMessage v-if="errorMessage" severity="error" class="block mb-3">{{ errorMessage }}</InlineMessage>
            <div class="flex justify-content-end gap-2">
                <Button type="button" label="CANCEL" severity="secondary" outlined @click="visible = false"></Button>
                <Button type="button" class="p-button-danger" label="DELETE ACCOUNT" @click="deleteUser"></Button>
            </div>
        </Dialog>
        <!--        <DangerButton @click="confirmUserDeletion">Delete Account</DangerButton>-->

        <!--        <Modal :show="confirmingUserDeletion" @close="closeModal">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Are you sure you want to delete your account?
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Once your account is deleted, all of its resources and data will be permanently deleted. Please
                            enter your password to confirm you would like to permanently delete your account.
                        </p>

                        <div class="mt-6">
                            <InputLabel for="password" value="Password" class="sr-only" />

                            <TextInput
                                id="password"
                                ref="passwordInput"
                                v-model="form.password"
                                type="password"
                                class="mt-1 block w-3/4"
                                placeholder="Password"
                                @keyup.enter="deleteUser"
                            />

                            <InputError :message="form.errors.password" class="mt-2" />
                        </div>

                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                            <DangerButton
                                class="ms-3"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                                @click="deleteUser"
                            >
                                Delete Account
                            </DangerButton>
                        </div>
                    </div>
                </Modal>-->
    </section>
</template>

<style>
</style>
