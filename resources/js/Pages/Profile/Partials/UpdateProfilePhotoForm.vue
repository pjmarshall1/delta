<script setup>

import {usePage, useForm} from "@inertiajs/vue3";
import {computed} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const form = useForm({
    photo: null,
})

const handleInputChange = (event) => {
    form.photo = event.target.files[0];
};

const preview = computed(() => {
    return form.photo ? URL.createObjectURL(form.photo) : usePage().props.auth.user.profile_photo_url;
});

const handleUpdatePhoto = () => {
    if (form.photo) {
        form.post(route('profile.photo.store'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
            }
        });
    }
}

const handleRemovePhoto = () => {
    form.delete(route('profile.photo.destroy'));
}

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Photo
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile photo.
            </p>
        </header>

        <form @submit.prevent class="mt-6 space-y-6">
            <div class="mt-2 flex flex-col items-center space-y-5">
                <label for="file" class="cursor-pointer">
                    <img class="h-28 w-28 rounded-full bg-gray-50"
                         :src="preview"
                         :alt="usePage().props.auth.user.name"/>
                </label>
                <input id="file" type="file" class="hidden" accept="image/*" v-on:change="handleInputChange"/>
            </div>
            <div class="flex flex-col items-center justify-center space-y-3 w-full">
                <PrimaryButton type="button" v-on:click="handleUpdatePhoto"
                               class="w-36 flex items-center justify-center">
                    <span>Update Image</span>
                </PrimaryButton>
                <SecondaryButton type="button" v-on:click="handleRemovePhoto"
                                 class="w-36 flex items-center justify-center">
                    <span>Remove Image</span>
                </SecondaryButton>
            </div>
        </form>
    </section>
</template>
