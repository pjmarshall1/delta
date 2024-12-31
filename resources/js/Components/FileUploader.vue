<script setup>

import {onMounted, onUnmounted, reactive, ref, watch} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";

const emit = defineEmits(['onStatusUpdated']);

const props = defineProps({
    acceptedFileTypes: {
        type: String,
        default: 'text/csv',
    },
    maxFileSize: {
        type: Number,
        default: 1000 * 1000 * 2, // 2MB
    },
    namePattern: {
        type: RegExp,
    },
    uploadUrl: {
        type: String,
        default: '',
    },
    formData: {
        type: Object,
        default: {},
    }
});

const state = reactive({
    status: '',
    statusMessage: '',
    error: '',
});

const getErrorDetails = (attribute) => {
    const errorMap = {
        'file.size': {
            title: 'The file is too large',
            message: 'Max file size is 1MB',
        },
        'file.type': {
            title: 'Invalid file type',
            message: 'Accepted file types are CSV',
        },
        'file.required': {
            title: 'No file selected',
            message: 'Please upload a file before submitting',
        },
        'file.name': {
            title: 'Invalid file name',
            message: 'Please upload a file with a valid name',
        },
        // Add more attribute mappings here
    };

    return errorMap[attribute] || {
        title: 'Unknown Error',
        message: 'An unexpected error occurred. Please try again.',
    };
}

const uploadForm = useForm({
    file: null,
    ...props.formData,
});

const fileInput = ref(null);

const upload = () => {
    if (uploadForm.file && state.status === 'selected') {
        uploadForm.post(props.uploadUrl, {
            onProgress: (event) => {
                state.status = 'uploading';
                state.statusMessage = Math.round((event.loaded / event.total) * 100) + '%';
            },
            onError: (e) => {
                if (e.file) {
                    state.status = 'error';
                    state.errror = 'e.file';
                }
            },
        });
    }
}

watch(() => uploadForm.file, (newFile) => {
    if (newFile) {
        state.status = 'selected';

        if (!props.acceptedFileTypes.split(',').includes(newFile.type)) {
            state.status = 'error';
            state.error = 'file.type';
        } else if (uploadForm.file.size > props.maxFileSize) {
            state.status = 'error';
            state.error = 'file.size';
        } else if (props.namePattern && !props.namePattern.test(uploadForm.file.name)) {
            state.status = 'error';
            state.error = 'file.name';
        }
    } else {
        state.status = '';
        state.error = '';
    }
}, {deep: true});

onMounted(() => {
    const page = usePage();

    Echo.private('user.' + page.props.auth.user.id)
        .listen('UpdateStatus', (e) => {
            state.status = e.status;
            state.statusMessage = e.message;

            emit('onStatusUpdated', e.status);
        })
});

onUnmounted(() => {
    const page = usePage();
    Echo.leave('user.' + page.props.auth.user.id);
});

defineExpose({upload});

</script>

<template>
    <div class="relative overflow-hidden">
        <div :class="[uploadForm.file ? 'translate-y-16 scale-y-50 opacity-0' : 'translate-y-0',
             'absolute inset-0 w-full p-2 transition-all duration-250']">
            <label class="w-full h-full flex items-center justify-center cursor-pointer" for="fileInput">
                <span class="text-sm text-gray-700 text-medium">Click to browse</span>
            </label>
            <input v-show="false" id="fileInput" ref="fileInput" :accept="props.acceptedFileTypes" class="hidden"
                   type="file"
                   @change="uploadForm.file = $event.target.files[0]">
        </div>
        <div :class="[uploadForm.file ? 'translate-y-0 scale-y-100 opacity-1' : '-translate-y-16 scale-y-50 opacity-0',
             'absolute inset-0 w-full p-2 transition-all duration-500']">
            <div
                :class="{'bg-green-800': state.status === 'complete', 'bg-zinc-600': ['selected', 'uploading','processing'].includes(state.status), 'bg-red-800': state.status === 'error'}"
                class="h-full w-full p-2 flex items-center justify-between rounded-lg transition-colors duration-300">
                <div class="flex items-center space-x-2">
                    <!-- Remove file button -->
                    <button :class="['selected', 'error'].includes(state.status) ? 'opacity-100' : 'opacity-0'"
                            :disabled="['processing', 'complete'].includes(state.status)"
                            class="w-7 h-7 flex items-center justify-center bg-gray-900 rounded-full text-white border-2 border-stone-900/65 hover:border-white transition-opacity duration-500"
                            @click="uploadForm.reset('file');">
                        <svg height="26" viewBox="0 0 26 26" width="26" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.586 13l-2.293 2.293a1 1 0 0 0 1.414 1.414L13 14.414l2.293 2.293a1 1 0 0 0 1.414-1.414L14.414 13l2.293-2.293a1 1 0 0 0-1.414-1.414L13 11.586l-2.293-2.293a1 1 0 0 0-1.414 1.414L11.586 13z"
                                fill="currentColor" fill-rule="nonzero"></path>
                        </svg>
                    </button>
                    <!-- File details -->
                    <div :class="['complete','processing'].includes(state.status) ? '-translate-x-8' : 'translate-x-0'"
                         class="flex flex-col transition-transform duration-300">
                        <span class="text-sm text-white leading-2 font-medium">{{ uploadForm.file?.name }}</span>
                        <span class="text-xs text-gray-200">{{ numeral(uploadForm.file?.size).format('0[.][00] b')
                            }}</span>
                    </div>
                </div>
                <!-- Processing details -->
                <div
                    :class="['uploading', 'processing', 'complete'].includes(state.status) ? 'scale-100 opacity-100' : 'scale-50 opacity-0'"
                    class="absolute right-12 mr-1 flex flex-col justify-self-end text-right transition-transform duration-300 opacity-0">
                    <span class="text-sm text-white leading-2 font-medium">{{ state.status }}</span>
                    <span class="text-xs text-gray-200">{{ state.statusMessage }}</span>
                </div>
                <!-- Error details -->
                <div v-if="state.status === 'error'"
                     class="flex flex-col justify-self-end text-right transition-transform duration-300 ">
                    <span class="text-sm text-white leading-2 font-medium">{{ getErrorDetails(state.error).title
                        }}</span>
                    <span class="text-xs text-gray-200">{{ getErrorDetails(state.error).message }}</span>
                </div>
                <!-- Reset button -->
                <button
                    :class="['uploading', 'processing', 'complete'].includes(state.status) ? 'opacity-100 duration-300' : 'opacity-0 duration-0'"
                    :disabled="state.status !== 'complete'"
                    class="absolute right-4 w-7 h-7 flex items-center justify-center bg-gray-900 rounded-full text-white border-2 border-stone-900/65 disabled:hover:border-stone-900/65 hover:border-white duration-500  transition-opacity"
                    @click="uploadForm.reset('file')">
                    <svg v-if="['uploading', 'processing'].includes(state.status)" class="w-full h-full animate-spin"
                         viewBox="0 0 100 100">
                        <circle class="text-white" cx="50%" cy="50%" fill="transparent" r="40"
                                stroke="currentColor" stroke-dasharray="251.2 251.2" stroke-dashoffset="125.6"
                                stroke-linecap="round" stroke-width="6"
                        />
                    </svg>
                    <svg v-if="state.status === 'complete'" height="26" viewBox="0 0 26 26" width="26"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9.185 10.81l.02-.038A4.997 4.997 0 0 1 13.683 8a5 5 0 0 1 5 5 5 5 0 0 1-5 5 1 1 0 0 0 0 2A7 7 0 1 0 7.496 9.722l-.21-.842a.999.999 0 1 0-1.94.484l.806 3.23c.133.535.675.86 1.21.73l3.233-.803a.997.997 0 0 0 .73-1.21.997.997 0 0 0-1.21-.73l-.928.23-.002-.001z"
                            fill="currentColor" fill-rule="nonzero"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>

circle {
    transition: stroke-dashoffset 0.5s ease-in-out;
}

/* Spinner animation */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
