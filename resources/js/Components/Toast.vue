<script setup>

import {onMounted} from "vue";

const emit = defineEmits(['onRemoveToast']);

const props = defineProps({
    id: {
        type: Number,
        required: true,
    },
    message: {
        type: String,
        required: true,
        default: 'Hello, World!',
    },
    type: {
        type: String,
        required: true,
        default: 'info',
    },
    options: {
        type: Object,
        default: () => ({}),
    },
});

const icons = (type) => ({
    success: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="size-7 text-green-500 rounded-full"><circle cx="12" cy="12" r="9" /><path d="M9 12.75l2.25 2.25L15 9.75" /></svg>`,
    error: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="size-7 text-red-500 rounded-full"><circle cx="12" cy="12" r="9" /><path d="M12 9v3.75" /><path d="M12 15.75h.008v.008H12v-.008Z" /></svg>`,
    info: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="size-7 text-blue-500 rounded-full"><circle cx="12" cy="12" r="9" /><path d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021" /><path d="M12 8.25h.008v.008H12z" /></svg>`,
    warning: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="size-7 text-yellow-500 rounded-full"><path d="M2.697 16.126c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126Z" /><path d="M12 15.75h.007v.008H12v-.008Z" /><path d="M12 9v3.75" /></svg>`,
}[type]);

const toastClass = (type) => ({
    success: 'bg-green-500 text-white',
    error: 'bg-red-500 text-white',
    info: 'bg-blue-500 text-white',
    warning: 'bg-yellow-500 text-white',
}[type] || 'bg-gray-500 text-white');

onMounted(() => {
    if (props.options?.timeout !== false) {
        setTimeout(() => {
            emit('onRemoveToast', props.id);
        }, props.options?.timeout);
    }
});

</script>

<template>
    <div class="min-w-80 mt-2 p-4 rounded-lg" :class="toastClass(props.type)">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="flex flex-shrink-0 items-center justify-center" v-html="icons(props.type)" />
                <div>
                    <p class="font-medium">{{ props.message }}</p>
                </div>
            </div>
            <div v-if="props.options.dismissible" class="ml-4">
                <button type="button" v-on:click="emit('onRemoveToast', props.id)"
                        class="inline-flex items-center justify-center  p-1 text-white/50 hover:text-white rounded-full focus:outline-none focus:ring-offset-transparent transition duration-200">
                    <span class="sr-only">Dismiss</span>
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
