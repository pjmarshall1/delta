<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import {Head} from '@inertiajs/vue3';
import UpdateProfilePhotoForm from "@/Pages/Profile/Partials/UpdateProfilePhotoForm.vue";

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
    <AuthenticatedLayout title="Profile">
        <div class="w-full grid grid-cols-12 gap-5">
                <div class="col-span-4 bg-white p-8 shadow rounded-lg">
                    <UpdateProfilePhotoForm/>
                </div>
                <div class="col-span-8 bg-white p-8 shadow rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-3xl"/>
                </div>

            <div class="col-span-12 bg-white p-8 shadow rounded-lg">
                <UpdatePasswordForm class="max-w-xl"/>
            </div>

            <div class="col-span-12 bg-white p-8 shadow rounded-lg">
                <DeleteUserForm class="max-w-xl"/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
