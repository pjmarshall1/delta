<script setup>

import {router} from "@inertiajs/vue3";
import {ref} from "vue";

import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import FileUploader from "@/Components/FileUploader.vue";

const emit = defineEmits(['onCancel', 'onUpload']);

const fileUploader = ref(null);

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const handleUploadButtonClick = () => {
    fileUploader.value.upload();
}

const handleFileUploaderStatusUpdated = (status) => {
    if (status === 'complete') {
        router.reload({only: ['scans']});
    }
}
</script>

<template>
    <Modal :closeable="props.closeable" :show="show" @close="emit('onCancel')">
        <div class="p-8 space-y-5">
            <div class="bg-white">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Import Scans</h3>
            </div>

            <div class="flex flex-col space-y-4">
                <div class="space-y-4">
                    <div class="space-y-2">
                        <InputLabel for="filePond" value="Upload a file to import"/>
                        <FileUploader ref="fileUploader"
                                      :namePattern="/^\d{8}_Momo\.csv$/"
                                      :uploadUrl="route('scans.import')"
                                      class="h-16 w-full bg-gray-200 border border-gray-300 rounded-lg"
                                      @onStatusUpdated="handleFileUploaderStatusUpdated"
                        />
                    </div>
                </div>
                <div class="w-full flex items-center justify-end space-x-5">
                    <SecondaryButton @click="emit('onCancel')">Cancel</SecondaryButton>
                    <PrimaryButton @click="handleUploadButtonClick">Upload</PrimaryButton>
                </div>
            </div>
        </div>
    </Modal>
</template>
